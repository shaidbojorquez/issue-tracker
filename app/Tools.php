<?php

namespace App;

use Illuminate\Support\Str;

class Tools
{
    static function secureUploadFiles($name, $path, $prefix = '')
	{
		$data = array(
			"hasWarnings" => false,
			"isSuccess" => false,
			"warnings" => array(),
			"files" => array()
		);
		$files = [];
		$input = request()->file($name);#Es el input que contiene los archivos
		if (!is_array($input))
			$files[] = $input;
		else
			$files = $input;

		foreach ($files as $file) {
			$tname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
			$res = $prefix . Str::slug($tname, '-') . '.' . $file->getClientOriginalExtension();
            // $tfile = $file->move(storage_path($path), $res);
            // $tfile = Storage::putFile($path.$res, $file);
            $file->storeAs($path, $res);


            $data['isSuccess'] = true;
            $data['files'][] = array(
                'file' => $file,
                'name' => $res,
                'extension' => $file->getClientOriginalExtension(),
                'title' => $prefix . Str::slug($tname, '-')
            );
		}

		return $data;
	}
}
