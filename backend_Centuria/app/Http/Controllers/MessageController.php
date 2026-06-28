<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function index($id)
    {
        // $id IS THE ID OF THE USER YOU WANT TO GET MESSAGES WITH

        $messages = Message::where(function ($query) use ($id) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('sender_id', $id)
                ->where('receiver_id', Auth::id());
        })->get();

        return response()->json($messages);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request)
    {
        $message = $request->validated();
        $message['sender_id'] = Auth::id();
        $message = Message::create($message);
        return response()->json($message, 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(['message' => 'Message deleted successfully'], 204);
    }
}
