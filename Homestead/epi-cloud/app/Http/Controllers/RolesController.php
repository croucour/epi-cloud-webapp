<?php

namespace App\Http\Controllers;

use App\Models\Boxes_waiting;
use App\Models\Role;
use App\Models\User;
use App\Models\Boxes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ixudra\Curl\Facades\Curl;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class RolesController extends Controller
{

    protected $prefix = "roles/";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function jwtToken() {
        $user = Auth::user();

        $customClaims = ['user_id' => $user->id];

        $payload = JWTFactory::make($customClaims);

        return JWTAuth::encode($payload);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::all()->except(1);
        $roles = Role::pluck('name', 'id');

        return view("roles.index")->with("users", $users)->with("roles", $roles);
    }

    public function update($user_id, $role_id) {
        try {
            $user = User::findOrFail($user_id);
            $newRole = Role::findOrFail($role_id);

            $previousRole = $user->role->first()->name;

            $data = ["name" => strtolower($user->last_name), "old_group" => strtolower($previousRole), "new_group"=> strtolower($newRole->name)];

            $response = Curl::to(getenv('URL_API_FTP').'user/group/')
                ->returnResponseObject()
                ->withHeader("Authorization: Bearer ".$this->jwtToken())
                ->withData($data)
                ->put();

            Role::detachAllRoles($user_id);
            $user->attachRole($newRole);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['response' => 500]);
        }
        return $response->status;
    }
}
