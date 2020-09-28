<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApprovedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */





    //  incase of using with model Post --->  $model::APPROVED = 'approved'
    //  incase of using with model Post --->  $model::APPROVED = 1
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('status',$model::APPROVED);
    }
}