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
            'name' => $this->name,
            'surname' => $this->surname,
            'birthdate' => $this->birthdate,
            'parent' => self::make($this->whenLoaded('parent')),
            'partner' => self::make($this->whenLoaded('partner')),
        ];
    }
}
