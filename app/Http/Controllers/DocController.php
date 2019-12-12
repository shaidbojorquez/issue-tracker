<?php

namespace App\Http\Controllers;

use App\Doc;
use App\Http\Resources\Doc as DocResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DocController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Doc  $doc
     * @return \Illuminate\Http\Response
     */
    public function show(Doc $doc)
    {
        return new DocResource($doc);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Doc  $doc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doc $doc)
    {

    }

    public function getFile()
	{
		$filename = (request()->filename) ? request()->filename : 'xyz.txt';
		if($type = request()->type){
			switch ($type) {
				case 'issues':
					$path = config('constants.paths.issues') . DIRECTORY_SEPARATOR . $filename;
					break;
				default:
					abort(404);
					break;
			}

			$path = storage_path('app'. DIRECTORY_SEPARATOR . $path);
		}else{
			abort(404);
		}

		$file = File::get($path);
		$type = File::mimeType($path);
		$response = response($file, 200,['Content-Type'=>$type]);
		return $response;
	}
}
