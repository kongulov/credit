<?php

namespace App\Services;

use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CustomerService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
    ) {

    }

    public function list(): Collection
    {
        return $this->customerRepository->list();
    }

    public function findById(int $itemId): Customer
    {
        return $this->customerRepository->findById($itemId);
    }

    public function create(array $details): Customer
    {
        if ($details['age'] < 18 || $details['age'] > 60) {
            throw ValidationException::withMessages([
                'age' => 'Age must be between 18 and 60.',
            ]);
        }
        if (!filter_var($details['email'], FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'email' => 'Invalid email format.',
            ]);
        }

        return $this->customerRepository->create($details);
    }

    public function update(int $itemId, array $details): Customer
    {
        $this->customerRepository->update($itemId, $details);

        return $this->findById($itemId);
    }

    public function delete(int $itemId): bool
    {
        if (!$this->customerRepository->delete($itemId)) {
            throw new ModelNotFoundException("Customer with ID {$itemId} not found.");
        }

        return true;
    }
}
