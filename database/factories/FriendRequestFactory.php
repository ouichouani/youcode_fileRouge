<?php

namespace Database\Factories;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<FriendRequest>
 */
class FriendRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $sender = User::inRandomOrder()->first() ;
        
        $receiver = User::where('id', '!=', $sender->id)
            ->whereDoesntHave('receivedRequests' , function($q) use ($sender){
                $q->where("sender_id" , $sender->id ) ;
            })->whereDoesntHave('sentRequests' , function($q) use ($sender){
                $q->where('receiver_id' , $sender->id) ;
            })->inRandomOrder()->first() ;


        return [
            'status' => fake()->randomElement(['pending' , 'accepted' , 'rejected']),
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id
        ];
    }
}
