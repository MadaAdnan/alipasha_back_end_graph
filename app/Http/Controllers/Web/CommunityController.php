<?php

namespace App\Http\Controllers\Web;

use App\Enums\CommunityTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       /*$community = Community::whereNot('type', 'live')->whereHas('messages')->whereHas('allUsers', function ($query) {
            $query->where('users.id', auth()->id());  // جلب المجتمعات التي يشارك فيها المستخدم الحالي
        })->latest('last_update')
            ->first();*/
        return view('theme2.conversation');
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
        if ($sellerId == null) {
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
        // dd($community);
        return redirect()->route('communities.show', $community->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $community = Community::whereHas('users', fn($q) => $q->where('users.id', auth()->id()))->findOrFail($id);


        return view('theme2.conversation', compact('community'));
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

    public function report(Request $request)
    {
        $messag=$request->msg;
        $setting=Setting::first();
        $suport_user=$setting->support_id;
        if($suport_user==null){
            return back()->with('error', 'لم يتمكن من إرسال الرسالة');
        }
        $community = Community::where('type', CommunityTypeEnum::CHAT->value)
            ->whereHas('users', fn($query) => $query->where('users.id', auth()->id()))
            ->whereHas('users', fn($query) => $query->where('users.id', $suport_user))->first();
        if ($community == null) {
            $community = Community::create([
                'name' => 'الدعم الفني',
                'manager_id' => auth()->id(),
                'type' => CommunityTypeEnum::CHAT->value,
                'last_update' => now()
            ]);
            $community->users()->syncWithoutDetaching([auth()->id(), $suport_user]);
        }
        Message::create([
            'community_id' => $community->id,
            'user_id' => auth()->id(),
            'body' => $messag,
            'type'=>'text',
        ]);

        return redirect()->route('communities.show', $community->id);
    }
}
