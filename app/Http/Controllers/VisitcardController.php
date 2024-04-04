<?php
// http://localhost:8000/api/documentation
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\VisitCard;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="VisitCard",
 *     required={"id", "name", "email", "tel", "adress", "company", "description"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="tel", type="string", example="123456789"),
 *     @OA\Property(property="adress", type="string", example="123 Main St"),
 *     @OA\Property(property="company", type="string", example="ABC Inc."),
 *     @OA\Property(property="description", type="string", example="Lorem ipsum"),
 * )
 */

class VisitcardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/visitcards",
     *     summary="Get all visit cards",
     *     tags={"Visit Cards"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/VisitCard")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
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
     *
     * @OA\Post(
     *     path="/visitcards",
     *     summary="Store a new visit card",
     *     tags={"Visit Cards"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Visit card data",
     *         @OA\JsonContent(
     *             required={"name", "email", "tel", "adress", "company", "description"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="tel", type="string"),
     *             @OA\Property(property="adress", type="string"),
     *             @OA\Property(property="company", type="string"),
     *             @OA\Property(property="description", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Visit card created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Visit card created successfully"),
     *             @OA\Property(property="visitCard", ref="#/components/schemas/VisitCard")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to create visit card",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Failed to create visit card")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'tel' => 'required|string',
            'adress' => 'required|string',
            'company' => 'required|string',
            'description' => 'required|string',
        ]);

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
     *
     * @OA\Put(
     *     path="/visitcards/{visitcard}",
     *     summary="Update a visit card",
     *     tags={"Visit Cards"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="visitcard",
     *         in="path",
     *         description="ID of the visit card to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated visit card data",
     *         @OA\JsonContent(
     *             required={"name", "email", "tel", "adress", "company", "description"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="tel", type="string"),
     *             @OA\Property(property="adress", type="string"),
     *             @OA\Property(property="company", type="string"),
     *             @OA\Property(property="description", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Visit card updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Visit card updated successfully"),
     *             @OA\Property(property="visitCard", ref="#/components/schemas/VisitCard")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized: You do not have permission to update this visit card",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized: You do not have permission to update this visit card")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to update visit card",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Failed to update visit card")
     *         )
     *     )
     * )
     */
    public function update(Request $request, VisitCard $visitcard)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'tel' => 'required|string',
            'adress' => 'required|string',
            'company' => 'required|string',
            'description' => 'required|string',
        ]);
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
     *
     * @OA\Delete(
     *     path="/visitcards/{visitcard}",
     *     summary="Delete a visit card",
     *     tags={"Visit Cards"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="visitcard",
     *         in="path",
     *         description="ID of the visit card to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Visit card deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized: You do not have permission to delete this visit card",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="Unauthorized: You do not have permission to delete this visit card")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to delete visit card",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Failed to delete visit card")
     *         )
     *     )
     * )
     */
    public function destroy(VisitCard $visitcard)
    { 
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
