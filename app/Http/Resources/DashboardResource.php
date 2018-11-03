<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DashboardResource extends Resource
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
            'nama' => $this->karyawan->nama,
            'status' => $this->status,
        ];
    }
}
