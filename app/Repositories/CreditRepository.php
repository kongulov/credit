<?php

namespace App\Repositories;

use App\Interfaces\CreditRepositoryInterface;
use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Credit;
use App\Models\Customer;
use Illuminate\Support\Collection;

class CreditRepository implements CreditRepositoryInterface
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository
    ) {}

    public function getCustomerCredits(Customer $customer): Collection
    {
        return $customer->credits;
    }

    public function addCustomerCredits(Customer $customer, array $details): Credit
    {
        return $customer->credits()->create($details);
    }
}
