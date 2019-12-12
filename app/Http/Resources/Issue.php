<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Http\Resources\User as UserResource;

class Issue extends JsonResource
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
            "type" => "issues",
            "id" => $this->id,
            "attributes" => [
                "title" => $this->title,
                "description" => $this->description,
                "priority" => $this->priority,
                "status" => $this->status,
                "assigned_to" => (!empty($this->assigned_to)) ? new UserResource($this->assigned_to) : null,
                "creator" => (!empty($this->creator)) ? new UserResource($this->creator) : null,
                "type" => $this->type
            ],
            "links" => [
                'self' => route('issue.show', [$this])
            ]
        ];
    }
}
