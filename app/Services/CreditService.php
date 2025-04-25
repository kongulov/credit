<?php

namespace App\Services;

use App\Exceptions\LoanConditionException;
use App\Interfaces\CreditRepositoryInterface;
use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Credit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CreditService
{
    public function __construct(
        protected CreditRepositoryInterface $creditRepository,
        protected CustomerRepositoryInterface $customerRepository
    ) {
    }

    public function getCustomerCredits(int $customerId): Collection
    {
        $customer = $this->customerRepository->findById($customerId);

        return $this->creditRepository->getCustomerCredits($customer);
    }

    public function addCustomerCredits(int $customerId, array $details): Credit
    {
        $customer = $this->customerRepository->findById($customerId);

        $this->loanConditions($customer);

        if ($customer->region === 'OS') $details['rate'] += 5;

        $this->creditApprovedLog($customer);

        return $this->creditRepository->addCustomerCredits($customer, $details);
    }

    private function loanConditions($customer): void
    {
        if ($customer->score <= 500)
            throw new LoanConditionException($customer,'Credit score too low.');

        if ($customer->monthly_income < 1000)
            throw new LoanConditionException($customer,'Monthly income too low.');

        if ($customer->age < 18 || $customer->age > 60)
            throw new LoanConditionException($customer,'Age not eligible for credit.');

        if (!in_array($customer->region, ['PR', 'BR', 'OS']))
            throw new LoanConditionException($customer,'Region not eligible.');

        if ($customer->region === 'PR' && random_int(0, 1) === 1)
            throw new LoanConditionException($customer,'Credit request randomly declined for Prague.');
    }

    private function creditApprovedLog($customer): void
    {
        $log = now()->format('d.m.Y H:i:s').' Уведомление клиенту '.$customer->name.': Кредит одобрен.';
        Log::info($log);
    }
}
