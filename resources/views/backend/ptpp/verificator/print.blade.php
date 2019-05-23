@extends('layouts.pdf')

@section('content')
<style>
.fs-12 { font-size: 12px; background: #eee; color: black;}
table { font-size: 12px;}
</style>
    {{-- Header --}}
    <table class="table">
        <tr>
            <td style="width:190px">{{$ptpp->number}}</td>
            <td style="width:300px;text-align:center; font-weight:bold; font-size:14px" rowspan="2" colspan="2">PERMINTAAN TINDAKAN PERBAIKAN DAN PENCEGAHAN (PTPP)</td>
            <td style="width:190px" rowspan="2"><img src="{{ public_path() }}/dist/img/pertamina.png" alt="" style="height: 30px; width: auto;"></td>
        </tr>
        <tr>
            <td>{{App\Helpers\DateHelper::formatView($ptpp->date_created)}}</td>
        </tr>
    </table>

    {{-- Body --}}
    <table class="table">
        <tr>
            <td style="width:80px;">Kepada Fungsi</td>
            <td style="width:10px;">:</td>
            <td style="width:310px;"> {{$ptpp->to_function}}</td>
            <td rowspan="2" style="width:193px;">
                <span><small><u>Area/Lokasi Temuan</u></small></span> <br>
                {{$ptpp->location}}
            </td>
        </tr>
        <tr>
            <td style="width:150px">Dari</td>
            <td style="width:10px">:</td>
            <td>{{$ptpp->from}}</td>
        </tr>
        <tr>
            <td colspan="4" class="fs-12"><i>Di isi oleh pemohon / auditor</i></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center;" ><b>SUMBER KETIDAKSESUAIAN ATAU POTENSINYA</b></td>
        </tr>
        <tr>
            <td colspan="4">
                <table style="">
                    <tr>
                        @foreach ($categories->take(3)->get() as $category)
                        <td style="border:none !important;">
                            @if($ptpp->category_id == $category->id)
                            <img src="{{ public_path() }}/dist/img/checked.png" alt="" style="height: 15px; width: auto;">
                            @else
                            <img src="{{ public_path() }}/dist/img/check.png" alt="" style="height: 15px; width: auto;">
                            @endif
                            {{$category->name}}
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($categories->take(3)->offset(3)->get() as $category)
                        <td style="border:none !important;">
                            @if($ptpp->category_id == $category->id)
                            <img src="{{ public_path() }}/dist/img/checked.png" alt="" style="height: 15px; width: auto;">
                            @else
                            <img src="{{ public_path() }}/dist/img/check.png" alt="" style="height: 15px; width: auto;"> 
                            @endif

                            {{$category->name}}
                        </td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center;"><b>KETIDAKSESUAIAN ATAU POTENSI YANG DITEMUKAN</b> (<font style="font-size:12px">bisa dilampiri dengan gambar/foto</font>)</td>
        </tr>
        <tr>
            <td colspan="4" style="border-bottom:none !important">
                <table>
                    <tr>
                        <td style="min-width:200px; border:none !important; border-right: 1px solid #eee !important">
                            {{$ptpp->notes}}
                        </td>
                        <td style=" border:none !important;">
                            {{-- file --}}
                            File Pendukung Yang Dilampirkan
                            <table>
                                @foreach ($ptpp->files as $row => $item)
                                <tr>
                                    <td style="border:none !important; ">{{++$row}}. {{$item->name}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="4" style="border-top:none !important;vertical-align:bottom; padding-left:50%">
                <table style="font-size:12px;">
                    <tr>
                        <td style="width:30%;text-align:center;">Pemohon/Auditor</td>
                        <td style="width:35%;text-align:center;">Sr. Spv. RSD</td>
                        <td style="width:35%;text-align:center;">OH</td>
                    </tr>
                    <tr>
                        <td style="height:50px; vertical-align:bottom; text-align:center; font-weight:bold">{{user_approval($ptpp->created_by)}}</td>
                        <td style="height:50px; vertical-align:bottom; text-align:center; font-weight:bold">{{user_approval($ptpp->approval_to_spv_rsd)}}</td>
                        <td style="height:50px; vertical-align:bottom; text-align:center; font-weight:bold">{{user_approval($ptpp->approval_to_oh)}}</td>
                    </tr>
                
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="fs-12"><i>Di isi oleh penerima PTPP</i></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center;"><b>TINDAK LANJUT</b></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center;">
                <table>
                    <tr>
                        <td style="vertical-align:top;"><b>PERBAIKAN / TINDAKAN SEGERA (Jika ada):</b></td>
                        <td style="text-align:center">Tanggal</td>
                        <td colspan="2" style="text-align:center">Pelaksanaan Perbaikan</td>
                    </tr>
                    <tr>
                        <td rowspan="2" style="vertical-align:top">{{$ptpp->followUp->notes}}</td>
                        <td rowspan="2">{{App\Helpers\DateHelper::formatView($ptpp->followUp->date)}}</td>
                        <td style="text-align:center;">Pelaksana Perbaikan</td>
                        <td style="text-align:center;">Disetujui oleh,</td>
                    </tr>
                    <tr>
                        <td style="height:50px; vertical-align:bottom; text-align:center; ">{{user_approval($ptpp->followUp->created_by)}}</td>
                        <td style="height:50px; vertical-align:bottom; text-align:center; ">{{user_approval($ptpp->followUp->approval_to_spv_epm)}}</td>
                    </tr>
                </table>

                {{-- penyebab masalah --}}
                <table>
                    <tr>
                        <td style="font-weight:bold">No</td>
                        <td style="font-weight:bold">Penyebab Masalah</td>
                        <td style="font-weight:bold">Tindakan Perbaikan / Pencegahan</td>
                        <td style="font-weight:bold">PIC</td>
                        <td style="font-weight:bold">Tanggal Selesai</td>
                    </tr>
                    @foreach ($ptpp->followUp->details as $row => $detail)
                    <tr>
                        <td>{{++$row}}</td>
                        <td>{{$detail->problem}}</td>
                        <td>{{$detail->prevention}}</td>
                        <td>{{$detail->pic}}</td>
                        <td>{{App\Helpers\DateHelper::formatView($detail->date_finish)}}</td>
                    </tr>
                    @endforeach
                </table>
                <div style="text-align:left; margin-top:10px">
                    <b>Dokumen yang direvisi (jika ada):</b>
                </div>
                <table>
                    <tr>
                        <td style="font-weight:bold">No</td>
                        <td style="font-weight:bold">Nama Dokumen</td>
                    </tr>
                    @foreach ($ptpp->followUp->files as $row => $file)
                    <tr>
                        <td style="width:10px">{{++$row}}</td>
                        <td>{{$file->name}}</td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="fs-12"><i>Di isi oleh pemohon / auditor</i></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center;"><b>VERIFIKASI PELAKSANAAN TINDAKAN PERBAIKAN DAN PENCEGAHAN</b></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center;">
                <table>
                    <tr>
                        <td style="vertical-align:top; width:500px; border: none !important" rowspan="2">
                            Status: <br>
                            <?php
                            $closed = public_path('dist/img/checked.png');
                            $open = public_path('dist/img/check.png');?>
                            <table style="border: none !important">
                                <tr>
                                    <td style="border: none !important"><img src="{{ $ptpp->verificator->status == 1 ? $closed : $open}}" alt="" style="height: 15px; width: auto;"> Closed ATAU</td>
                                    <td style="border: none !important">Tanggal</td>
                                    <td style="border: none !important; width:10px">:</td>
                                    <td style="border: none !important">{{App\Helpers\DateHelper::formatView($ptpp->verificator->created_at)}}</td>
                                </tr>
                                <tr>
                                    <td style="border: none !important"><img src="{{ $ptpp->verificator->status == 0 ? $closed : $open}}" alt="" style="height: 15px; width: auto;"> Perlu follow up</td>
                                    <td style="border: none !important">No. PTPP Baru</td>
                                    <td style="border: none !important; width:10px">:</td>
                                    <td style="border: none !important">{{$ptpp->verificator->no_ptpp_new}}</td>
                                </tr>
                            </table>
                            <br>
                            Catatan: <br>
                            <i>Jika tindakan perbaikan/pencegahan belum CLOSED, maka terbitkan PTPP Baru</i>
                        </td>
                        <td style="text-align:center">Verifikasi oleh OH</td>
                    </tr>
                    <tr>
                        <td style="vertical-align:bottom; text-align:center">{{user_approval($ptpp->verificator->approval_to_oh)}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

@endsection