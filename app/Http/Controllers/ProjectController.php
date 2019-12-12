<?php

namespace App\Http\Controllers;

use App\Project;
use App\Http\Resources\Project as ProjectResource;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProjectResource::collection(Project::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create([
            "title" => $request->input('data.attributes.title'),
            "description" => $request->input('data.attributes.description'),
            "begin_date" => $request->input('data.attributes.begin_date'),
            "end_date" => $request->input('data.attributes.end_date'),
            "status" => $request->input('data.attributes.status')
        ]);

        if (!empty($request->input('data.attributes.users'))) {
            $project->users()->sync($request->input('data.attributes.users')); #sync sincroniza el arreglo que le pase, lo que tenga el arreglo lo pone tal cual en la tabla
            $project->save();
        }

        return new ProjectResource($project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectRequest $project)
    {
        return new ProjectResource($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->fill([
            "title" => $request->input('data.attributes.title'),
            "description" => $request->input('data.attributes.description'),
            "begin_date" => $request->input('data.attributes.begin_date'),
            "end_date" => $request->input('data.attributes.end_date'),
            "status" => $request->input('data.attributes.status')
        ]);
        $project->save();

        if (!empty($request->input('data.attributes.users'))) {
            $project->users()->sync($request->input('data.attributes.users'));
            $project->save();
        }

        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return new ProjectResource($project);
    }
}
