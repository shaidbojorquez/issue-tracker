<?php

namespace App\Http\Controllers;

use App\Doc;
use App\Http\Resources\Doc as DocResource;
use Illuminate\Http\Request;

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
}
