<?php

namespace App\Repositories;


use Illuminate\Http\Request;



class UserRepository{

  
  public function storePost(Request $request){
    return  auth()->user()->posts()->create($request->all());
  }
  

  public function makeComment(Request $request){
    return  auth()->user()->comments()->make($request->all());
  }
  

  public function fetchNotifications(){
    return auth()->user()->unreadNotifications()->pluck('data');
  }
  

  



}

