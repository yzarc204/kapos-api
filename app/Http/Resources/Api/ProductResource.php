<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'slug' => $this->slug,
            'cost' => $this->cost,
            'price' => $this->price,
            'unit' => $this->unit,
            'description' => $this->description,
            'feature_image' => ($this->feature_image) ? asset($this->feature_image) : null
        ];
    }
}
