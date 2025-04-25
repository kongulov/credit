<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Tests\TestCase;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use Illuminate\Validation\ValidationException;

class CustomerServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->customerService = new CustomerService(new CustomerRepository());
    }

    /** @test */
    public function it_can_create_customer_with_valid_data()
    {
        $customerData = [
            'name' => 'John Doe',
            'age' => 25,
            'region' => 'PR',
            'income' => 2000,
            'score' => 500,
            'pin' => (string)fake()->unique()->randomNumber(5),
            'email' => 'john@example.com',
            'phone' => '1234567890'
        ];

        $customer = $this->customerService->create($customerData);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals($customerData['name'], $customer->name);
        $this->assertEquals($customerData['age'], $customer->age);
        $this->assertEquals($customerData['region'], $customer->region);
        $this->assertEquals($customerData['income'], $customer->income);
        $this->assertEquals($customerData['score'], $customer->score);
        $this->assertEquals($customerData['pin'], $customer->pin);
        $this->assertEquals($customerData['email'], $customer->email);
        $this->assertEquals($customerData['phone'], $customer->phone);
    }

    /** @test */
    public function it_throws_exception_for_invalid_age()
    {
        $customerData = [
            'name' => 'John Doe',
            'age' => 15,
            'region' => 'PR',
            'income' => 2000,
            'score' => 500,
            'pin' => (string)fake()->unique()->randomNumber(5),
            'email' => 'john@example.com',
            'phone' => '1234567890'
        ];

        try {
            $this->customerService->create($customerData);
            $this->fail('Expected ValidationException not thrown');
        } catch (ValidationException $e) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function it_throws_exception_for_invalid_email_format()
    {
        $this->expectException(ValidationException::class);

        $customerData = [
            'name' => 'John Doe',
            'age' => 25,
            'region' => 'PR',
            'income' => 2000,
            'score' => 500,
            'pin' => (string)fake()->unique()->randomNumber(5),
            'email' => 'invalid-email',
            'phone' => '1234567890'
        ];

        $this->customerService->create($customerData);
    }

    /** @test */
    public function it_throws_exception_for_duplicate_pin()
    {
        $firstCustomer = $this->customerService->create([
            'name' => 'John Doe',
            'age' => 25,
            'region' => 'PR',
            'income' => 2000,
            'score' => 500,
            'pin' => (string)fake()->unique()->randomNumber(5),
            'email' => 'john@example.com',
            'phone' => '1234567890'
        ]);

        $customerData = [
            'name' => 'Jane Doe',
            'age' => 30,
            'region' => 'BR',
            'income' => 3000,
            'score' => 600,
            'pin' => '12345',
            'email' => 'jane@example.com',
            'phone' => '0987654321'
        ];

        try {
            $this->customerService->create($customerData);
            $this->fail('Expected UniqueConstraintViolationException not thrown');
        } catch (UniqueConstraintViolationException $e) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function it_can_update_customer_data()
    {
        $customer = $this->customerService->create([
            'name' => 'John Doe',
            'age' => 25,
            'region' => 'PR',
            'income' => 2000,
            'score' => 500,
            'pin' => (string)fake()->unique()->randomNumber(5),
            'email' => 'john@example.com',
            'phone' => '1234567890'
        ]);

        $updatedData = [
            'name' => 'John Updated',
            'income' => 2500,
            'phone' => '9876543210'
        ];

        $updatedCustomer = $this->customerService->update($customer->id, $updatedData);

        $this->assertEquals($updatedData['name'], $updatedCustomer->name);
        $this->assertEquals($updatedData['income'], $updatedCustomer->income);
        $this->assertEquals($updatedData['phone'], $updatedCustomer->phone);
        $this->assertEquals(25, $updatedCustomer->age);
        $this->assertEquals('PR', $updatedCustomer->region);
    }

    /** @test */
    public function it_can_delete_customer()
    {
        $customer = $this->customerService->create([
            'name' => 'John Doe',
            'age' => 25,
            'region' => 'PR',
            'income' => 2000,
            'score' => 500,
            'pin' => (string)fake()->unique()->randomNumber(5),
            'email' => 'john@example.com',
            'phone' => '1234567890'
        ]);

        $result = $this->customerService->delete($customer->id);

        $this->assertTrue($result);
        $this->expectException(ModelNotFoundException::class);
        $this->customerService->findById($customer->id);
    }

    /** @test */
    public function it_throws_exception_when_updating_non_existent_customer()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->customerService->update(999, [
            'name' => 'John Updated'
        ]);
    }

    /** @test */
    public function it_throws_exception_when_deleting_non_existent_customer()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->customerService->delete(999);
    }
}
