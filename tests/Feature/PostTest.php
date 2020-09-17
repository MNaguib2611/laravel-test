<?php namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;


class PostTest extends TestCase {

    // as every testCase should not depend on another,we need to make sure the user exists
    public function testRegisterUser() {
        User::where("email", "m.naguib26113@gmail.com")->delete();
        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            ])->json('post', '/api/auth/register', [ "name"=> "Mohammed Naguib",
            "email"=> "m.naguib26113@gmail.com",
            "password"=> "password",
            "password_confirmation"=> "password"
            ]);

        $response ->assertStatus(201);
    }

    public function testIndex() {
        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            ])->json('get', '/api/posts');

        $response ->assertStatus(401);


        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            ])->json('post', '/api/auth/login', [ "email"=> "m.naguib26113@gmail.com",
            "password"=> "password"
            ]);
        $response ->assertStatus(200);
        $array=json_decode($response->getContent());
        $token=$array->access_token;


        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            'Authorization'=> $token,
            ])->json('get', '/api/posts');

        $response ->assertStatus(200);

    }

    public function testOnlyApprovedPostsReturn() {
        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            ])->json('post', '/api/auth/login', [ "email"=> "m.naguib26113@gmail.com",
            "password"=> "password"
            ]);
        $response ->assertStatus(200);
        $array=json_decode($response->getContent());
        $token=$array->access_token;


        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            'Authorization'=> $token,
            ])->json('get', '/api/posts');

        $response ->assertStatus(200);

        $postsArray=json_decode($response->getContent());
        $this->assertEquals(Post::approvedPosts()->count(), $postsArray->count);
    }


    public function testCreatePost() {
        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            ])->json('post', '/api/auth/login', [ "email"=> "m.naguib26113@gmail.com",
            "password"=> "password"
            ]);
        $response ->assertStatus(200);
        $array=json_decode($response->getContent());
        $token=$array->access_token;

        //test missing arguments post
        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            'Authorization'=> $token,
            ])->json('post', '/api/posts'); //will use this post in the next test case

        $response ->assertStatus(422);





        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            'Authorization'=> $token,
            ])->json('post', '/api/posts', ['title'=>'rejected unitTest', 'content'=>'damn unitTest']); //will use this post in the next test case

        $response ->assertStatus(202);

    }

    public function testUnApprovedPostReturnsNotFound() {
        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            ])->json('post', '/api/auth/login', [ "email"=> "m.naguib26113@gmail.com",
            "password"=> "password"
            ]);
        $response ->assertStatus(200);
        $array=json_decode($response->getContent());
        $token=$array->access_token;

        $post = Post::create([
            "title"   =>"UnitTest testOnlyApprovedCommentsReturnaaa",
            "content" =>"UnitTest testOnlyApprovedCommentsReturn",
            "user_id" =>User::latest()->first()->id
            ]);
        $post->reject();
        $post->save();

        $response=$this->withHeaders([ 'Accept'=> 'application/json',
            'Content-Type'=> 'application/json',
            'Authorization'=> $token,
            ])->json('get', "/api/posts/".$post->id);

        $response ->assertStatus(404); //meaning that the post containing profanity got rejected-->will not be retrieved   

    }
}
