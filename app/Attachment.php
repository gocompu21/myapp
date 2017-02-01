<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'bytes',
        'mime',
    ];

    /* Relationships */

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /* Accessors */

    public function getBytesAttribute($value)
    {
        return format_filesize($value);
    }

    public function getUrlAttribute()
    {
        return url('files/'.$this->filename);
    }
}
