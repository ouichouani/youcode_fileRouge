<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConversationRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateConversationRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        // CONVERSATION FUNCTION RETURN A QUIRY BUILDER NOT A RELATIONSHIP
        $conversations = $user->conversations()
            ->with(['user1:id,name,email,last_seen_at', 'user1.image', 'user2:id,name,email,last_seen_at', 'user2.image' , 'lastMessage'])
            ->orderBy('last_message_id' , 'desc')
            ->get();
        return response()->json(['conversations' => $conversations]);
    }

    public function show(Conversation $conversation)
    {
        $conversation->load(['user1:id,name,email', 'user1.image', 'user2:id,name,email', 'user2.image' ]);
        return response()->json(['conversation' => $conversation]);
    }


    public function store(StoreConversationRequest $request)
    {
        $data = $request->validated();
        $data['user1_id'] = Auth::id();
        $message = "conversation already exists" ;

        // CHECK IF CONVERSATION ALREADY EXISTS
        $conversation = Conversation::where(function ($q) use ($data) {
            $q->where("user1_id", Auth::id())->where("user2_id", $data['user2_id']);
            })->orWhere(function ($q) use ($data) {
            $q->where("user2_id", Auth::id())->where("user1_id", $data['user2_id']);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create($data);
            $message = "conversation create with success" ;
        }
        
        $conversation->load(['user1:id,name,email', 'user1.image', 'user2:id,name,email', 'user2.image' ]);
        return response()->json(['Message' => $message , 'conversation' => $conversation]);
    }


    public function destroy(Conversation $conversation)
    {
        $conversation->delete();
        return response()->json(['message' => 'conversation deleted with success']);
    }
}
