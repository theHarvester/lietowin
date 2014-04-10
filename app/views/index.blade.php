@extends('master.page')

@section('content')
<div id="top-menu">
    <div class="side-popout-btn">
        <div class="icon-svg">
            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>arrow</title>
                    <polygon stroke-width="0" stroke="#000000" points="30.971576690673828,20.5 8.51421070098877,33.46576690673828 8.51421070098877,7.534233093261719 30.971576690673828,20.5 " fill="#ffffff" id="svg_6"/>
                </g>
            </svg>
        </div>
        <a href="#body-header" class="button">
            Home
        </a>

    </div>
    <div class="side-popout-btn">
        <div class="icon-svg">
            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>arrow</title>
                    <line stroke="#ffffff" id="svg_3" y2="30.26" x2="11" y1="8.83333" x1="11" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" fill="none"/>
                    <line id="svg_4" y2="30.16667" x2="9.99691" y1="30.16667" x1="28" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#ffffff" fill="none"/>
                    <line stroke="#ffffff" id="svg_5" y2="30.88" x2="27.00001" y1="16.45333" x1="27.00001" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" fill="none"/>
                    <line stroke="#ffffff" id="svg_6" y2="9.83334" x2="11.66513" y1="9.83334" x1="20.66822" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" fill="none"/>
                    <line id="svg_7" y2="17.17424" x2="27.34091" y1="9.83333" x1="20" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#ffffff" fill="none"/>
                    <line id="svg_9" y2="15.16667" x2="18.66667" y1="15.16667" x1="14" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#ffffff" fill="none"/>
                    <line id="svg_10" y2="20.16667" x2="23.33333" y1="20.16667" x1="14" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#ffffff" fill="none"/>
                    <line id="svg_11" y2="25.5" x2="23.33334" y1="25.5" x1="14.00001" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#ffffff" fill="none"/>
                </g>
            </svg>
        </div>
        <a href="#body-content" class="button">
            How to play
        </a>
    </div>
    <div class="side-popout-btn">
        <div class="icon-svg">
            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" class="drawing">
                <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
                <g>
                    <title>arrow</title>
                    <ellipse stroke="#ffffff" ry="13.66667" rx="13.66667" id="svg_13" cy="20.83333" cx="20.33333" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" fill="none"/>
                    <line id="svg_14" y2="30.5" x2="20.66667" y1="18.16667" x1="20.66667" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" stroke="#ffffff" fill="none"/>
                    <line stroke="#ffffff" id="svg_15" y2="14.5" x2="20.66667" y1="12.16667" x1="20.66667" stroke-linecap="null" stroke-linejoin="null" stroke-dasharray="null" stroke-width="2" fill="none"/>
                </g>
            </svg>
        </div>
        <a href="#body-footer" class="button">
            About
        </a>
    </div>
</div>
<div id="body-container">
    <div id="body-header" class="page">
        <div class="content-container">
            <div id="header-text">
                Lie to Win
            </div>
            @if(!Auth::check())
            <div class="main-button section">
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
        </div>
    </div>
    <div id="body-content" class="page">
        <div class="content-container">
            Content
        </div>
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
    </div>
    <div id="body-footer" class="page">
        <div class="content-container">
            Footer stuff
        </div>
    </div>
</div>

@stop