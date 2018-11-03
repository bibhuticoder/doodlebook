<?php

namespace App\Http\Resources\Doodle;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment\CommentCollectionResource;
use App\Models\DoodleLike;
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
            'likes' => $this->likes->count(),
            'created_at' => $this->created_at->toFormattedDateString(),
            //'starred' => $request->auth->id //($request->auth) ?
                // ((DoodleLike::where('doodle_id', $this->id)->where('user_id', $request->auth->id)->count() === 1) ? 1 : NULL)
                // : NULL
        ];
    }
}
