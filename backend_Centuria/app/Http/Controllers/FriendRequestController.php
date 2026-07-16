<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFriendRequestRequest;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{

    public function index()
    {
        // GET ALL FRIEND REQUESTS FOR THE AUTHENTICATED USER
        $friendRequests = FriendRequest::Where('receiver_id', Auth::id())->with(['sender', 'sender.image'])->get();
        return response()->json(['friendRequests' => $friendRequests]);
    }

    public function followers(User $user)
    {
        $followers = FriendRequest::Where('receiver_id', $user->id)
            ->where('status', 'accepted')
            ->with(['sender', 'sender.image'])
            ->select("friend_requests.*")
            ->selectRaw("
        EXISTS(
            SELECT 1
            FROM friend_requests fr2
            WHERE fr2.sender_id = friend_requests.receiver_id
            AND fr2.receiver_id = friend_requests.sender_id
            AND fr2.status IN ('accepted', 'accepted')
        ) as is_following_back
    ")
            ->get();
        return response()->json(['followers' => $followers]);
    }

    public function following(User $user)
    {
        $following = FriendRequest::Where('sender_id', $user->id)->with(['receiver', 'receiver.image'])
            ->where('status', 'accepted')->get();
        return response()->json(['following' => $following]);
    }

    public function allFriends()
    {
        $user = Auth::user() ;
        $friends = FriendRequest::allFriends($user) ;
        return response()->json(['friends' => $friends]);
    }


    public function store(StoreFriendRequestRequest $request)
    {
        $data = $request->validated();
        $data['sender_id'] = Auth::id();
        $friendRequest = FriendRequest::create($data);
        return response()->json(['friendRequest' => $friendRequest]);
    }


    public function accept(FriendRequest $friendRequest)
    {
        $this->authorize('accept', $friendRequest);
        $friendRequest->update(['status' => 'accepted']);
        return response()->json(['message' => 'request accepted']);
    }


    public function reject(FriendRequest $friendRequest)
    {
        $this->authorize('reject', $friendRequest);
        $friendRequest->update(['status' => 'rejected']);
        return response()->json(['message' => 'request rejected']);
    }


    public function destroy(FriendRequest $request)
    {
        $this->authorize('delete', $request);
        $request->delete();
        return response()->json(['message' => 'success', 'Friend request deleted successfully']);
    }
}
