@extends('layouts.pdf')

@section('content')
    <h3>Laporan Ke Pak Mahfud</h3>

    <table>
        <tr><td>sisa saldo</td> <td>:</td><td>{{format_price($sisa_saldo)}}</td></tr>
        <tr><td>sisa saldo pens</td><td>:</td><td>{{format_price($sisa_saldo_pens)}}</td></tr>
        <tr><td>total topup</td><td>:</td><td>{{format_price($total_topup)}}</td></tr>
        <tr><td>saldo kantin transaksi</td><td>:</td><td>{{format_price($saldo_kantin)}}</td></tr>
        <tr><td>total transaksi</td><td>:</td><td>{{format_price($total_transaksi)}}</td></tr>
        <tr><td>saldo pens transaksi</td><td>:</td><td>{{format_price($saldo_pens)}}</td></tr>
        <tr><td>total transaksi penjualan</td><td>:</td><td>{{format_price($total_transaksi_penjualan)}}</td></tr>
        <tr><td>profit kantin mesin</td><td>:</td><td>{{$profit_kantin_mesin}}</td></tr>
        <tr><td>untuk pak mahfud</td><td>:</td><td>{{format_price($pak_mahfud)}}</td></tr>
    </table>
@endsection