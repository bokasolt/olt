@extends('backend.layouts.app')

@section('title', __('Removed domains'))

@section('breadcrumb-links')
    @include('backend.includes.breadcrumb-links')
@endsection


@section('content')
    <x-backend.card>
        <x-slot name="body">
            <h4>@lang('Removed domains')</h4>
            <livewire:backend.domains-table type="trashed" />
        </x-slot>
    </x-backend.card>
@endsection
