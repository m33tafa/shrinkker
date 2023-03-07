<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL as FacadesURL;

class UrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'original_url' => $this->url,
            'code' => $this->code,
            'shrinkked_url' => FacadesURL::to('/'. $this->code),
            'creation_time' => $this->created_at,
            'hits' => $this->hits
        ];
    }
}
