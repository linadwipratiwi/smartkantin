@extends('layouts.pdf')
@section('css')
    <style>
    * { font-size:12px}
    </style>
@endsection
@section('content')
    <table class="table">
        <tr>
            <td style="width:150px">{{strtoupper($item->name)}}</td>
            <td style="width:300px;text-align:center; font-weight:bold" rowspan="2">LAPORAN RIWAYAT ALAT</td>
            <td style="width:150px" rowspan="2"><img src="{{ public_path() }}/dist/img/pertamina.png" alt="" style="height: 30px; width: auto;"></td>
        </tr>
        <tr>
            <td>{{App\Helpers\DateHelper::formatView(Carbon\Carbon::now())}}</td>
        </tr>
    </table>
    <br><br>
    <table class="table">
        <tr>
            <td>No</td>
            <td>Checklist</td>
            <td>Tanggal</td>
            <td>Keterangan</td>
            <td>Pelaksana</td>
            <td>Dibuat Oleh</td>
            <td>Status Verifikasi</td>
        </tr>
        @foreach ($histories as $row => $history)
            <tr>
                <td>{{++$row}}</td>
                <td>{{$history->maintenanceActivity ? $history->maintenanceActivity->linkToRef() : '-' }} </td>
                <td>{{\App\Helpers\DateHelper::formatView($history->date)}}</td>
                <td>{{$history->notes}}</td>
                <td>{!! $history->executor() !!} </td>
                <td>{{ $history->user->name }} </td>
                <td>{{$history->approvalTo->name}} {!! $history->statusApproval() !!} </td>
                
            </tr>
        @endforeach
    </table>
@endsection