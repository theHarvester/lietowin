@extends('master.layout')

@section('body')

<div class="l-content">
    <div class="information pure-g-r">
    	@if(Session::has('flash_error'))
    		<div class="pure-u-1-1">
            	<div id="flash_notice">{{ Session::get('flash_error') }}</div>
        	</div>
        @endif

        @yield('content')
    </div>
</div>



@stop