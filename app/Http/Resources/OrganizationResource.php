<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'phone' => $this->whenHas('phone', $this->phone),
            'city' => $this->whenHas('city', $this->city),
            'deleted_at' => $this->whenHas('deleted_at', $this->deleted_at),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts'))
        ] + parent::toArray($request);
    }
}
