<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // TODO: Refactor.
        return [
            'id'            => $this->id,
            'user'          => new User($this->whenLoaded('user')),
            'content'       => $this->content,
            'created_at'    => $this->created_at->diffForHumans(),

        ];
    }
}
