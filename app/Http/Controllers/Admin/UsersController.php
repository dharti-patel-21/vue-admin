<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index(){
        //$users = User::query()->latest()->paginate(setting('pagination_limit'));
        $users = User::query()->latest()->get();
        return $users;
    }

    public function store(){
        return User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);
    }
}
