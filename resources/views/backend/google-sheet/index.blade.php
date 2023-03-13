@extends('backend.layouts.app')

@section('title', __('Google sheets'))

@section('breadcrumb-links')
    @include('backend.includes.breadcrumb-links')
@endsection

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Google sheets')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.google-sheet.create')"
                    :text="__('Add google sheet')"
            />
        </x-slot>

        <x-slot name="body">
            @lang('Google sheets')
            <livewire:backend.google-sheets-table />
        </x-slot>
    </x-backend.card>
@endsection
