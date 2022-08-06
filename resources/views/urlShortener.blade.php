@extends('layouts.app')

@section('styles')


@endsection

@section('content')
    <div id="app">
        <url-shortener authorized-user="{{ auth()->user()->id ?? null }}"></url-shortener>
    </div>
@endsection

