<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditStoreRequest;
use App\Http\Resources\CreditResource;
use App\Services\CreditService;
use App\Services\CustomerService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CreditController extends Controller
{
    public function __construct(
        protected CustomerService $customerService,
        protected CreditService $creditService
    ) {}

    public function getCustomerCredits(int $customerId): AnonymousResourceCollection
    {
        return CreditResource::collection($this->creditService->getCustomerCredits($customerId));
    }

    public function addCustomerCredits(CreditStoreRequest $request, int $customerId): CreditResource
    {
        return new CreditResource($this->creditService->addCustomerCredits($customerId, $request->validated()));
    }
}
