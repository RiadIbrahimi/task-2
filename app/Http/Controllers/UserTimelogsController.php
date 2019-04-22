<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UserTimelogsController extends Controller
{
	/**
     * An endpoint at /user-timelogs which will return JSON listing users and how many seconds they have logged.
     *
     * @retrun json
     */
    public function index()
    {
    	$users = User::with('timelogs')->get();

    	$result = [];

    	foreach ($users as $user) {
    		$user['seconds_logged'] = collect($user->timelogs)->sum('seconds_logged');

    		$result[] = [
    			'user_id' => $user->id, 
    			'seconds_logged' => $user->seconds_logged
    		];
    	}

		return response()->json($result)->setEncodingOptions(JSON_PRETTY_PRINT);
    }
}