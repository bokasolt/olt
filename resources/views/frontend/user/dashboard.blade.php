@extends('frontend.layouts.app')

@section('title', __('Dashboard'))

@section('content')
	@include('frontend.includes.purchase-dialog')

    <div class="p-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-frontend.card>
                    <x-slot name="header">
                        @lang('Dashboard.') @lang('Total domains:') <span>{{ $total }}</span>.
                    </x-slot>
                    <x-slot name="headerActions">
                    @if ($logged_in_user->isPaid())
                        <livewire:frontend.balance />
                        <x-utils.link
                            :href="route('frontend.user.shopping-cart')"
                            :active="activeClass(Route::is('frontend.user.shopping-cart'))"
                            :text="'User\'s shopping cart'"
                            />
                    @endif
                    </x-slot>
                    <x-slot name="body">
                        <livewire:frontend.domains-table />
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-10-->
        </div><!--row-->
    </div><!--container-->
@endsection
