<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>블레이드 문법</title>
</head>
    <body>
    <h1>블레이드 문법</h1>
        @if($itemCount = count($items))
        <p>{{$itemCount}} 종류의 파일이 있습니다</p>
        @else
        <p>엥 아무것도 없네요</p>
        @endif
    <ul>
        <p>foreach..endforeach</p>
        @foreach($items as $item)
            <li>{{$item}}</li>
        @endforeach

        <p>'forelse..empty..endforelse'</p>
        @forelse($items as $item)
            <li>{{$item}}</li>
        @empty
            <li>앵 아무것도 없네요</li>
        @endforelse

    </ul>
    </body>
</html>
