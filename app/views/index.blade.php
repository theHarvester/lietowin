@extends('master.page')

@section('content')


@if(!Auth::check())
    <div class="pure-u-1-2">
        <h3 class="information-head">Login</h3>

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
        <p>
            <span class="btn_submit">{{ Form::submit('Login') }}</span>
        </p>

        {{ Form::close() }}

    </div>
@else
    {{ link_to('play', 'Play') }}

@endif
@stop