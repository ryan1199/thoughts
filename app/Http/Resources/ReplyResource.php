<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
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
            'slug' => $this->slug,
            'content' => $this->content,
            'edited_contents' => $this->edited_contents,
            'pinned' => $this->pinned,
            'user' => new UserResource($this->whenLoaded('user')),
            'thought' => new ThoughtResource($this->whenLoaded('thought')),
            'reply' => new ReplyResource($this->whenLoaded('reply')),
            'replied' => $this->replied,
            'replies' => new ReplyCollection($this->whenLoaded('replies')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
