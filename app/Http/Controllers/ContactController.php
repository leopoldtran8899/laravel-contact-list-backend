<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::all();
        foreach($contacts as $contact) {
            $contact->company;
            $supervisor = $contact->supervisor;
            if($supervisor) {
                $contact->supervisor->contact;
            }
        }
        return response()->json(['contacts' => $contacts]);
    }

    // TODO: only allow manager role to create/update/delete contact

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     try {
    //         $this->validate($request, [
    //             'first_name' => 'required',
    //             'last_name' => 'required',
    //             'company_id' => 'required|integer',
    //             'email' => 'required|email|unique:contacts',
    //         ]);
    //     } catch (ValidationException $e) {
    //         return response()->json(['error' => $e->getMessage()], 422);
    //     }
    //     try {
    //         $contact = Contact::create($request->only(['first_name', 'last_name', 'company_id', 'email', 'phone', 'address', 'emergency_phone', 'emergency_name', 'supervisor_id']));
    //         $contact->save();
    //     } catch (QueryException $e) {
    //         return response()->json(['error' => 'Cannot create'], 422);
    //     }

    //     return response()->json(['contact' => $contact], 201);
    // }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        $contact->company;
        return response()->json(['contact' => $contact]);
    }

    /**
     * Update the specified resource in storage.
     * Only user can update their own contact
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->validate($request, [
                'email' => 'email|unique:contacts' . $id,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }


        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        try {
            $user = $request->user();
            if($user->email != $contact->email) {
                return response()->json(['error' => 'Unauthorised'], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        try {
            $contact->update($request->only(['first_name', 'last_name', 'company_id', 'email', 'phone', 'address', 'emergency_phone', 'emergency_name', 'supervisor_id']));
        } catch (QueryException $e) {
            return response()->json(['error' => 'Cannot update'], 422);
        }

        return response()->json(['contact' => $contact]);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $contact = Contact::find($id);

    //     if (!$contact) {
    //         return response()->json(['message' => 'Contact not found'], 404);
    //     }

    //     try {
    //         $contact->delete();
    //     } catch (QueryException $e) {
    //         return response()->json(['error' => 'Cannot delete'], 422);
    //     }

    //     return response()->json(['message' => 'Contact deleted']);
    // }

    public function getContactWithNotes(String $id){
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        $contact->notes;
        return response()->json(['contact' => $contact]);
    }
}