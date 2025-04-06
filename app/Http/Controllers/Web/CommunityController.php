<?php

namespace App\Http\Controllers\Web;

use App\Enums\CommunityTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $communities = Community::whereHas('users', fn($query) => $query->where('users.id', auth()->id()))->paginate(10);
        return view('web.communities', compact('communities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sellerId = $request->sellerId;
        if ($sellerId = null) {
            return back()->with('error', 'لم يتمكن من إنشاء المحادثة');
        }
        $seller = User::find($sellerId);
        if ($seller == null) {
            return back()->with('error', 'لم يتمكن من إنشاء المحادثة');
        }
        $community = Community::where('type', CommunityTypeEnum::CHAT->value)
            ->whereHas('users', fn($query) => $query->where('users.id', auth()->id()))
            ->whereHas('users', fn($query) => $query->where('users.id', $sellerId))->first();
        if ($community == null) {
            $community = Community::create([
                'name' => $seller->name,
                'manager_id' => auth()->id(),
                'type' => CommunityTypeEnum::CHAT->value,
                'last_update' => now()
            ]);
            $community->users()->syncWithoutDetaching([auth()->id(), $sellerId]);
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
