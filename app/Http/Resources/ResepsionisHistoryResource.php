<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ResepsionisHistoryResource extends Resource
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
            'divisi' => $this->karyawan->divisi . ' / ' . $this->karyawan->jabatan,
            'jam' => $this->verifikasi->created_at->format('h:i:s A'),
            'foto' => $this->karyawan->fp != null ? 'http://attendance.birutekno.com/public/storage/images/'. $this->karyawan->fp : 'http://attendance.birutekno.com/public/images/avatar_default.jpg',
            'action' => $this->status,
            'verifikasi' => array($this->verifikasi)
        ];
    }
}
