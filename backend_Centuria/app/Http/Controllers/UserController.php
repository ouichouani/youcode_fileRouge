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

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (!$token = Auth::attempt($credentials)) return response()->json(['error' => 'Unauthorized'], 401);
        return $this->respondWithToken($token);
    }

    public function register(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $token = Auth::login($user);
        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out'])->withCookie(
            cookie()->forget('token')
        );
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'user' => Auth::user()->load('image:path,imageable_id'), // this user is not changing , i keep getting the same data for one user 
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ])->cookie(
            'token',   // cookie name
            $token,    // cookie value
            auth()->factory()->getTTL(),        // expires in 60 minutes
            '/',       // available on all routes
            null,      // domain
            false,     // secure // true means the cookie will only be sent over HTTPS, false means it can be sent over HTTP as well
            false,     // httpOnly means the cookie cannot be accessed via JavaScript, which helps prevent XSS attacks
            false,     // raw
            'Lax'   // sameSite means the cookie will only be sent in first-party contexts
        );
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    // CRUD methods

    public function dashboard()
    {

        $user = Auth::user();

        if ($user->is_banned) {
            Auth::logout();
            return response()->json(['error' => 'Your account has been banned. Please contact support for more information.'], 403);
        }

        // select all user's habits and tasks with their categories and logs for the current month
        $data = $this->loadDataForDashboard($user);
        $habits = $data['habits'];
        // $habits->lastLog = $habits?->logs?->last() ;
        $tasks = $data['tasks'];
        return response()->json(['habits' => $habits, 'tasks' => $tasks]);
    }

    public function search($query, $like)
    {
        return $query->where(function ($q) use ($like) {
            $q->where('name', "like", "%$like%")->orWhere('email', 'like', "%$like%");
        });
    }

    public function index()
    {
        $user = Auth::id();

        // $this->authorize('index', User::class);
        $users = User::with('image')
            ->where('is_banned', false)
            ->where('is_banned_by_moderator', false)
            ->with(['sentRequests' => function ($query) use ($user) {
                $query->where('status', 'pending')
                ->where('receiver_id' , $user) ;
            }, 'receivedRequests' => function ($query) use ($user) {
                $query->where('status', 'pending')
                ->where('sender_id' , $user) ;
            }])
            ->select('users.*')
            ->selectRaw("EXISTS 
            (SELECT 1 FROM friend_requests 
                WHERE (
                    (sender_id = ? AND  receiver_id = users.id)
                    OR
                    (receiver_id =  ? AND sender_id = users.id)
                )
                AND status = 'accepted') as is_friend ", [$user, $user])
            ->orderBy('email');

        $like = request()->query('like');

        if ($like) $users = $this->search($users, $like);

        $users = $users->get();
        return response()->json(['users' => $users]);
    }

    public function loadRelationsforShow($primaryUser)
    {
        // This method is created to avoid code repetition in show and profile methods as they both need to load the same relations for the user
        $user = clone $primaryUser;
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        $user->load([
            'posts' => function ($q) use ($user) {

                if ($user->id != Auth::id()) {
                    $q->where('visibility', 'public');
                    if ($user->is_frend_with(Auth::user())) $q->orWhere('visibility', 'friends');
                }
                $q->latest();
            },
            'posts.comments.user.image:path,imageable_id',
            'posts.likes',
            'posts.video',
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
                $query->orderBy('title');
            },
            'habits.lastLog',
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
        $isFriend = true;

        // add a imojy based on the score 
        $user->score = $user->score . ' ' . $this->getRank($user->score);

        return response()->json([
            'user' => $user->load('image:path,imageable_id'),
            'posts' => $posts,
            'sentRequests' => $sentRequests,
            'receivedRequests' => $receivedRequests,
            'isFriend' => $isFriend,
        ], 200);
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

        // add a imojy based on the score 
        $user->score = $user->score . ' ' . $this->getRank($user->score);

        return response()->json([
            'user' => $user->load('image:path,imageable_id'),
            'posts' => $posts,
            'sentRequests' => $sentRequests,
            'receivedRequests' => $receivedRequests,
            'pendingRequest' => $pendingRequest,
            'isFriend' => $isFriend
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::find($id);
            $this->authorize('update', $user);
            if (!$user) throw new Exception('user not found');

            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);
            $image = isset($data['image']) ? $data['image'] : null;
            if (isset($image) && $image instanceof \Illuminate\Http\UploadedFile) {
                Image::store($user, 'users', $image);
            }


            return response()->json(['success' => 'User updated successfully.', 'user' => $user->load('image')]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {

        $user = User::find($id);
        $this->authorize('delete', $user);

        if (!$user) return response()->json(['error' => 'user not found'], 400);

        Image::deleteOne($user);
        $user->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }

    public function ping()
    {
        $user = Auth::user();
        $user->last_seen_at = now();
        $user->save();
        return response()->json(['success' => 'Ping received.']);
    }

    function getRank($score)
    {
        if ($score === null || $score === 0) {
            return 'Lost Soul 👻';
        }

        return match (true) {
            // 💀 EXTREME LOW (chaos zone)
            $score < -400 => 'absolut shit 💩',
            $score < -300 => 'Void Spawn 🕳️',
            $score < -250 => 'Abyss ☠️',
            $score < -200 => 'Cursed Skull 💀',
            $score < -150 => 'Broken Spirit 🪫',
            $score < -100 => 'Failed Experiment 🧪',
            // 😵 very low
            $score < -75 => 'Slime 🟢',
            $score < -50 => 'baby 🍼',
            $score < -25 => 'Potato 🥔',
            $score < 0 => 'Rat 🐀',
            // 🐣 beginner fail zone
            $score < 25 => 'Bug 🐛',
            $score < 50 => 'Dust 🌫️',
            $score < 75 => 'Lost Wanderer 🚶',
            $score < 100 => 'Peasant 🪵',
            // 📈 early progress
            $score < 150 => 'Novice 📘',
            $score < 200 => 'Apprentice 🧪',
            $score < 250 => 'Wanderer 🚶‍♂️',
            $score < 300 => 'Goblin 🧌',
            $score < 350 => 'Squire 🛡️',
            // ⚔️ mid tier
            $score < 400 => 'Hunter 🏹',
            $score < 450 => 'Knight-in-Training ⚔️',
            $score < 500 => 'Knight 🛡️',
            $score < 550 => 'Elite Fighter 🥊',
            $score < 600 => 'Shadow Blade 🌑',
            $score < 650 => 'Griffin 🦅',
            // 🔥 advanced
            $score < 700 => 'Prince 🤴',
            $score < 750 => 'Noble 🎩',
            $score < 800 => 'Warlock 🧙‍♂️',
            $score < 850 => 'Wizard 🔮',
            $score < 900 => 'Phoenix 🔥',
            $score < 950 => 'Dragon 🐉',
            // 👑 elite
            $score < 1000 => 'King 👑',
            $score < 1100 => 'High King 👑⚔️',
            $score < 1200 => 'Emperor 🏛️',
            $score < 1400 => 'Overlord 🩸',
            $score < 1600 => 'Titan ⚡',
            $score < 1800 => 'Ancient One 🗿',
            $score < 2000 => 'Mythic Hero 🌟',
            // 🌌 legendary+ tiers
            $score < 2500 => 'Celestial Guardian 🌌',
            $score < 3000 => 'Void Reaper 🕳️⚔️',
            $score < 4000 => 'Star Destroyer 🌠',
            $score < 5000 => 'Reality Bender 🧠',
            $score < 7000 => 'Eternal Legend ♾️',
            $score < 10000 => 'Ascended One ✨',

            default => 'Cosmic Sovereign 🌌👑',
        };
    }
}
