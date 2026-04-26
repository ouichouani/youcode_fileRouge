<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFriendRequestRequest;
use App\Models\FriendRequest;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{
    
    public function index()
    {
        $friendRequests = FriendRequest::Where('receiver_id', Auth::id())->with(['sender' , 'sender.image'])->get();
        return view('users.requests.index', compact('friendRequests'));
    }


    public function store(StoreFriendRequestRequest $request)
    {
        $data = $request->validated();
        $data['sender_id'] = Auth::id();
        FriendRequest::create($data);

        return redirect()->back()->with('success', 'Friend request sent successfully.');
    }


    public function accept(FriendRequest $friendRequest)
    {
        $this->authorize('accept', $friendRequest);
        $friendRequest->update(['status' => 'accepted']);
        return redirect()->back()->with('success', 'Friend request accepted successfully.');
    }

    public function reject(FriendRequest $friendRequest)
    {
        $this->authorize('reject', $friendRequest);
        $friendRequest->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Friend request rejected successfully.');
    }


    public function destroy(FriendRequest $request)
    {
        $this->authorize('delete', $request);
        $request->delete();
        return redirect()->back()->with('success', 'Friend request deleted successfully.');
    }
}
