<?php

namespace App\Http\Resources\Doodle;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment\CommentCollectionResource;

class DoodleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'comments' => new CommentCollectionResource($this->comments),
            'likes' => $this->likes->count()
        ];
    }
}
