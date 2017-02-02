<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
//    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title',
        'content',
        'notification',
        'view_count'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'notification',
        'deleted_at',
    ];

    protected $with = ['user'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function getBytesAttribute($value){
        return format_filesize($value);
    }

    public function getUrlAttribute()
    {
        return url('files/'.$this->filename);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    /* Accessor */

    public function getCommentCountAttribute() {
        return (int) $this->comments->count();
    }

}
