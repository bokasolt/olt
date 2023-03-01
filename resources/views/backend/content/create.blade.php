@extends('backend.layouts.app')

@section('title', __('Create Content'))

@section('content')
    <x-forms.post :action="route('admin.content.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Content')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.content')" :text="__('Cancel')"/>
            </x-slot>

            <x-slot name="body">
                <div class="form-group row">
                    <label for="path" class="col-md-2 col-form-label">@lang('Path')</label>

                    <div class="col-md-10">
                        <input type="text" name="path" id="path" class="form-control" placeholder="{{ __('Path') }}"
                               value="{{ old('path') }}" maxlength="255"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="title" class="col-md-2 col-form-label">@lang('Title')</label>

                    <div class="col-md-10">
                        <input type="text" name="title" id="title" class="form-control" placeholder="{{ __('Title') }}"
                               value="{{ old('title') }}" maxlength="255"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="menu_order" class="col-md-2 col-form-label">@lang('Menu')</label>

                    <div class="col-md-10">
                        <input type="text" name="menu_order" id="menu_order" class="form-control" placeholder="{{ __('Menu order') }}"
                               value="{{ old('menu_order') }}" maxlength="255"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="additional_notes" class="col-md-2 col-form-label">@lang('Body')</label>

                    <div class="col-md-10">
                        <textarea name="body" id="body" class="form-control"
                               placeholder="{{ __('Body') }}"
                        >{{ old('body') }}</textarea>
                    </div>
                </div>

            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Content')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection

@push('after-scripts')
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace( 'body', {
    filebrowserUploadUrl: "{{route('admin.upload', ['_token' => csrf_token() ])}}",
    filebrowserUploadMethod: 'form'
});
</script>
@endpush