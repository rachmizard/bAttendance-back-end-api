<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Resources\AbsenHistoryResource;
use App\Http\Resources\ResepsionisHistoryResource;
use App\Http\Resources\MyHistoryResource;
use App\Http\Controllers\Controller;
use App\Karyawan;
use App\Jam;
use App\Absen;
use App\Lembur;
use App\Verifikasi;
use Auth;
use Carbon\Carbon;
use Validator;
use Input;
use App\Events\Absen as AbsenEvent;

class APIController extends Controller
{


    /**
     * Login users apps listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginApps(Request $request)
    {
        $nik = Karyawan::where('nik', '=', $request->input('nik'))->first();
        if(count($nik) > 0){
            if ($nik['status'] == 'unauthorized') {
                // Get this ID
                $karyawanID = null;
                $response['message'] = 'unauthorized';
                return response()->json(['response' => $response, 'karyawan' => $karyawanID ]);
            }else if($nik['status'] == 'authorized') {
                // Get this ID
                $updateToken = Karyawan::find($nik['id']);
                $updateToken->update(['device_token' => $request->input('device_token')]);
                $karyawanID = Karyawan::where('nik', $request->input('nik'))->first();
                $response['message'] = 'success';
                $response['token'] = $request->input('device_token');
                return response()->json(['response' => $response, 'karyawan' => $karyawanID ]);
            }else{
                // Get this ID
                $karyawanID = null;
                $response['message'] = 'vacant';
                return response()->json(['response' => $response, 'karyawan' => $karyawanID ]);
            }
        }else{
            $response['message'] = 'failed';
            return response()->json(['response' => $response, 'karyawan' => null ]);
        }
    }

    /**
     * Register a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function registerApps(Request $request)
    {
         $getNik = Karyawan::where('nik', $request->input('nik'))->first();
         if (count($getNik) > 0) {
             if ($getNik['status'] == 'unauthorized') {
                 $getID = Karyawan::find($getNik['id']);
                 $getID->status = 'authorized';
                 $getID->update();
                 $response['message'] = 'success';
                 return response()->json(['response' => $response, 'karyawan' => $getID]);
             }else{
                 $getID = Karyawan::find($getNik['id']);
                 $response['message'] = 'failed';
                 return response()->json(['response' => $response, 'karyawan' => $getID]);
             }
         }else{
            $response['message'] = 'failed';
            return response()->json(['response' => $response, 'karyawan' => null]);
         }
    }

    /**
     * Register a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function registerAppsPin(Request $request)
    {
         $getNik = Karyawan::where('nik', $request->input('nik'))->first();
         if (count($getNik) > 0) {
             $getID = Karyawan::find($getNik['id']);
             $getID->pin = $request->input('pin');
             $getID->update();
             return response()->json(['karyawan' => $getID]);
         }else{
             return response()->json(['karyawan' => null]);
         }
    }

    /**
     * Generate pin the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        // Generate random PIN
        $date = Carbon::now();
        $parse = Carbon::parse($date);
        $verifikasi = new Verifikasi;
        // Generate Pin
        $pin = 0;
        $i = '';
        while ($i < 3) {
            $pin .= mt_rand(0, 9);
            $i++;
        }
        if ($verifikasi) {
            $verifikasi->status = '0';
            $verifikasi->pin = substr($pin, -2).substr($parse->second, -1).substr($parse->minute, -1);
            $verifikasi->save();
        }

        return response()->json(['id' => (string)$verifikasi->id, 'pin' => $verifikasi->pin]);
    }

    /**
     * Started absen resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function masuk(Request $request)
    {
        $getDate = Carbon::now();
        // Get date now
        $validator = Carbon::today()->format('Y-m-d');
        // Check if data is already exist for presence today
        $check = Absen::where('karyawan_id', $request->input('karyawan_id'))->where('status', 'masuk')->whereDate('created_at', $validator)->get();
        if (!count($check) > 0) {
            $masuk = new Absen();
            $masuk->karyawan_id = $request->input('karyawan_id');
            $masuk->verifikasi_id = $request->input('verifikasi_id');
            $masuk->status = 'masuk';
            $masuk->alasan = null;
            $masuk->save();
            return response()->json(['message' => 'success', 'id' => $masuk->id ]);
        }else{
            return response()->json(['message' => 'failed', 'text' => 'Silahkan coba lagi!']);
        }
    }

    /**
     * Started keluar presence resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function keluar(Request $request)
    {
        $getDate = Carbon::now();
        // Get date now
        $validator = Carbon::today()->format('Y-m-d');
        // Check if data is already exist for out today
        $check = Absen::where('karyawan_id', $request->input('karyawan_id'))->where('status', 'keluar')->whereDate('created_at', $validator)->get();
        if (!count($check) > 0) {
            // Generate random PIN
            $date = Carbon::now();
            $parse = Carbon::parse($date);
            $verifikasi = new Verifikasi;
            // Generate Pin
            $pin = 0;
            $i = '';
            while ($i < 3) {
                $pin .= mt_rand(0, 9);
                $i++;
            }
            if ($verifikasi) {
                $verifikasi->status = '2';
                $verifikasi->pin = substr($pin, -2).substr($parse->second, -1).substr($parse->minute, -1);
                $verifikasi->save();
            }
            $keluar = new Absen();
            $keluar->karyawan_id = $request->input('karyawan_id');
            $keluar->verifikasi_id = $verifikasi->id;
            $keluar->status = 'keluar';
            $keluar->alasan = $request->input('alasan');
            $keluar->save();
            return response()->json(['message' => 'success', 'id' => $keluar->id]);
        }else{
            return response()->json(['message' => 'failed']);
        }
    }

     /**
     * Started mabal presence resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mabal(Request $request)
    {
        $getDate = Carbon::now();
        // Get date now
        $validator = Carbon::today()->format('Y-m-d');
        // Check if data is already exist for out today
        $check = Absen::where('karyawan_id', $request->input('karyawan_id'))->where('status', 'keluar')->whereDate('created_at', $validator)->get();
        if (!count($check) > 0) {
            // Generate random PIN
            $date = Carbon::now();
            $parse = Carbon::parse($date);
            $verifikasi = new Verifikasi;
            // Generate Pin
            $pin = 0;
            $i = '';
            while ($i < 3) {
                $pin .= mt_rand(0, 9);
                $i++;
            }
            if ($verifikasi) {
                $verifikasi->status = '2';
                $verifikasi->pin = substr($pin, -2).substr($parse->second, -1).substr($parse->minute, -1);
                $verifikasi->save();
            }
            $keluar = new Absen();
            $keluar->karyawan_id = $request->input('karyawan_id');
            $keluar->verifikasi_id = $verifikasi->id;
            $keluar->status = 'keluar';
            $keluar->alasan = $request->input('alasan');
            $keluar->save();
            return response()->json(['message' => 'success', 'id' => $keluar->id]);
        }else{
            return response()->json(['message' => 'failed']);
        }
    }

    public function editAbsen(Request $request, $id)
    {
        // Getting master Jam
        $jam_masuk = Jam::where('status', 1)->first();
        $absen = Absen::find($id);
        $absen->verifikasi_id = $request->input('verifikasi_id');
        $absen->update();
        $token = $absen->karyawan->device_token;
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $checkInTime = Carbon::parse($absen->verifikasi->updated_at)->timezone('Asia/Jakarta');

        // return response()->json(['message' => 'success', 'id' => $absen->karyawan->id]);
        if (Carbon::parse($checkInTime)->format('H:i:s') < Carbon::parse($jam_masuk['start'])->format('H:i:s')) {
            $notification = [
                'title' => 'Selamat datang '. $absen->karyawan->nama,
                'body' => 'WOW Anda semangat sekali, dengan hadir lebih awal. Selamat bekerja 😀😬',
                'priority' => 'high',
                'sound' => true,
            ];

            // Trigger Push Notif
            $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
            $fcmNotification = [
                // 'registration_ids' => $token, //multple token array
                'to'        => $token, //single token
                'notification' => $notification,
                'data' => $extraNotificationData
            ];
            $headers = [
                'Authorization: key=AIzaSyApVpA0N2kN6WpFS6vytCCTPCj4L3xBefg',
                'Content-Type: application/json'
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
            $result = curl_exec($ch);
            curl_close($ch);
            return response()->json(['message' => 'success', 'id' => $absen->id, 'text' => 'WOW Anda semangat sekali, dengan hadir lebih awal. Selamat bekerja 😀😬']);
        }else if(Carbon::parse($checkInTime)->format('H:i:s') > Carbon::parse($jam_masuk['start'])->format('H:i:s')){
            if (Carbon::parse($checkInTime)->format('H:i:s') < Carbon::parse($jam_masuk['tolerance'])->format('H:i:s')) {
                $notification = [
                    'title' => 'Selamat datang '. $absen->karyawan->nama,
                    'body' => 'Terimakasih sudah hadir tepat waktu. Selamat bekerja 😀😬',
                    'priority' => 'high',
                    'sound' => true,
                ];

                // Trigger Push Notif
                $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
                $fcmNotification = [
                    // 'registration_ids' => $token, //multple token array
                    'to'        => $token, //single token
                    'notification' => $notification,
                    'data' => $extraNotificationData
                ];
                $headers = [
                    'Authorization: key=AIzaSyApVpA0N2kN6WpFS6vytCCTPCj4L3xBefg',
                    'Content-Type: application/json'
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$fcmUrl);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                $result = curl_exec($ch);
                curl_close($ch);
                return response()->json(['message' => 'success', 'id' => $absen->karyawan->id, 'text' => 'Terimakasih sudah hadir tepat waktu. Selamat bekerja 😀😬']);
            }else{
                $notification = [
                    'title' => 'Selamat datang '. $absen->karyawan->nama,
                    'body' => 'Hati-hati, malas adalah awal dari kegagalan. Segera perbaiki di hari esok,. Selamat bekerja 😀😬',
                    'priority' => 'high',
                    'sound' => true,
                ];
                // Trigger Push Notif
                $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
                $fcmNotification = [
                    // 'registration_ids' => $token, //multple token array
                    'to'        => $token, //single token
                    'notification' => $notification,
                    'data' => $extraNotificationData
                ];
                $headers = [
                    'Authorization: key=AIzaSyApVpA0N2kN6WpFS6vytCCTPCj4L3xBefg',
                    'Content-Type: application/json'
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$fcmUrl);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                $result = curl_exec($ch);
                curl_close($ch);
                return response()->json(['message' => 'request', 'id' => $absen->karyawan->id, 'text' => 'Hati-hati, malas adalah awal dari kegagalan. Segera perbaiki di hari esok,. Selamat bekerja 😀😬']);
            }
        }

    }

    public function deleteAbsen($id)
    {
        $absen = Absen::find($id);
        $absen->delete();
        return response()->json(['message' => 'success', 'id' => $absen->id]);
    }

    /**
     * Started lembur presence resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lembur(Request $request)
    {
        $getDate = Carbon::now();
        // Get date now
        $validator = Carbon::today()->format('Y-m-d');
        // Check if data is already exist for overtime today
        $check = Lembur::where('karyawan_id', $request->input('karyawan_id'))->whereDate('created_at', $validator)->get();
        if (!count($check) > 0) {
            $lembur = new Lembur();
            $lembur->karyawan_id = $request->input('karyawan_id');
            $lembur->durasi = $request->input('durasi');
            $lembur->alasan = $request->input('alasan');
            $lembur->save();
            return response()->json(['message' => 'success', 'id' => $lembur->id]);
        }else{
            return response()->json(['message' => 'failed']);
        }
    }

    /**
     * Change status verification presence resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        $verifikasi = Verifikasi::find($id);
        $verifikasi->status = $request->input('status');
        $verifikasi->update();

        $title = 'Absen Notifikasi';
        $message =  'Seseorang melakukan absen masuk hari ini '. Carbon::now()->format('d-m-Y H:i:s');
        $type = 'success';
        $image = 'foto.jpg';
        event(new AbsenEvent($title, $message, $type, $image));
        return response()->json(['message' => 'success', 'id' => $verifikasi->id]);
    }

    /**
     * Detail status verification presence resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showVerifikasi($id)
    {
        $verification = Verifikasi::findOrFail($id);
        return response()->json(['id' => (string)$verification->id, 'pin' => $verification->pin, 'status' => $verification->status]);
    }

    /**
     * Fetching data presence resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request)
    {
        return ResepsionisHistoryResource::collection(Absen::with('verifikasi')->orderBy('created_at', 'DESC')->where('status', 'masuk')->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get());
    }

    /**
     * Fetching data by users id presence resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myHistory(Request $request, $id)
    {
        return MyHistoryResource::collection(Absen::where('karyawan_id', $id)->get());
    }

    /**
     * Forgot users's pin resource
     *
     * @return \Illuminate\Http\Response
     */
    // public function forgotPin()
    // {
    //     $getNik = Karyawan::where('nik', $request->input('nik'))->first();
    //     $newpin = Karyawan::findOrFail($getNik['id']);
    //     $newpin->pin = $request->input('pin');
    //     if ($newpin->update()) {
    //         $response['status'] = 'success';
    //         $response['pin'] = $request->input('pin');
    //     }
    //     return response()->json(['karyawan' => $response]);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postKaryawan(Request $request)
    {
        // $this->validate($request, [
        //     'nama'  => 'required|string|max:50',
        //     'jenis_kelamin'  => 'required|string|max:50',
        //     'nik'  => 'required|numeric|max:50',
        //     'divisi' => 'required|string|max:50',
        // ]);

        $karyawan = new Karyawan;
        $karyawan->nama = $request->input('nama');
        $karyawan->divisi = $request->input('divisi');
        $karyawan->jenis_kelamin = $request->input('jenis_kelamin');
        $karyawan->nik = $request->input('nik');
        $karyawan->save();

        return response()->json($karyawan);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $getNik = Karyawan::where('nik', $request->input('nik'))->first();
        if (count($getNik) > 0) {
            $response['message'] = 'exist';
            return response()->json(['response' => $response, 'karyawan' => $getNik]);
        }else{
            $response['message'] = 'empty';
            return response()->json(['response' => $response, 'karyawan' => null]);
        }
    }

