<?php

namespace App\Interfaces;

use App\Models\Customer;
use Illuminate\Support\Collection;

interface CustomerRepositoryInterface
{
    public function list(): Collection;
    public function findById(int $itemId): Customer;
    public function create(array $details): Customer;
    public function update(int $itemId, array $details): bool;
    public function delete(int $itemId): bool;
    public function setRelations(array $relations): self;
}
