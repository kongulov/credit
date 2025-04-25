<?php

namespace App\Interfaces;

use App\Models\Customer;

interface CreditRepositoryInterface
{
    public function getCustomerCredits(Customer $customer);

    public function addCustomerCredits(Customer $customer, array $details);
}
