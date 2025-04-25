<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return CustomerResource::collection($this->customerService->list());
    }

    public function show(int $itemId): CustomerResource
    {
        return new CustomerResource($this->customerService->findById($itemId));
    }

    public function store(CustomerStoreRequest $request): CustomerResource
    {
        return new CustomerResource($this->customerService->create($request->validated()));
    }

    public function update(CustomerUpdateRequest $request, int $itemId): CustomerResource
    {
        return new CustomerResource($this->customerService->update($itemId, $request->validated()));
    }

    public function destroy(int $itemId): JsonResponse
    {
        $this->customerService->delete($itemId);

        return response()->json([
            'message' => 'Customer deleted successfully',
        ]);
    }
}
