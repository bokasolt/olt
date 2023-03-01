@extends('backend.layouts.app')

@section('title', __('Content'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Content')
        </x-slot>

        <x-slot name="headerActions">
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.content.create')"
                    :text="__('Add New Content')"
                />
        </x-slot>

        <x-slot name="body">
            <livewire:backend.contents-table />
        </x-slot>
    </x-backend.card>
@endsection
