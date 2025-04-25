<?php

namespace Tests\Unit;

use App\Models\Customer;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    /** @test */
    public function it_can_list_all_customers()
    {
        $response = $this->getJson('/api/customers');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'created_at'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_can_create_a_customer()
    {
        $customerData = [
            'name' => fake()->name(),
            'age' => fake()->numberBetween(18, 60),
            'region' => fake()->randomElement(['PR', 'BR', 'OS', 'NS', 'SS', 'RS']),
            'income' => fake()->numberBetween(1000, 4000),
            'score' => fake()->numberBetween(100, 1000),
            'pin' => (string)fake()->unique()->randomNumber(5),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
        ];

        $response = $this->postJson('/api/customers', $customerData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'created_at',
            ]
        ]);

        $this->assertDatabaseHas('customers', $customerData);
    }

    /** @test */
    public function it_can_show_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone
            ]
        ]);
    }

    /** @test */
    public function it_can_update_a_customer()
    {
        $customer = Customer::factory()->create();

        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '9876543210'
        ];

        $response = $this->putJson("/api/customers/{$customer->id}", $updatedData);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $updatedData
        ]);

        $this->assertDatabaseHas('customers', $updatedData);
    }

    /** @test */
    public function it_can_delete_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_customer()
    {
        $response = $this->postJson('/api/customers', []);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $response = $this->postJson('/api/customers', [
            'name' => fake()->name(),
            'age' => fake()->numberBetween(18, 60),
            'region' => fake()->randomElement(['PR', 'BR', 'OS', 'NS', 'SS', 'RS']),
            'income' => fake()->numberBetween(1000, 4000),
            'score' => fake()->numberBetween(100, 1000),
            'pin' => (string)fake()->unique()->randomNumber(5),
            'email' => 'invalid-email',
            'phone' => fake()->phoneNumber(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_prevents_duplicate_pin_registration()
    {
        $existingCustomer = Customer::factory()->create();

        $response = $this->postJson('/api/customers', [
            'name' => fake()->name(),
            'age' => fake()->numberBetween(18, 60),
            'region' => fake()->randomElement(['PR', 'BR', 'OS', 'NS', 'SS', 'RS']),
            'income' => fake()->numberBetween(1000, 4000),
            'score' => fake()->numberBetween(100, 1000),
            'pin' => $existingCustomer->pin,
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pin']);
    }

    /** @test */
    public function it_returns_404_for_non_existent_customer()
    {
        $response = $this->getJson('/api/customers/999');
        $response->assertStatus(404);
    }

}
