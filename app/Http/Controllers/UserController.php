<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\FriendRequest;
use App\Models\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Authentication methods

    public function dashboard()
    {

        $user = Auth::user();
        if ($user->is_banned) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been banned. Please contact support for more information.');
        }

        // select all user's habits and tasks with their categories and logs for the current month
        $data = $this->loadDataForDashboard($user);
        $habits = $data['habits'];
        $tasks = $data['tasks'];
        return view("dashboard.dashboard", compact('user', 'tasks', 'habits'));
    }

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
        if (Auth::user()->is_admin) return redirect()->route('admin.dashboard')->with('success', 'You are now logged in.');
        if (Auth::user()->is_moderator) return redirect()->route('moderator.dashboard')->with('success', 'You are now logged in.');

        return redirect()->route('dashboard')->with('success', 'You are now logged in.');
    }

    public function register(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if (isset($image) && $image instanceof \Illuminate\Http\UploadedFile) {
            Image::store($user, 'users', $data['image']);
        }
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

    public function search($query , $like){
        return $query->where(function($q) use($like){
            $q->where('name' , "like" ,"%$like%")->orWhere('email' , 'like', "%$like%") ;
        });
    }


    public function index()
    {
        $this->authorize('index', User::class);
        $users = User::with('image')
        ->where('is_banned' , false)
        ->where('is_banned_by_moderator' , false)
        ->orderBy('email');

        $like = request()->query('like') ;

        if($like) $users = $this->search($users , $like) ;

        $users = $users->get();

        return view('users.users.index',  compact('users'));
    }

    public function create()
    {
        return view('users.users.register');
    }

    public function loadRelationsforShow($user)
    {
        // This method is created to avoid code repetition in show and profile methods as they both need to load the same relations for the user
        if (!$user) return redirect()->route('login')->with('error', 'Please login to view your profile');

        $user->load([
            'posts' => function($q){
                $q->latest() ;
            } ,
            'posts.comments',
            'posts.likes',
            'posts.images:path,imageable_id',
            'image:path,imageable_id',

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

    public function loadDataForDashboard(User $user)
    {
        $user->load([
            'habits' => function ($query) {
                $query->orderBy('id', 'asc');
            },
            'habits.category',
            'habits.logs' => function ($query) {
                $query->whereMonth('completed_date', now()->month)->whereYear('completed_date', now()->year)->orderBy('completed_date', 'asc');
            },
            'tasks.category',
        ]);
        $habits = $user?->habits;
        $tasks = $user?->tasks;

        return [
            'habits' => $habits,
            'tasks' => $tasks,
        ];
    }

    public function profile()
    {
        $user = Auth::user();

        $data = $this->loadRelationsforShow($user);

        $posts = $data['posts'];
        $sentRequests = $data['sentRequests'];
        $receivedRequests = $data['receivedRequests'];
        $isFriend = true ;

        return view('users.users.show', compact('user', 'posts', 'sentRequests', 'receivedRequests' , 'isFriend'));
    }

    public function show(int $id)
    {
        if (Auth::id() == $id) {
            return redirect()->route('users.profile');
        }

        $user = User::findOrFail($id);

        // check if the user is a friend of the authenticated user
        $pendingRequest = FriendRequest::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $user->id)->where('status', 'pending');
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)->where('receiver_id', Auth::id())->where('status', 'pending');
        })->get()->first();

        $isFriend = FriendRequest::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $user->id)->where('status', 'accepted');
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)->where('receiver_id', Auth::id())->where('status', 'accepted');
        })->exists();

        $data = $this->loadRelationsforShow($user);
        $posts = $data['posts'];
        $sentRequests = $data['sentRequests'];
        $receivedRequests = $data['receivedRequests'];



        return view('users.users.show', compact('user', 'posts', 'sentRequests', 'receivedRequests', 'pendingRequest' , 'isFriend'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::find($id);
            $this->authorize('update', $user);
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
            $image = isset($data['image']) ? $data['image'] : null ;
            if (isset($image) && $image instanceof \Illuminate\Http\UploadedFile) {
                Image::store($user, 'users', $image);
            }


            return redirect()->route('users.show', $user->id)->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            dd($e->getMessage()) ;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {

        $user = User::find($id);
        $this->authorize('delete', $user);

        if (!$user) {
            return redirect()->back()->with('error', 'user not found');
        }

        $user->delete();
        return redirect()->route('users.users.index')->with('success', 'User deleted successfully.');
    }
}
