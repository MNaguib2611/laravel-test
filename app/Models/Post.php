<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;
    const APPROVED = "approved";
    const PENDING  = "pending";
    const REJECTED = "rejected";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'content',
    ];

    /**
     * The user who created this post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Post comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Approve this post.
     *
     * @return \App\Models\Post
     */
    public function approve(): Post
    {
        $this->status = 'approved';
        $this->save();

        return $this;
    }

    /**
     * Reject this post.
     *
     * @return \App\Models\Post
     */
    public function reject(): Post
    {
        $this->status = 'rejected';
        $this->save();

        return $this;
    }

   
    public static function approvedPosts()
    {
        return SELF::where("status",SELF::APPROVED);
    }

    
    
}
