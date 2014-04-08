<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lie To Win</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    {{HTML::style('css/normalize.css')}}
    @if(Route::currentRouteName() == 'play')
        {{HTML::style('css/game.less', array('type' => 'text/less'))}}
    @else
        {{HTML::style('css/page.less', array('type' => 'text/less'))}}
        {{HTML::script('js/page.js')}}
    @endif

    <link href='http://fonts.googleapis.com/css?family=Fauna+One' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
        var urlPathPrefix = "{{url('/', $parameters = array(), $secure = null)}}";
    </script>
    @yield('head')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/less.js/1.7.0/less.js"></script>
</head>
<body>
    @yield('body')
</body>
</html>