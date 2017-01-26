<p>
    {{$article->content}}
    <small>{{$article->created_at}}</small>
<br />
<br />
<div style="text-align: center;">
    <img src="{{ $message->embed(storage_path('elephant.gif')) }}" alt="">
</div>
</p>

