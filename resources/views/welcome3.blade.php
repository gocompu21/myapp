<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
</head>
    <body>
        <!-- HTML 주석안에서 {{ $name }}을 출력합니다-->
        {{--블레이드 주석안에서 {{$name}}을 출력합니다 --}}
        <h1>{{$greeting or 'Hello '}}{{$name or ''}}</h1>
    </body>
</html>
