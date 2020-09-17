<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Session;
use App\Models\User;

class AuthTest extends TestCase
{
   
    public function testRegisteration()
    {
        User::where("email","m.naguib2611@gmail.com")->delete();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('post', '/api/auth/register',[
            "name"     => "Mohammed Naguib",
            "email"    => "m.naguib2611@gmail.com",
            "password" => "password",
            "password_confirmation" => "password"
        ]);

        $response
            ->assertStatus(201);
        
            
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('post', '/api/auth/register',[
            "name"     => "Mohammed Naguib",
            "email"    => "m.naguib2611@gmail.com",
            "password" => "password",
            "password_confirmation" => "password"
        ]);

        $response
            ->assertStatus(400);    
    }

    public function testlogin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('post', '/api/auth/login',[ 
                            "email"    => "m.naguib2611@gmail.com",
                            "password" => "Wrong->password"
                            ]);
        $response
            ->assertStatus(401);

       
        $response2 = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('post', '/api/auth/login',[ 
                            "email"    => "m.naguib2611@gmail.com",
                            "password" => "password"
                            ]);
        $response2
            ->assertStatus(200);  
           
          
    }

    public function testlogout()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('post', '/api/auth/login',[ 
                            "email"    => "m.naguib2611@gmail.com",
                            "password" => "password"
                            ]);
        $response
            ->assertStatus(200);
            $array = json_decode($response->getContent());
            $token = $array->access_token;


            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $token,
            ])->json('post','/api/auth/logout');
            $response
                ->assertStatus(200);
    }
}
