<?php

namespace App\Repositories;


use Illuminate\Http\Request;



class UserRepository{

  
  public function storePost(Request $request){
    return  auth()->user()->posts()->create($request->all());
  }
  

  public function storeComment(Request $request){
    return  auth()->user()->comments()->make($request->all());
  }
  

  public function sendNotifications(){
    return auth()->user()->unreadNotifications()->pluck('data');
  }
  

  



}

