<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'paginate' => [
                'current_page' => $this->currentPage(),
                'total_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'links' => [
                    'next_page' => $this->nextPageUrl(),
                    'prev_page' => $this->previousPageUrl(),
                ]
            ]
        ];
    }
}
