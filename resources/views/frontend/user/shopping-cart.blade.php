@extends('frontend.layouts.app')

@section('title', __("User's shopping cart"))

@section('content')
    <div class="p-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-frontend.card>
                    <x-slot name="header">
                        @lang("User's shopping cart")
                    </x-slot>

                    <x-slot name="body">
                        <livewire:frontend.shopping-cart-table />
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-10-->
        </div><!--row-->
    </div><!--container-->
@endsection
