@extends('frontend.layouts.app')

@section('title', $content->title)

@section('content')
    <div class="container my-4">
        <h1 class="display-4 text-center m-4">{!! $content->title !!}</h1>
        {!! $content->body !!}
    </div>
@endsection
