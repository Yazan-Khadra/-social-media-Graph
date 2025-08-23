<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $year = $this->year->Year_name;
        $major = $this->major;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year' => $year,
            'major' => $major
        ];
    }
}
