<?php


namespace LaravelMerax\FileServer\App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class File extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->original_name,
            'size'      => $this->size,
            'mimeType'  => $this->mime_type,
            'owner'     => '',
            'createdAt' => $this->created_at->toDatetimeString(),
        ];
    }
}
