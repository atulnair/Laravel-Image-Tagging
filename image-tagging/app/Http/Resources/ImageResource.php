<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>(string)$this->id,
            'type'=>$this->type,
            'category'=>$this->category != null ? $this->category : '',
            'url'=>asset('storage/'.$this->title),
            'size'=>[
                'height'=>$this->height,
                'width'=>$this->width,
            ],
            'tags'=> TagResource::collection($this->tags),
        ];
    }
}
