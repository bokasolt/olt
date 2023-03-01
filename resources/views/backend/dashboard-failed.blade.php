@extends('backend.layouts.app')

@section('title', __('Ahrefs Errors Report'))

@section('breadcrumb-links')
    @include('backend.includes.breadcrumb-links')
@endsection


@section('content')
    <x-backend.card>
        <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-sync"
                    class="card-header-action"
                    :href="route('admin.ahrefs.sync-all')"
                    :text="__('Update metrics')"
                />
        </x-slot>

        <x-slot name="body">
            <h4>@lang('Ahrefs Errors Report')</h4>
            <livewire:backend.domains-table type="failed" />
        </x-slot>
    </x-backend.card>
@endsection
