@extends('layouts.pdf')

@section('content')
    <?php

        $date = explode('-', \Input::get('date'));
        $date_start = \App\Helpers\DateHelper::formatDB(trim($date[0]), 'start');
        $date_end = \App\Helpers\DateHelper::formatDB(trim($date[1]), 'end');
    ?>
    <h3>Laporan Ke Pak Mahfud Tgl. {{$date_start}} s.d {{$date_end}}</h3>

    <table>
        <tr><td>sisa saldo</td> <td>:</td><td>{{$sisa_saldo}}</td></tr>
        <tr><td>sisa saldo pens</td><td>:</td><td>{{$sisa_saldo_pens}}</td></tr>
        <tr><td>total topup</td><td>:</td><td>{{$total_topup}}</td></tr>
        <tr><td>saldo kantin transaksi</td><td>:</td><td>{{$saldo_kantin}}</td></tr>
        <tr><td>total transaksi</td><td>:</td><td>{{$total_transaksi}}</td></tr>
        <tr><td>saldo pens transaksi</td><td>:</td><td>{{$saldo_pens}}</td></tr>
        <tr><td>total transaksi penjualan</td><td>:</td><td>{{$total_transaksi_penjualan}}</td></tr>
        <tr><td>profit kantin mesin</td><td>:</td><td>{{$profit_kantin_mesin}}</td></tr>
        <tr><td>untuk pak mahfud</td><td>:</td><td>{{$pak_mahfud}}</td></tr>
    </table>
@endsection