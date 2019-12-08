<?php

if (! function_exists('src')) {
    /**
     * Description
     *
     * @param  string $filename
     * @param  string  $default
     * @return route
     */
    function src($filename, $default = '')
    {
    	$params = ['filename'=>$filename];
    	if($default)$params['type'] = $default;
    	return route('src', $params); #utiliza Route::get('/files', 'FilesController@getFile')->name('src');
    }
}
