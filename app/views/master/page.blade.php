@extends('master.layout')

@section('body')

<div id="body-container">
    <div id="body-header">Lie to Win</div>
    <div id="body-content">
        @yield('content')
    </div>
    <div id="body-footer">Footer stuff</div>
</div>



@stop