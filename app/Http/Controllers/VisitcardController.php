<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\VisitCard;
use Illuminate\Support\Facades\Auth;

class VisitcardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $visitCards = VisitCard::all();
        $visitCards = VisitCard::where('user_id', $request->user()->id)->get();
        if ($visitCards->count() > 0) {
            return response()->json([
                'status' => 200,
                'visitCards' => $visitCards
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'visitCards' => 'No records found'
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $visitCard = VisitCard::create($request->all());
        $visitCard = VisitCard::create([
            'user_id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->email,
            'tel' => $request->tel,
            'adress' => $request->adress,
            'company' => $request->company,
            'description' => $request->description
        ]);

        if ($visitCard) {
            return response()->json([
                'status' => 201,
                'message' => 'Visit card created successfully',
                'visitCard' => $visitCard
            ], 201);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to create visit card'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VisitCard $visitcard)
    {
        return $visitcard;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VisitCard $visitcard)
    {
        if ($visitcard->user_id !== Auth::id()) {
            return response()->json([
                'status' => 403,
                'message' => 'Unauthorized: You do not have permission to update this visit card'
            ], 403);
        }
        if ($visitcard->update($request->all())) {
            return response()->json([
                'status' => 200,
                'message' => 'Visit card updated successfully',
                'visitCard' => $visitcard
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to update visit card'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VisitCard $visitcard)
    { //!message deleted doesn't displayed

        if ($visitcard->user_id !== Auth::id()) {
            return response()->json([
                'status' => 403,
                'message' => 'Unauthorized: You do not have permission to delete this visit card'
            ], 403);
        }
        if ($visitcard->delete()) {
            return response()->json([
                'status' => 204,
                'message' => 'Visit card deleted successfully'
            ], 204);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to delete visit card'
            ], 500);
        }
    }
}
