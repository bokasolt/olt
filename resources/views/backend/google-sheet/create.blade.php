@extends('backend.layouts.app')

@section('title', __('Add Domain'))

@section('content')
    <x-forms.post :action="route('admin.google-sheet.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Add google sheet')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.google-sheet.index')" :text="__('Cancel')"/>
            </x-slot>

            <x-slot name="body">

                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

                    <div class="col-md-10">
                        <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Name') }}"
                               value="{{ old('name') }}" maxlength="255" required/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="url" class="col-md-2 col-form-label">@lang('Url')</label>

                    <div class="col-md-10">
                        <input type="text" name="url" id="url" class="form-control" placeholder="{{ __('Url') }}"
                               value="{{ old('url') }}" maxlength="255" required/>
                    </div>
                </div><!--form-group-->

            </x-slot>

            <x-slot name="footer">
                @include('backend.google-sheet.includes.associations')
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
