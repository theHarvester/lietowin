@extends('master.layout')

@section('body')

<div id="body-container">
    <div id="body-header">
        <div id="header-text">
            Lie to Win
        </div>
    </div>
    <div id="body-content">
        <div class="content-container">
            @yield('content')
        </div>
    </div>
    <div id="body-footer">
        <div class="content-container">
            Footer stuff
        </div>
    </div>
</div>



@stop