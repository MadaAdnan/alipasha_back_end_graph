<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Community\MessageResource;
use App\Models\Community;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        info($request->all());
        $user_id = $request->userId;
        $seller_id = $request->sellerId;
        $community = Community::where(fn($query) => $query->where(['user_id' => $user_id, 'seller_id' => $seller_id]))
            ->orWhere(fn($query) => $query->where(['user_id' => $seller_id, 'seller_id' => $user_id]))->first();
        if (!$community) {
            $community = Community::create([
                'user_id' => $user_id,
                'seller_id' => $seller_id,
                'last_change' => now(),
            ]);
        } else {
            $community->update(['last_change' => now()]);
        }

        $message = Message::create([
            'user_id' => $user_id,
            'message' => $request->message ?? '',
            'community_id' => $community->id,
        ]);
        if ($request->attach) {

            $message->addMedia($request->attach)->toMediaCollection('attach');

        }
        return response()->json(['message' => new MessageResource($message)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
