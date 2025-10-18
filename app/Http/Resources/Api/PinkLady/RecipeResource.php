<?php

namespace App\Http\Resources\Api\PinkLady;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 'title' => $this->title, 'slug' => $this->slug,
            'country_code' => $this->country_code, 'content' => $this->content,
            'status' => $this->status, 'created_at' => $this->created_at,
        ];
    }
}
