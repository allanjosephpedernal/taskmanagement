<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('task');
    }

    /**
     * Get all resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {   
        $length = $request->input('length');
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');
        
        $query = Task::eloquentQuery($sortBy, $orderBy, $searchValue)->groupBy(['name','description']);

        $data = $query->paginate($length);
        
        return new DataTableCollectionResource($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        try
        {
            // get error
            $error = static::validateRequest(
                \Validator::make($request->all(), [
                    'name' => 'required',
                    'description' => 'required'
                ])
            );

            // count error
            if (count($error) > 0)
            {
                // response
                return $this->respondWithError($error);
            }

            // extract
            extract($request->all());

            // create task
            $task = new Task;
            $task->name = $name;
            $task->description = $description;
            $task->save();

            // return
            return \Response::json(['message' => 'Task is successfully added!']);
        }
        catch(\Exception $e)
        {
            // return
            return \Response::json(['message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        try
        {
            // get task
            $task = Task::findOrFail($id);

            // return
            return \Response::json(['task' => $task]);
        }
        catch(\Exception $e)
        {
            // return
            return \Response::json(['message' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        try
        {
            // get error
            $error = static::validateRequest(
                \Validator::make($request->all(), [
                    'name' => 'required',
                    'description' => 'required'
                ])
            );

            // count error
            if (count($error) > 0)
            {
                // response
                return $this->respondWithError($error);
            }

            // extract
            extract($request->all());

            // get task
            $task = Task::findOrFail($id);
            $task->name = $name;
            $task->description = $description;
            $task->save();

            // return
            return \Response::json(['message' => 'Task is successfully updated!']);
        }
        catch(\Exception $e)
        {
            // return
            return \Response::json(['message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        try
        {
            // delete task
            Task::findOrFail($id)->delete();

            // return
            return \Response::json(['message' => 'Task is successfully deleted!']);
        }
        catch(\Exception $e)
        {
            // return
            return \Response::json(['message' => $e->getMessage()]);
        }
    }
}
