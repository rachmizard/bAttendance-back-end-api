<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MyHistoryResource extends Resource
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
            'action' => $this->status,
            'jam' => $this->created_at->diffForHumans(),
            'nama' => $this->karyawan->nama,
            'tanggal' => $this->created_at->format('D, d M Y'),
            'karyawan_id' => $this->karyawan->id,
            'foto' => 'http://attendance.birutekno.com/public/storage/images/'. $this->karyawan->fp
        ];
    }
}
