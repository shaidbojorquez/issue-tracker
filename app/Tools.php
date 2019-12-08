<?php

namespace App;

use Illuminate\Support\Str;

class Tools
{
	/*static function secureUploadFile($name, $path, $prefix = '')
	{
		$res = '';
		if(request()->file($name)){
			$file = request()->file($name);
			$tname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
			$res = $prefix.Str::slug($tname, '-') . '.' . $file->getClientOriginalExtension();
			$file->move(storage_path(self::setSubdomain($path)),$res);
		}
		return $res;
	}*/
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
			$res = $prefix.Str::slug($tname, '-') . '.' . $file->getClientOriginalExtension();
			$tfile = $file->move(storage_path($path), $res);

			$data['isSuccess'] = true;
			$data['files'][] = $tfile;
		}

		return $data;
	}
}
