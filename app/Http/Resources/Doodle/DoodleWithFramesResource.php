<?php

namespace App\Http\Resources\Doodle;

use Illuminate\Http\Resources\Json\JsonResource;

class DoodleWithFramesResource extends JsonResource
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
            'image' => $this->image,
            'description' => $this->description,
            'frames' => $this->frames_sorted,
            'animation_detail' => $this->animationDetail,
            'visibility' => $this->visibility,
        ];
    }
}
