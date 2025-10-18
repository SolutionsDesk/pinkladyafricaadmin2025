<?php

namespace App\Http\Resources\Api\PinkLady;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->settings;
    }
}
