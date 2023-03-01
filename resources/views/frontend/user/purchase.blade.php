@extends('frontend.layouts.app')

@section('title', __('Purchase'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <x-frontend.card>
                    <x-slot name="header">
                        @lang('Purchase')
                    </x-slot>

                    <x-slot name="body">
                    <livewire:frontend.purchase />
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-10-->
        </div><!--row-->
    </div><!--container-->
@endsection

@push('after-scripts')
    <script src="{{ mix('js/2checkout.js') }}"></script>
@endpush
