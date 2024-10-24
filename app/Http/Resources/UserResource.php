<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'thoughts' => new ThoughtCollection($this->whenLoaded('thoughts')),
            'notifications' => new NotificationCollection($this->whenLoaded('notifications')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
