@extends('master.page')

@section('content')


@if(!Auth::check())
    <div class="main-button section">
        <!--        {{ link_to('play', 'Play', array('class' =>'button')) }}-->
        <a href="#" class="button causeLoginLb">Play</a>
    </div>

    <div id="loginFormContainer">
        <div id="loginForm" class="white_content">
            <div class="exit">X</div>
            <div class="content">
                <h3>Login</h3>

                {{ Form::open(array('url' => 'account/login', 'method' => 'POST')) }}

                <p>
                <span class="pure-u-1-4">
                    {{ Form::label('username', 'Username') }}
                </span>

                    {{ Form::text('username', Input::old('username')) }}

                </p>
                <p>
                <span class="pure-u-1-4">
                    {{ Form::label('password', 'Password') }}
                </span>
                    {{ Form::password('password') }}
                </p>
                <a href="#" class="button">Login</a>

                {{ Form::close() }}

            </div>
        </div>
        <div class="black_overlay"></div>
    </div>


@else

    <div class="main-button section">
        {{ link_to('play', 'Play', array('class' =>'button')) }}
    </div>

@endif
<div class="section">
    <div class="section-header">How to play</div>
    <div class="section-3-1 flipMe">
        Raise
    </div>
    <div class="section-3-1 flipMe delay-1">
        Spot on
    </div>
    <div class="section-3-1 flipMe delay-2">
        Lie
    </div>
</div>
@stop