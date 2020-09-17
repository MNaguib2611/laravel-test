<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    const APPROVED = 1;
    const PENDING  = 2;
    const REJECTED = 3;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id', 'content',
    ];

    /**
     * The user who created this comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Comment post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Approve this comment.
     *
     * @return \App\Models\Comment
     */
    public function approve(): Comment
    {
        $this->status = SELF::APPROVED;
        $this->save();

        return $this;
    }

    /**
     * Reject this comment.
     *
     * @return \App\Models\Comment
     */
    public function reject(): Comment
    {
        $this->status = SELF::REJECTED;
        $this->save();

        return $this;
    }




    public static function approvedComments()
    {
        return SELF::where("status",SELF::APPROVED);
    }

}
