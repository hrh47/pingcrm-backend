<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'owner' => $this->owner,
            'photo' => $this->photo_path ? URL::route('image', ['path' => $this->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
