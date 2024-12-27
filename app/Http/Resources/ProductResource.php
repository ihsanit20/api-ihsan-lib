<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mrp' => $this->mrp,
            'selling_price' => $this->selling_price,
            'ISBN' => $this->ISBN,
            'description' => $this->description,
            'photo' => $this->photo,
            'barcode' => $this->barcode,
            'barcode_image' => $this->barcode_image,
            'available_stock' => $this->available_stock,
            'categories' => $this->categories->pluck('name'), 
            'authors' => $this->authors->map(function ($author) {
                return [
                    'id' => $author->id,
                    'name' => $author->name,
                    'role' => $author->pivot->role,
                ];
            }),
        ];
    }
}
