<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'gender' => $this->gender,
            'name' => $this->name,
            'surname' => $this->surname,
            'birthdate' => $this->birthdate,
            'children' => self::collection($this->whenLoaded('children')),
            'parent' => ParentPersonResource::make($this->whenLoaded('parent')),
            'partner' => self::make($this->whenLoaded('partner')),
        ];
    }
}
