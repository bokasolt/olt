@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@section('breadcrumb-links')
    @include('backend.includes.breadcrumb-links')
@endsection


@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Welcome :Name', ['name' => $logged_in_user->name])
        </x-slot>

        <x-slot name="headerActions">
                <x-utils.link
                    class="card-header-action"
                    :href="route('admin.trashed-domains')"
                    :text="__('Removed domains')"
                />
                <x-utils.link
                    icon="c-icon cil-sync"
                    class="card-header-action"
                    :href="route('admin.ahrefs.sync-all')"
                    :text="__('Update metrics')"
                />
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.domain.create')"
                    :text="__('Add Domain')"
                />
        </x-slot>

        <x-slot name="body">
            @lang('Dashboard')
            <livewire:backend.domains-table />
        </x-slot>
    </x-backend.card>
@endsection
