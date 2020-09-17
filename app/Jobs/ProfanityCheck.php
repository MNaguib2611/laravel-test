<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;
use App\Services\TextModerator;
use App\Notifications\PostNotification;
use App\Models\User; 
use App\Models\Post; 

class ProfanityCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post,User $user)
    {
        $this->post  = $post;
        $this->user  = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $textmoderator = new TextModerator();            
        if($textmoderator->check($this->post->title." ".$this->post->content)){
            $this->post->approve();
            $this->user->notify(new PostNotification("your post ".$this->post->title." was approved"));
        }else{
            $this->post->reject();
            $this->user->notify(new PostNotification("your post ".$this->post->title." was rejected"));
        }
    }
}
