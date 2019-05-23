@extends('layouts.app')
@section('content')
	@include('backend.dashboard._count')
	{{-- @include('backend.dashboard._list-reminder-checklist') --}}
	@include('backend.dashboard._list-checklist')
	@include('backend.dashboard._list-history')
	@include('backend.dashboard._list-submission')

	{{-- PTPP --}}
	@if(auth()->user()->id == setting('spv_oh'))
		@include('backend.dashboard.ptpp._list-form-pending', ['list_ptpp' => $list_ptpp_form_pending_oh])
		@include('backend.dashboard.ptpp._list-verificator-pending-oh')
	@endif

	@if(auth()->user()->id == setting('spv_rsd'))
		@include('backend.dashboard.ptpp._list-form-pending', ['list_ptpp' => $list_ptpp_form_pending_rsd])
	@endif

	@if(auth()->user()->id == setting('spv_epm'))
		@include('backend.dashboard.ptpp._list-follow-up-pending-epm')
	@endif

@stop

@section('scripts')
	
@stop