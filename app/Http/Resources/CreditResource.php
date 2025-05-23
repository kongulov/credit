<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'rate' => $this->rate,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
        ];
    }
}
