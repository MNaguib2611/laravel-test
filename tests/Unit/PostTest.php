<?php namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Scopes\ApprovedScope;
use App\Jobs\ProfanityCheck;


class PostTest extends TestCase {

    // register a user incase the users table is empty
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




    public function testPostCreatedPending() {

        $user = User::latest()->first();
        $post = $user->posts()->create(["user_id"=> $user,
                                        "title"=> "this is the title from unit-testing",
                                        "content"=> "this is the content from unit-testing",
                                        ]);
        $postStored = Post::withoutGlobalScope(ApprovedScope::class)->find( $post->id) ;                          
        $this->assertEquals($postStored->status,Post::PENDING);    
    }




    public function testPostRejected() {

        $user = User::latest()->first();
        $post = $user->posts()->create(["user_id"=> $user,
                                        "title"=> "damn this is the title from unit-testing",
                                        "content"=> "this is the content from unit-testing",
                                        ]);
        $postCheck = (new ProfanityCheck($post,
            "Post",
            $user)
        );
        $postCheck->handle();
        $postStored = Post::withoutGlobalScope(ApprovedScope::class)->find( $post->id) ;                          
        $this->assertEquals($postStored->status,Post::REJECTED);    
    }
  

    public function testPostApproved() {

        $user = User::latest()->first();
        $post = $user->posts()->create(["user_id"=> $user,
                                        "title"=> "clean this is the title from unit-testing",
                                        "content"=> "this is the content from unit-testing",
                                        ]);
        $postCheck = (new ProfanityCheck($post,
            "Post",
            $user)
        );
        $postCheck->handle();
        $postStored = Post::withoutGlobalScope(ApprovedScope::class)->find( $post->id) ;                          
        $this->assertEquals($postStored->status,Post::APPROVED);    
    }





















    // public function testIndex() {
    //     $response=$this->withHeaders([ 'Accept'=> 'application/json',
    //         'Content-Type'=> 'application/json',
    //         ])->json('get', '/api/posts');

    //     $response ->assertStatus(401);


    //     $response=$this->withHeaders([ 'Accept'=> 'application/json',
    //         'Content-Type'=> 'application/json',
    //         ])->json('post', '/api/auth/login', [ "email"=> "m.naguib26113@gmail.com",
    //         "password"=> "password"
    //         ]);
    //     $response ->assertStatus(200);
    //     $array=json_decode($response->getContent());
    //     $token=$array->access_token;


    //     $response=$this->withHeaders([ 'Accept'=> 'application/json',
    //         'Content-Type'=> 'application/json',
    //         'Authorization'=> $token,
    //         ])->json('get', '/api/posts');

    //     $response ->assertStatus(200);

    // }

    // public function testOnlyApprovedPostsReturn() {
    //     $response=$this->withHeaders([ 'Accept'=> 'application/json',
    //         'Content-Type'=> 'application/json',
    //         ])->json('post', '/api/auth/login', [ "email"=> "m.naguib26113@gmail.com",
    //         "password"=> "password"
    //         ]);
    //     $response ->assertStatus(200);
    //     $array=json_decode($response->getContent());
    //     $token=$array->access_token;


    //     $response=$this->withHeaders([ 'Accept'=> 'application/json',
    //         'Content-Type'=> 'application/json',
    //         'Authorization'=> $token,
    //         ])->json('get', '/api/posts');

    //     $response ->assertStatus(200);

    //     $postsArray=json_decode($response->getContent());
    //     $this->assertEquals(Post::approvedPosts()->count(), $postsArray->count);
    // }
   
}
