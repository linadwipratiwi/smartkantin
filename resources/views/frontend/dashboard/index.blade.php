@extends('layouts.app')
@section('content')
	<div id="body-grafik-transaction">
		@include('frontend.dashboard._chart-morris')
	</div>
	@include('frontend.dashboard._count')
	@include('frontend.dashboard._grafik')

@stop

@section('scripts')
	
@stop