<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Doc extends JsonResource
{
    public static $wrap = 'data';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    #Al sobreescribir esto pongo como quiero que se vea mi JSON
    public function toArray($request)
    {
        return [
            "type" => "docs",
            "id" => $this->id,
            "attributes" => [
                "name" => $this->name,
                "extension" => $this->extension,
                "size" => $this->size,
                "title" => $this->title
            ],
            "links" => [
                'self' => route('doc.show', [$this]),
                'download' => $this->url_file_link
            ]
        ];
    }
}
