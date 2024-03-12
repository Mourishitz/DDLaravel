<?php

namespace App\Core\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoreResource extends JsonResource
{
    protected array $withoutFields = [];

    /**
     * Set the keys that are supposed to be filtered out.
     *
     *
     * @return void
     */
    public function hideField(array $fields)
    {
        $this->withoutFields = $fields;
    }

    /**
     * Remove the filtered keys.
     */
    protected function filterFields($array): array
    {
        return collect($array)->forget($this->withoutFields)->toArray();
    }
}
