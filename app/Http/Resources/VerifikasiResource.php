<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class VerifikasiResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'status_qr' => $this->status_qr,
            'status_pin' => $this->status_pin,
            'pin' => $this->pin
        ];
    }
}
