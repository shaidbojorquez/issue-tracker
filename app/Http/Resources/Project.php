<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Project extends JsonResource
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
            "type" => "projects",
            "id" => $this->id,
            "attributes" => [
                "title" => $this->title,
                "description" => $this->description,
                "begin_date" => $this->begin_date,
                "end_date" => $this->end_date,
                "status" => $this->status
            ],
            "links" => [
                'self' => route('project.show', [$this])
            ]
        ];
    }
}
