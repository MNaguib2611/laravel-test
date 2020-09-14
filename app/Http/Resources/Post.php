<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
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
            'id' => $this->id,
            'user' => $this->user,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'comments' => $this->comments,
        ];
    }
}
