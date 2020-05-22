<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ToDo;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ToDo::orderBy('id', 'desc')->get();
    }

    public function index_done(){
        return ToDo::where('is_done', true)->orderBy('id', 'desc')->get();
    }

    public function index_undone(){
        return ToDo::where('is_done', false)->orderBy('id', 'desc')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todo = new ToDo;

        $todo->title = $request->input('title');
        
        if($todo->save()) {
            return $todo;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return ToDo::findOrFail($request->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = ToDo::findOrFail($id);

        $task->title = $request->input('title');
        
        if($task->save()) {
            return $task;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = ToDo::findOrFail($id);

        if($todo->is_done){
            $todo->delete();
            return response()->json(['deleted' => true], 201);
        }

        return response()->json(['deleted' => false], 200);
    }

    public function done_task($id)
    {
        $todo = ToDo::findOrFail($id);
        $todo->is_done = true;

        if($todo->save()) {
            return $todo;
        }
    }
}