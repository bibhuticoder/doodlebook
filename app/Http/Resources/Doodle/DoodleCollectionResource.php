<?php

namespace App\Http\Resources\Doodle;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DoodleCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($doodle){
            return [
                'id' => $doodle->id,
                'title' => $doodle->title,
                'description' => str_limit($doodle->description, 10),
                'image' => $doodle->image,
                'likes' => $doodle->likes->count(),
                'comments' => $doodle->comments->count(),
                'user' => $doodle->user,
            ];
        }); 
    }
}
