@extends('layouts.layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/pagination.css') }}" >
@stop
@section('javascript')
    <script src="{{ asset('/js/pagination.js') }}"></script>
@stop
@section('body')
    <div id="pagination">
        @include('pagination.search')
    </div>
@stop
