<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FilesController extends Controller
{
	public function getFile()
	{
		$filename = (request()->filename) ? request()->filename : 'xyz.txt'; #para verificar si vino el filename de la petición
		if($type = request()->type){
			switch ($type) {
				case 'issues':
					$path = config('constants.paths.issues') . DIRECTORY_SEPARATOR . $filename;
					break;
				default:
					abort(404);
					break;
			}

			$path = storage_path($path);
		}else{
			abort(404);
		}

		$file = File::get($path);
		$type = File::mimeType($path);
		$response = response($file, 200,['Content-Type'=>$type]);
		return $response;
	}
}
