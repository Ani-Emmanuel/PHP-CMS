<?php

namespace App\Http\Controllers;

use App\Project;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (Auth::check()) {
        $projects = Project::where('user_id', Auth::user()->id)->get();
        return view('projects.index',['projects'=>$projects]);  
        }

        return view('auth.login');
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company_id=null)
    {
        //
        $companies = null;
        if(!$company_id){
            $companies = Company::where('user_id',Auth::user()->id)->get();
        }
        return view('projects.create',['company_id'=>$company_id,'companies'=>$companies]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(Auth::check()){
        $saveProject = Project::create([
            'name'=>$request->input('name'),
            'description'=>$request->input('description'),
            'company_id'=>$request->input('company_id'),
            'user_id'=>Auth::user()->id
        ]);

        if($saveProject){
            return redirect()->route('projects.index')->with('success','Project create successfully');
        }

        }

        return back()->withInput()->with('errors','Could not create Project');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //

         $projects = Project::find($project->id);
         return view("projects.show",['projects'=>$projects]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //

        $projects = Project::find($project->id);
        return view('projects.edit',['projects'=>$projects]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
        $ProjectUpdate = Project::where('id',$project->id)
        ->update([
            'name'=>$request->input('name'),
            'description'=>$request->input('description')
        ]);

        if($ProjectUpdate){
            return redirect()->route('projects.show',['Project'=>$project->id])
            ->with('success','Project updated successfully');
        }
            

        return back()->withInput();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
        $deleteProject = Project::find($project->id);
        if($deleteProject->delete()){
            return redirect()->route('projects.index')
            ->with('success','Project deleted successfully');
        }

        return back()->withInput()->with('error','Project was not deleted');
    }
}
