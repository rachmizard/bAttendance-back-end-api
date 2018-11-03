<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Absen;
use App\MasterRekap;
use App\MasterFilter;
use Carbon\Carbon;

class RekapDetailResource extends Resource
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
            'tanggal' => $this->tanggal(),
            'hadir' => $this->hadir(),
            'sakit' => $this->sakit(),
            'izin' => $this->izin(),
            'alfa'  => $this->alfa(),
            'dinas' => $this->dinas(),
            'keterangan' => $this->keterangan()
        ];
    }


    public function filterHistory()
    {
        return MasterRekap::findOrFail(1)->value('tanggal_aktif_rekap');
    }

    public function tanggal()
    {
        // return Carbon::parse(Absen::where('karyawan_id', $this->id)->whereDate('created_at', Carbon::parse(MasterRekap::find(1)->tanggal_aktif_rekap)->format('Y-m-d'))->value('created_at'))->format('d');
        return Carbon::parse($this->created_at)->format('d M Y');
    }

    public function hadir()
    {
        return Absen::where(['karyawan_id' => $this->karyawan->id, 'status' => 'masuk'])
            ->whereDate('created_at', Carbon::parse($this->created_at)->format('Y-m-d'))->value('status');
    }

    public function sakit()
    {
        return Absen::where(['karyawan_id' => $this->karyawan->id, 'status' => 'sakit'])
            ->whereDate('created_at', Carbon::parse($this->created_at)->format('Y-m-d'))->value('status');
    }

    public function izin()
    {
        return Absen::where(['karyawan_id' => $this->karyawan->id, 'status' => 'izin'])
            ->whereDate('created_at', Carbon::parse($this->created_at)->format('Y-m-d'))->value('status');
    }

    public function alfa()
    {
        return Absen::where(['karyawan_id' => $this->karyawan->id, 'status' => 'alfa'])
            ->whereDate('created_at', Carbon::parse($this->created_at)->format('Y-m-d'))->value('status');
    }

    public function dinas()
    {
        return Absen::where(['karyawan_id' => $this->karyawan->id, 'status' => 'dinas'])
            ->whereDate('created_at', Carbon::parse($this->created_at)->format('Y-m-d'))->value('status');
    }

    public function keterangan()
    {
        return Absen::where(['karyawan_id' => $this->karyawan->id])
            ->whereDate('created_at', Carbon::parse($this->created_at)->format('Y-m-d'))->value('alasan');
    }
}
