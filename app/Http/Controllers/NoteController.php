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
        $notes = Note::all();
        foreach ($notes as $note) {
            $creator = $note->creator;
            if($creator) {
                $note->creator->contact;
            }
        }
        return response()->json(['notes' => $notes]);
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

    /**
     * Get notes written by user for a contact
     */
    public function getNotesByContactFromUser(Request $request, $contactId)
    {
        $user = null;
        try {
            $user = $request->user();
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
        $notes = Note::where('contact_id', $contactId)->where('creator_id', $user->id)->get();
        foreach($notes as $note) {
            $creator = $note->creator;
            if($creator) {
                $note->creator->contact;
            }
        }
        return response()->json(['notes' => $notes]);
    }
    /**
     * Create new note by user for a contact by id
     */
    public function createNoteForContact(Request $request)
    {
        $user = null;
        try {
            $user = $request->user();
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
        try {
            $this->validate($request, [
                'note' => 'required',
                'contact_id' => 'required|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        try {
            $note = Note::create([
                'note' => $request->input('note'),
                'creator_id' => $user->id,
                'contact_id' => $request->input('contact_id')
            ]);
            $note->save();
        } catch (QueryException $e) {
            return response()->json(['error' => 'Cannot create'], 422);
        }

        return response()->json(['note' => $note], 201);
    }

}