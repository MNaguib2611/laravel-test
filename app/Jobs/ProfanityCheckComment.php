<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;
use App\Services\TextModerator;
use App\Notifications\CommentNotification;
use App\Models\User; 

class ProfanityCheckComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $comment;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment,User $user)
    {
        $this->comment  = $comment;
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
        if($textmoderator->check($this->comment->content)){
            $this->comment->approve();
            $this->user->notify(new CommentNotification("your comment ". $this->comment->content ." was approved"));
        }else{
            $this->comment->reject();
            $this->user->notify(new CommentNotification("your comment ". $this->comment->content ." was rejected"));
        }
    }
}
