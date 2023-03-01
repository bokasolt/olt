@extends('backend.layouts.app')

@section('title', __('Settings'))

@section('content')
    <x-forms.patch :action="route('admin.settings.update')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Settings')
            </x-slot>

            <x-slot name="body">
                @foreach($options as $option)
                <div class="form-group row">
                    <label for="option{{ $option->id }}"
                           class="col-md-2 col-form-label">{{ $option->option }}</label>

                    <div class="col-md-10">
                        <input type="text" name="options[{{ $option->id }}]" id="option{{ $option->id }}"
                               class="form-control"
                               value="{{ old('option'.$option->id ) ?? $option->value }}" maxlength="255"
                               required/>
                    </div>
                </div><!--form-group-->
                @endforeach
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Settings')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection
