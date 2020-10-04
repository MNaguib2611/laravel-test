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
            'id'                => $this->id,
            'user'              => new User($this->whenLoaded('user')),
            'title'             => $this->title,
            'content'           => $this->content,
            'created_at'        =>$this->created_at->diffForHumans(),
            'comments'          => Comment::collection($this->whenLoaded('comments')),
            'comments_count'    => $this->whenLoaded('comments', $this->comments_count),    
        ];
    }
}
