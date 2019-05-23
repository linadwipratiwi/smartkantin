@extends('layouts.pdf')

@section('content')
    {{-- Header --}}
    <table class="table">
        <tr>
            <td style="width:150px">{{$submission->number}}</td>
            <td style="width:300px;text-align:center; font-weight:bold" rowspan="2">FORM PENGAJUAN PENGADAAN BARANG DEPOT LPG TANJUNG PERAK</td>
            <td style="width:150px" rowspan="2"><img src="{{ public_path() }}/dist/img/pertamina.png" alt="" style="height: 30px; width: auto;"></td>
        </tr>
        <tr>
            <td>{{App\Helpers\DateHelper::formatView($submission->created_at)}}</td>
        </tr>
    </table>

    {{-- Body --}}
    <br><br><br><br><br><br>
    <table class="table">
        <tr>
            <td style="width:150px">Pemohon</td>
            <td style="width:10px">:</td>
            <td>{{$submission->createdBy->name}}</td>
        </tr>
        <tr>
            <td style="width:150px">Fungsi</td>
            <td style="width:10px">:</td>
            <td>{{$submission->category->name}}</td>
        </tr>
        <tr>
            <td style="width:150px">Nama Barang</td>
            <td style="width:10px">:</td>
            <td>{{$submission->name}}</td>
        </tr>
        <tr>
            <td style="width:150px; vertical-align:top">Alasan</td>
            <td style="width:10px">:</td>
            <td>{{$submission->notes}}</td>
        </tr>
        <tr>
            <td style="width:150px; vertical-align:top">File Pendukung</td>
            <td style="width:10px">:</td>
            <td>
                @if(!$submission->files->count()) - @endif
                @foreach ($submission->files as $row => $file)
                    {{++$row}}. {{$file->name}} <br>
                @endforeach    
            </td>
        </tr>
    </table>

    {{-- Footer     --}}
    <br><br><br><br><br>
    <table>
        <tr>
            <td style="width:300px">Operation Head</td>
            <td style="width:300px; text-align:right">Spv. Engineering Planing Maintenance</td>
        </tr>
        <tr>
            <td style="width:300px; padding-top:60px">{{App\Helpers\ApprovalHelper::user($submission->approval_to_oh)}}</td>
            <td style="width:300px; padding-top:60px; text-align:right">{{App\Helpers\ApprovalHelper::user($submission->approval_to_spv_epm)}}</td>
        </tr>
    </table>

@endsection