<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $joinedGroups = $user->groups;
        $ownedGroups = $user->ownedGroups;
        $groups = array_merge($joinedGroups->toArray(), $ownedGroups->toArray());
        return response()->json(['groups' => $groups]);
    }

    public function store(StoreGroupRequest $request)
    {
        $data = $request->validated();
        $data["owner_id"] = Auth::id();
        $group = Group::create($data);
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            Image::store($group, 'groups', $data['image'] ?? null);
        }
        $group->users()->sync($data['members'] ?? []);

        return response()->json(['Message' => 'group create with success', 'group' => $group]);
    }

    public function show(Group $group)
    {
        return response()->json(['group' => $group->load('image', 'owner.image', 'users.image', 'lastMessage')]);
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        if ($group->owner_id !== Auth::id()) {
            return response()->json(['message' => 'you are not authorized to update this group'], 403);
        }
        $data = $request->validated();
        $group->update($data);
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            Image::store($group, 'groups', $data['image'] ?? null);
        }
        return response()->json(["message" => "group updates with success", "group" => $group]);
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return response()->json(["message" => "group deleted with success"]);
    }

    public function addMember(Group $group, User $user)
    {
        $group->users()->sync($group->users->pluck('id')->push($user->id));
        return response()->json(['message' => 'user added with success', "members" => $group->users]);
    }

    public function RemoveMember(Group $group, User $user)
    {
        $group->users()->sync([...$group->users->pluck('id')->filter(fn($id) => $id !== $user->id)]);
        return response()->json(['message' => 'user removed with success', "members" => $group->users]);
    }
}
