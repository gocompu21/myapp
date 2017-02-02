<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\CommentsEvent::class => [
          \App\Listeners\CommentsEventListener::class,
        ],
        \App\Events\ModelChanged::class => [
            \App\Listeners\CacheHandler::class,
        ],
    ];

    protected $subscribe = [
      \App\Listeners\UsersEventListener::class,
    ];

//    protected $listen = [
//        \App\Events\ArticleCreated::class => [
//            \App\Listeners\ArticlesEventListener::class,
//        ],
//    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
