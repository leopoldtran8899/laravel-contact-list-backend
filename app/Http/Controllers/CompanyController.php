<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['companies' => Company::all()]);
    }
    // TODO: only allow manager role to create/update/delete company
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
        try {
            $company = Company::create($request->only(['name', 'group_id']));
            $company->save();
        } catch (QueryException $e) {
            return response()->json(['error' => 'Cannot create'], 422);
        }

        return response()->json(['company' => $company], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        return response()->json(['company' => $company]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->validate($request, [
                'name' => 'required_if:group_id,undefined',
                'group_id' => 'required_if:name,undefined|integer'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $company = Company::find($id);

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }
        try {
            $company->update($request->only(['name', 'group_id']));
        } catch (QueryException $e) {
            return response()->json(['error' => 'Cannot update'], 422);
        }

        return response()->json(['company' => $company]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        try {
            $company->delete();
        } catch (QueryException $e) {
            return response()->json(['error' => 'Cannot delete'], 422);
        }

        return response()->json(['message' => 'Company deleted']);
    }

    /**
     * Get Company with its contacts
     */
    public function getCompanyWithContacts(string $id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }
        $company->contacts;
        return response()->json(['company' => $company]);
    }
}