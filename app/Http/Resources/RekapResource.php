<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Absen;
use App\MasterRekap;
use App\Lembur;
use App\Jam;
use Carbon\Carbon;

class RekapResource extends Resource
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
          'nik' => $this->nik,
          'karyawan' => $this->nama,
          'jml_hadir' => $this->countHadir() == 0 ? '0' : $this->countHadir(),
          'jml_izin' => $this->countIzin() == 0 ? '0' : $this->countIzin(),
          'jml_sakit' => $this->countSakit() == 0 ? '0' : $this->countSakit(),
          'jml_alfa' => $this->countAlfa() == 0 ? '0' : $this->countAlfa(),
          'total_lembur' => $this->countLemburDuration() == 0 ? '<span class="label label-warning">Belum Lembur</span>' : $this->countLemburDuration(). ' Jam',
          'total_telat' => $this->countTelat() . ' kali'
        ];
    }

    public function countHadir()
    {
      return Absen::where(['karyawan_id' => $this->id, 'status' => 'keluar'])
            ->whereBetween('created_at', [new Carbon(MasterRekap::find(1)->start), new Carbon(MasterRekap::find(1)->end)])
            ->count();
    }

    public function countIzin()
    {
      return Absen::where(['karyawan_id' => $this->id, 'status' => 'izin'])
            ->whereBetween('created_at', [new Carbon(MasterRekap::find(1)->start), new Carbon(MasterRekap::find(1)->end)])
            ->count();
    }

    public function countSakit()
    {
      return Absen::where(['karyawan_id' => $this->id, 'status' => 'sakit'])
            ->whereBetween('created_at', [new Carbon(MasterRekap::find(1)->start), new Carbon(MasterRekap::find(1)->end)])
            ->count();
    }

    public function countAlfa()
    {
      return Absen::where(['karyawan_id' => $this->id, 'status' => 'alfa'])
            ->whereBetween('created_at', [new Carbon(MasterRekap::find(1)->start), new Carbon(MasterRekap::find(1)->end)])
            ->count();
    }

    public function countLemburDuration()
    {
      return Lembur::where(['karyawan_id' => $this->id])
            ->whereBetween('created_at', [new Carbon(MasterRekap::find(1)->start), new Carbon(MasterRekap::find(1)->end)])
            ->sum('durasi');
    }

    public function countTelat()
    {
      $find_jam = Jam::where('status', 1)->first();
      $total_jam_telat = Absen::where(['karyawan_id' => $this->id, 'status' => 'masuk'])
      ->whereDate('created_at', '>', Carbon::parse($find_jam['tolerance'])->format('H:i:s'))
      ->whereBetween('created_at', [new Carbon(MasterRekap::find(1)->start), new Carbon(MasterRekap::find(1)->end)])->count('created_at');
      return $total_jam_telat;
    }


}
