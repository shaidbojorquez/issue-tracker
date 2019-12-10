<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Tools;
use App\Doc;
use App\Http\Resources\Issue as IssueResource;
use App\Http\Resources\Doc as DocResource;
use App\Http\Requests\IssueRequest;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return IssueResource::collection(Issue::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IssueRequest $request)
    {
        $issue = Issue::create([
            "title" => $request->input('data.attributes.title'),
            "description" => $request->input('data.attributes.description'),
            "priority" => $request->input('data.attributes.priority'),
            "status" => $request->input('data.attributes.status'),
            "type" => $request->input('data.attributes.type'),
            "assignee_id" => $request->input('data.attributes.assignee_id')
        ]);

        // if (request()->ajax()) {
        return new IssueResource($issue);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        return new IssueResource($issue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function update(IssueRequest $request, Issue $issue)
    {
        $issue->fill([
            "title" => $request->input('data.attributes.title'),
            "description" => $request->input('data.attributes.description'),
            "priority" => $request->input('data.attributes.priority'),
            "status" => $request->input('data.attributes.status'),
            "type" => $request->input('data.attributes.type'),
            "assignee_id" => $request->input('data.attributes.assignee_id')
        ]);
        $issue->save();

        return new IssueResource($issue);
    }

    public function attach(Request $request, Issue $issue)
    {
        if($request->file('attachments')){
            $path = config('constants.paths.issues') . DIRECTORY_SEPARATOR . 'issue_' . $issue->id; #voy a constants.php
            $res = Tools::secureUploadFiles('attachments', $path, 'attachment_');
            $docsAttached = collect([]);
            foreach ($res['files'] as $file) {
                if(!empty($file)) {
                    $doc = new Doc;
                    $doc->title = $file['title'];
                    $doc->name = $file['name'];
                    $doc->extension = $file['extension'];
                    $doc->size = $file['file']->getSize();
                    $doc->docable_id = $issue->id;#Para decir el id del modelo con el que esta asociado el archivo
                    $doc->docable_type = Issue::class;#Para decir de que clase es si es issue o proyecto
                    $doc->save();

                    $docsAttached->push($doc);
                }
            }

            return DocResource::collection($docsAttached);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();

        return new IssueResource($issue);
    }
}
