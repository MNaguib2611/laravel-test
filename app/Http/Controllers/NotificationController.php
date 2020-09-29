<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;


class NotificationController extends Controller
{
    

    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }




    public function __invoke(Request $request): JsonResponse
    {
        $notificataions = $this->users->sendNotifications();

        return response()->json(['data' => $notificataions]);
    }
}
