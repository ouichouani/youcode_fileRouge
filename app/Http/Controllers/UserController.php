<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\FriendRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Authentication methods

    public function showLoginForm()
    {
        return view('users.users.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return redirect()->route('login')->with('error', 'The provided credentials do not match our records');
        }

        $request->session()->regenerate();

        return redirect('/dashboard')->with('success', 'You are now logged in.');
    }

    public function register(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account created successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // CRUD methods

    // public function index()
    // {
    //     $users = User::all();
    //     return view('users.users.index', compact('users'));
    // }

    public function create()
    {
        return view('users.users.register');
    }

    public function loadRelationsforShow($user)
    {
        // This method is created to avoid code repetition in show and profile methods as they both need to load the same relations for the user
        if (!$user) return redirect()->route('login')->with('error', 'Please login to view your profile');

        $user->load([

            'posts.comments',
            'posts.likes',
            'posts.images:path',
            'image:path',
            'sentRequests' => function ($query) {
                $query->where('status', 'accepted')->with(['receiver:id,name', 'receiver.image:path']);
            },
            'receivedRequests' => function ($query) {
                $query->where('status', 'accepted')->with(['sender:id,name', 'sender.image:path']);
            }

        ]);

        return [
            'posts' => $user?->posts,
            'sentRequests' =>  $user?->sentRequests,
            'receivedRequests' => $user?->receivedRequests,
        ];
    }

    public function profile()
    {
        $user = Auth::user();

        $data = $this->loadRelationsforShow($user);

        $posts = $data['posts'];
        $sentRequests = $data['sentRequests'];
        $receivedRequests = $data['receivedRequests'];

        return view('users.users.show', compact('user', 'posts', 'sentRequests', 'receivedRequests'));
    }

    public function show(int $id)
    {
        if (Auth::id() == $id) {
            return redirect()->route('users.profile') ;
        }

        $user = User::findOrFail($id);

        // check if the user is a friend of the authenticated user
        $pendingRequest = FriendRequest::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $user->id)->where('status', 'pending');
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)->where('receiver_id', Auth::id())->where('status', 'pending');
        })->get()->first(); 


        // dd($pendingRequest) ;

        $data = $this->loadRelationsforShow($user);
        $posts = $data['posts'];
        $sentRequests = $data['sentRequests'];
        $receivedRequests = $data['receivedRequests'];

        

        return view('users.users.show', compact('user', 'posts', 'sentRequests', 'receivedRequests' , 'pendingRequest'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                throw new Exception('user not found');
            }

            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return redirect()->route('users.users.show', $user->id)->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'user not found');
        }

        $user->delete();
        return redirect()->route('users.users.index')->with('success', 'User deleted successfully.');
    }
}
