<?php

namespace App\Repositories;

use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Support\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    private array $relations = [];

    public function list(): Collection
    {
        return Customer::query()->with($this->relations)->get();
    }

    public function findById(int $itemId): Customer
    {
        return Customer::query()->with($this->relations)->findOrFail($itemId);
    }

    public function create(array $details): Customer
    {
        return Customer::create($details);
    }

    public function update(int $itemId, array $details): bool
    {
        return Customer::query()->where('id', $itemId)->update($details);
    }

    public function delete(int $itemId): bool
    {
        return Customer::query()->where('id', $itemId)->delete();
    }

    public function setRelations(array $relations): self
    {
        $this->relations = array_merge($this->relations, $relations);

        return $this;
    }
}
