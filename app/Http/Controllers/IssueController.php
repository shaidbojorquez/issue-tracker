<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Tools;
use App\Doc;
use App\Http\Resources\Issue as IssueResource;
use App\Http\Resources\Doc as DocResource;
use App\Http\Requests\IssueRequest;
use App\Http\Requests\DocRequest;
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
            "project_id" => $request->input('data.attributes.project_id')
        ]);
        $issue->setCreator(auth()->user()->id);#Con el que me autentico
        $issue->setAssignedTo($request->input('data.attributes.assigned_to'));#El que yo envio
        $issue->save();

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
       /* if (!empty($issue->creator) && $issue->creator->id === auth()->user()->id) {*/
            $issue->fill([
                "title" => $request->input('data.attributes.title'),
                "description" => $request->input('data.attributes.description'),
                "priority" => $request->input('data.attributes.priority'),
                "status" => $request->input('data.attributes.status'),
                "type" => $request->input('data.attributes.type'),
                "project_id" => $request->input('data.attributes.project_id')
            ]);
            $issue->setAssignedTo($request->input('data.attributes.assigned_to'));
            $issue->save();

            return new IssueResource($issue);

          /*  }else{
                abort(401, "You need to be the owner of the issue to update issue");
            }*/
    }

    public function attach(DocRequest $request, Issue $issue)
    {
        if (!empty($issue->creator) && $issue->creator->id === auth()->user()->id) {
            if($request->file('attachments')){
                $path = config('constants.paths.issues') . DIRECTORY_SEPARATOR . 'issue_' . $issue->id;
                $res = Tools::secureUploadFiles('attachments', $path, 'attachment_');
                $docsAttached = collect([]);
                foreach ($res['files'] as $file) {
                    if(!empty($file)) {
                        $doc = new Doc;
                        $doc->title = $file['title'];
                        $doc->name = $file['title'];
                        $doc->extension = $file['extension'];
                        $doc->size = $file['file']->getSize();
                        $doc->docable_id = $issue->id;
                        $doc->docable_type = Issue::class;
                        $doc->save();

                        $docsAttached->push($doc);
                    }
                }

                return DocResource::collection($docsAttached);
            }
        }else{
            abort(401, "You need to be the owner of the issue to attacht documents");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    { #Solo el creador puede eliminar el issue
        if (!empty($issue->creator) && $issue->creator->id === auth()->user()->id) {
            $issue->users()->detach();
            $issue->delete();
            return new IssueResource($issue);
        } else {
            abort(401, "You need to be the owner of the issue to delete it.");
        }
    }
}