    public function pulang()
    {
        $getStatus = Jam::where('status', 1)->first();
        if (Carbon::now()->format('H:i:s') > Carbon::parse($getStatus['end'])->format('H:i:s')) {
            return response()->json(['message' => true]);
        }else{
            return response()->json(['message' => false]);
        }
    }

    public function telat(Request $request, $id)
    {
        $absen_telat = Absen::find($id);
        $absen_telat->alasan = $request->input('alasan');
        if ($absen_telat->save()) {
            return response()->json(['message' => 'success', 'text' => 'Alasan mu alasan classic!']);
        }else{
            return response()->json(['message' => 'failed', 'text' => 'Gagal menginput Alasan!']);
        }
    }

    public function over()
    {
        $jam = Jam::where('status', 1)->first();
        if (Carbon::now()->isWeekend()) {
            return response()->json(['message' => true, 'text' => 'Ya ini hari libur boleh lembur']);
        }else{
            if (Carbon::now()->format('H:i:s') > Carbon::parse($jam['end'])->format('H:i:s')) {
                return response()->json(['message' => true, 'text' => 'Ya kamu sudah lebih dari jam '. $jam['end'] .' boleh lembur']);       
            }else{
                return response()->json(['message' => false, 'text' => 'Belum boleh lembur']);
            }
        }
    }

    public function lapur($id)
    {
        $lapur = Verifikasi::find($id);
        $lapur->delete();
        return response()->json(['message' => 'success']);
    }

}
