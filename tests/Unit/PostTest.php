<?php namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Scopes\ApprovedScope;
use App\Jobs\ProfanityCheck;
use App\Services\TextModerator;

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
        $postStored = Post::withoutGlobalScopes()->find( $post->id) ;                          
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
        $postStored = Post::withoutGlobalScopes()->find( $post->id) ;                          
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
        $postStored = Post::withoutGlobalScopes()->find( $post->id) ;                          
        $this->assertEquals($postStored->status,Post::APPROVED);   
    }



    //Mocking Dependency ->TextModerator
    public function testMockPost() {
        // $user = $this->createMock(User::class);
        // $post = $this->createMock(Post::class);

        $user = User::latest()->first();
        $post = $user->posts()->create(["user_id"=> $user,
                                        "title"=> "clean this is the title from unit-testing",
                                        "content"=> "this is the content from unit-testing",
                                        ]);

        $postStored = Post::withoutGlobalScopes()->find( $post->id) ;  
        $this->assertEquals($postStored->status,Post::PENDING);    

                                        
        
        $textModerator = $this->getMockBuilder('TextModeratorMock')->setMethods(['check'])->getMock();
        
        //mock that profanity check passes
        $textModerator->method('check')
                      ->willReturn(true);                                
         
        if ($textModerator->check($postStored)) {
            $post->approve();
            $postStored = Post::withoutGlobalScopes()->find( $post->id) ;  
            $this->assertEquals($postStored->status,Post::APPROVED);
        }              
                       
        
        //mock that profanity check fails
        $textModerator->method('check')
        ->willReturn('true');                                

        if ($textModerator->check($postStored)) {
            $post->reject();
            $postStored = Post::withoutGlobalScopes()->find( $post->id) ;  
            $this->assertEquals($postStored->status,Post::REJECTED);
        }              
    }

    












   
}
