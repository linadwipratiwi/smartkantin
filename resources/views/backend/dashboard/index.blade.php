@extends('layouts.app')
@section('content')
	@include('backend.dashboard._count')
	@include('backend.dashboard._filter')
	@include('backend.dashboard._client')

@stop

@section('scripts')
	
@stop