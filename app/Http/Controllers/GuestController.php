<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pengajuan;

class GuestController extends Controller
{
    //
    public function goPengajuan(){
        return view('frontend.guest.pengajuan');
    }
    public function formSubmit(Request $req)
    {
        // print_r($req->input());
        $permintaan= new Pengajuan;
        $permintaan->nama_sekolah= $req->input('Nama_Sekolah');
        $permintaan->nama_kepala_sekolah= $req->input('Nama_Kepala_Sekolah');
        $permintaan->alamat= $req->input('Alamat');
        $permintaan->alamat_email= $req->input('Alamat_Email');
        $permintaan->jumlah_peserta_didik= $req->input('Jumlah_Peserta_Didik');
        $permintaan->jumlah_pedagang_dikantin_sekolah= $req->input('Jumlah_Pedagang_di_Kantin_Sekolah');
        $permintaan->nama_koperasi= $req->input('Nama_Koperasi');
        $permintaan->rencana_jadwal_paparan= $req->input('Rencana_Jadwal_Paparan');
        $permintaan->nama_pengisi=$req->input('Nama');
        $permintaan->no_ponsel_wa_pengisi=$req->input('No');

        try{
            $permintaan->save();
            print_r("success");
        }
        catch(Throwable $th){
            print_r("failed");

        }        
    }
}
