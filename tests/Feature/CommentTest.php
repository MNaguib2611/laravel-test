<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;



class CommentTest extends TestCase
{
    protected $token;
    // as every testCase should not depend on another,we need to make sure the user exists
    public function testRegisterUser(){
        User::where("email","m.naguib26111@gmail.com")->delete();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('post', '/api/auth/register',[
            "name"     => "Mohammed Naguib",
            "email"    => "m.naguib26111@gmail.com",
            "password" => "password",
            "password_confirmation" => "password"
        ]);

        $response
            ->assertStatus(201);
    } 
    public function testCreateComment()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('post', '/api/auth/login',[ 
                            "email"    => "m.naguib26111@gmail.com",
                            "password" => "password"
                            ]);
        $response
            ->assertStatus(200);
            $array = json_decode($response->getContent());
            $token = $array->access_token;    
        


        $response2 = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => $token,
        ])->json('post', '/api/posts',['title'=>'approved unitTest','content'=>'approved unitTest']);
        // $post=Post::where("status",Post::APPROVED)->first();
        $post=Post::latest()->first();
       
        $response2
            ->assertStatus(202);




        // testing missing arguments comment
        $response3 = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => $token,
        ])->json('POST', "/api/posts/".$post->id."/comments");

        $response3
            ->assertStatus(422);        



        $response4 = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => $token,
        // ])->json('POST', "/api/posts/".$post->id."/comments");
        ])->json('POST', "/api/posts/".$post->id."/comments",['content'=>"unitTest comment"]);

        $response4
            ->assertStatus(202);    

    }



    public function testOnlyApprovedCommentsReturn(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->json('post', '/api/auth/login',[ 
                            "email"    => "m.naguib26111@gmail.com",
                            "password" => "password"
                            ]);
        $response
            ->assertStatus(200);
            $array = json_decode($response->getContent());
            $token = $array->access_token;    


        $post = Post::create([
            "title"   =>"UnitTest testOnlyApprovedCommentsReturnaaa",
            "content" =>"UnitTest testOnlyApprovedCommentsReturn",
            "user_id" =>User::latest()->first()->id
            ]);
            $post->approve();
            $post->save();
           
        $comment = Comment::create([
            "content" =>"UnitTest testOnlyApprovedCommentsReturn approve",
            "user_id" =>User::latest()->first()->id,
            "post_id" => $post->id
            ]);
            $comment->approve();   
            $comment->save();

        $comment2 = Comment::create([
            "content" =>"UnitTest testOnlyApprovedCommentsReturn reject",
            "user_id" =>User::latest()->first()->id,
            "post_id" => $post->id
            ]);
            $comment2->reject();        
            $comment2->save();


        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization'=> $token
        ])->json('get', '/api/posts/'.$post->id);
        $response
            ->assertStatus(200);
     
            
    }

    
}
