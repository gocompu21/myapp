<?php

if(!function_exists('markdown')){

    function markdown($text = null){
        return app(ParsedownExtra::class)->text($text);
    }
}

if(!function_exists('gravatar_url')) {

    function gravatar_url($email, $size=48){
        return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($email), $size);
    }
}

if(!function_exists('gravatar_profile_url')) {

    function gravatar_profile_url($email)
    {
        return sprintf("//www.gravatar.com/%s",md5($email));
    }
}

if(!function_exists('attachments_path')){
    function attachments_path($path = ''){
        return public_path('files'.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }
}
if(!function_exists('format_filesize')){
    function format_filesize($bytes){
        if (! is_numeric($bytes)) return 'NaN';

        $decr = 1024;
        $step = 0;
        $suffix = ['bytes', 'KB', 'MB'];

        while (($bytes / $decr) > 0.9) {
            $bytes = $bytes / $decr;
            $step ++;
        }

        return round($bytes, 2) . $suffix[$step];
    }
}

if (! function_exists('link_for_sort')) {
    /**
     * Build HTML anchor tag for sorting
     *
     * @param string $column
     * @param string $text
     * @param array  $params
     * @return string
     */
    function link_for_sort($column, $text, $params = [])
    {
        $direction = request()->input('order');
        $reverse = ($direction == 'asc') ? 'desc' : 'asc';

        if (request()->input('sort') == $column) {
            // Update passed $text var, only if it is active sort
            $text = sprintf(
                "%s %s",
                $direction == 'asc'
                    ? '<i class="fa fa-sort-alpha-asc"></i>'
                    : '<i class="fa fa-sort-alpha-desc"></i>',
                $text
            );
        }

        $queryString = http_build_query(array_merge(
            request()->except(['sort', 'order']),
            ['sort' => $column, 'order' => $reverse],
            $params
        ));

        return sprintf(
            '<a href="%s?%s">%s</a>',
            urldecode(request()->url()),
            $queryString,
            $text
        );
    }
}

if (! function_exists('cache_key')) {
    /**
     * Generate key for caching.
     *
     * Note that, even though the request endpoints are the same
     *     the response body may be different because of the query string.
     *
     * @param $base
     * @return string
     */
    function cache_key($base)
    {
        $key = ($query = request()->getQueryString())
            ? $base . '.' . urlencode($query)
            : $base;

        return md5($key);
    }
}

if (! function_exists('taggable')) {
    /**
     * Determine if the current cache driver has cacheTags() method
     *
     * @return bool
     */
    function taggable()
    {
        return in_array(config('cache.default'), ['memcached', 'redis'], true);
    }
}

if (! function_exists('jwt')) {
    function jwt()
    {
        return app('tymon.jwt.auth');
    }
}


if (! function_exists('is_api_domain')) {
    function is_api_domain()
    {
        return starts_with(request()->getHttpHost(), config('project.api_domain'));
    }
}


