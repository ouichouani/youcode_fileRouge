<?php

namespace App\Http\Controllers;

use App\Events\ConversationMessageSent;
use App\Events\GroupMessageSent;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function sendMessageInConversation(StoreMessageRequest $request, Conversation $conversation)
    {
        $message = $request->validated();
        $message['sender_id'] = Auth::id();
        $message = $conversation->messages()->create($message);
        $conversation->update([
            'last_message_id' => $message->id,
        ]);

        // FIRE THE MESSAGESENT EVENT
        broadcast(new ConversationMessageSent($message));
        return response()->json($message, 201);
    }

    public function sendMessageInGroup(Group $group, StoreMessageRequest $request)
    {
        $data = $request->validated();
        $data['sender_id'] = Auth::id();
        $message = $group->messages()->create($data);
        // FIRE THE MESSAGESENT EVENT
        broadcast(new GroupMessageSent($message));
        return response()->json($message, 201);
    }

    public function getConversationMessages(Conversation $conversation)
    {
        $messages = $conversation->messages;
        return response()->json(["messages" => $messages]); // check later
    }

    public function getGroupMessages(Group $group)
    {
        $messages = $group->messages;
        return response()->json(["messages" => $messages->load('sender.image')]); // check later
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(['message' => 'Message deleted successfully'], 204);
    }

}