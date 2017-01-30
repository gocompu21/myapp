<div class="media">
    @include('users.partial.avatar', ['user' => $article->user])
    <div class="media-body">
        <h4 class="media-heading">
            <a href="{{ route('articles.show', $article->id) }}">
                {{ $article->title }}
            </a>
        </h4>
        <p class="text-muted meta__article">
            <i class="fa fa-user"></i> {{ $article->user->name }}
            <i class="fa fa-closck-o"></i>{{ $article->created_at->diffForHumans() }}
        </p>
    </div>
</div>
