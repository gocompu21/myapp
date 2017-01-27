<?php

namespace App;

use File;

class Documentation
{
    public function get($file='documentation.md'){
        $content = File::get($this->path($file));
        return $this->replaceLinks($content);
    }

    public function etag($file){
        $lastmodified = File::lastmodified($this->path($file,'docs/images'));
        return md5($file . $lastmodified);
    }

    public function image($file)
    {
        return \Image::make($this->path($file, 'docs/images'));
    }

    protected function path($file, $dir='docs')
    {
        $file = ends_with($file, ['.md','.png']) ? $file : $file . '.md';
        $path = base_path($dir . DIRECTORY_SEPARATOR . $file);

        if(! File::exists($path)){
            abort(404, '요청하신 파일이 없습니다');
        }
        return $path;
    }

    protected function replaceLinks($content)
    {
        return str_replace('/docs/{{version}}','/docs',$content);
    }
}
