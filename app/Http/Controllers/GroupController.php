<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['groups' => Group::all()]);
    }
    // TODO: only allow manager role to create/update/delete group
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        
        $group = Group::create($request->only(['name']));
        $group->save();
        
        return response()->json(['group' => $group], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, String $id)
    {
        $group = Group::find($id);
        
        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }
        
        return response()->json(['group' => $group]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        
        $group = Group::find($id);
        
        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }
        
        $group->update($request->only(['name']));
        
        return response()->json(['group' => $group]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $group = Group::find($id);
        
        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }
        try {
            $group->delete();
        } catch (QueryException $e) {
            return response()->json(['error' => 'Cannot delete'], 422);
        }
        
        return response()->json(['message' => 'Group deleted']);
    }
}