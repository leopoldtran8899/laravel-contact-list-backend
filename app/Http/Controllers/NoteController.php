<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NoteController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['notes' => Note::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'note' => 'required',
                'creator_id' => 'required|integer',
                'contact_id' => 'required|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        try {
            $note = Note::create($request->only(['note', 'creator_id', 'contact_id']));
            $note->save();
        } catch (QueryException $e) {
            return response()->json(['error' => 'Cannot create'], 422);
        }

        return response()->json(['note' => $note], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        return response()->json(['note' => $note]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->validate($request, [
                'note' => 'required',
                'creator_id' => 'required|integer',
                'contact_id' => 'required|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }
        try {
            $note->update($request->only(['note', 'creator_id', 'contact_id']));
        } catch (QueryException $e) {
            return response()->json(['error' => 'Cannot update'], 422);
        }

        return response()->json(['note' => $note]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        try {
            $note->delete();
        } catch (QueryException $e) {
            return response()->json(['error' => 'Cannot delete'], 422);
        }

        return response()->json(['message' => 'Note deleted']);
    }


}