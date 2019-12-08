<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    public static $wrap = 'data';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "type" => "users",
            "id" => $this->id,
            "attributes" => [
                "name" => $this->name,
                "email" => $this->email
            ],
            "links" => [
                'self' => route('user.show', [$this])
            ]
        ];
    }
}
