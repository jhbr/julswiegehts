<?php

namespace App\Http\Resources\Account;

use App\Http\Resources\Island\IslandResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'twitch_name' => $this->twitch_name,
            'email' => $this->email,
            $this->mergeWhen($this->isAdmin(), [
                'is_admin' => 1
            ]),
            'island' => new IslandResource($this->island()->first()),

        ];

    }
}
