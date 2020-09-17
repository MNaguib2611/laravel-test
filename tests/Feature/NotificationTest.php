<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Notifications\PostNotification;
use App\Notifications\CommentNotification;
use App\Models\User;

class NotificationTest extends TestCase
{
      // as every testCase should not depend on another,we need to make sure the user exists
      public function testRegisterUser() {
        User::where("email", "m.naguib26112@gmail.com")->delete();
        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            ])->json('post', '/api/auth/register', [ "name"=> "Mohammed Naguib",
            "email"=> "m.naguib26112@gmail.com",
            "password"=> "password",
            "password_confirmation"=> "password"
            ]);

        $response ->assertStatus(201);
    }

    
    public function testGetLoggedUserNotifications()
    {
        //log the user
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('post', '/api/auth/login',[ 
                            "email"    => "m.naguib26112@gmail.com",
                            "password" => "password"
                            ]);
        $response
            ->assertStatus(200);
            $array = json_decode($response->getContent());
            $token = $array->access_token;    


        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization'=> $token,
        ])->json('GET', '/api/notifications');
        $response
            ->assertStatus(200);    
    }
}
