<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;
use App\Services\TextModerator;
use App\Notifications\StatusNotification;
use App\Models\User; 
use App\Models\Post; 

class ProfanityCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $obj;
    protected $type;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */

     //incase of 
    public function __construct($obj,String $type,User $user)
    {
        $this->obj  = $obj;
        $this->type  = $type;
        $this->user  = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //using primitve binding to inject data to the TextModerator class  
        $textmoderator = app(TextModerator::class);    
        $stringHeader = mb_substr($this->obj->full_text, 0, 10);        
        if($textmoderator->check($this->obj->full_text)){
            $this->obj->approve();
            $this->user->notify(new StatusNotification("your $this->type $stringHeader  was approved"));
        }else{
            $this->obj->reject();
            $this->user->notify(new StatusNotification("your $this->type  $stringHeader  was rejected"));
        }
    }
}
