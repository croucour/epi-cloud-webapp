<?php

namespace App\Http\Controllers;

use App\Models\Boxes_waiting;
use App\Models\User;
use App\Models\Boxes;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $vms = Boxes::getByRole();
        $boxes_waiting = Boxes_waiting::getByRole();

        return view('dashboard')->with('nb_vms', $vms->count())->with("nb_boxes_waiting", $boxes_waiting == null ? 0 : $boxes_waiting->count());
    }
}
