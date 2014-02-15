@extends('master.page')

@section('content')
<div class="pure-u-1-2">
    <h3 class="information-head">Login</h3>

    {{ Form::open(array('url' => 'account/login', 'method' => 'POST')) }}

    <p>
    <span class="pure-u-1-4">
        {{ Form::label('username', 'Email') }}
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




<div class="pure-u-1-2">
    <h3 class="information-head">Signup</h3>
    <p>
        {{ Form::open(array('url' => 'account/signup', 'method' => 'POST')) }}

        <!-- username field -->
    <p>
        <span class="pure-u-1-4">
            {{ Form::label('username', 'Username') }}
        </span>
        {{ Form::text('username', Input::old('username')) }}
    </p>

    <!-- email field -->
    <p>
        <span class="pure-u-1-4">
            {{ Form::label('email', 'Email') }}
        </span>
        {{ Form::text('email', Input::old('email')) }}
    </p>

    <!-- password field -->
    <p>
        <span class="pure-u-1-4">
            {{ Form::label('password', 'Password') }}
        </span>
        {{ Form::password('password') }}
    </p>

    <!-- submit button -->
    <p class="btn_submit">{{ Form::submit('Create Account') }}</p>

    {{ Form::close() }}
    </p>
</div>





@stop