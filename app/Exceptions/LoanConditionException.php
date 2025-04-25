<?php

namespace App\Exceptions;

use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Log;

class LoanConditionException extends Exception
{
    protected Customer $customer;

    public function __construct(Customer $customer, string $message = "Loan condition failed.", int $code = 422)
    {
        parent::__construct($message, $code);

        $this->customer = $customer;
    }

    public function render($request)
    {
        $log = now()->format('d.m.Y H:i:s').' Уведомление клиенту '.$this->customer->name.': Кредит отклонен. Причина: '.$this->getMessage();
        Log::info($log);

        return response()->json([
            'message' => $log,
        ], $this->getCode());
    }
}
