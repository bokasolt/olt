@extends('backend.layouts.app')

@section('title', __('Orders'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Orders')
        </x-slot>
        <x-slot name="body">
            <livewire:backend.orders-table />
        </x-slot>
    </x-backend.card>
@endsection
