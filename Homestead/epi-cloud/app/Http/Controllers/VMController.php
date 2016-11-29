<?php

namespace App\Http\Controllers;

use ClassPreloader\Config;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class VMController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $client = new Client(['http_errors' => false]);
        $res = $client->request(
            'GET', 'https://epi-cloud-vm-service.herokuapp.com/api/v1' . '/boxes', []);
        if ($res->getStatusCode() == 503)
            return view('serverout');
        // "200"
        echo $res->getHeader('content-type');
        echo $res->getBody();

        return view('virtualmachines');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = new Client(['http_errors' => false]);
        $res = $client->request(
            'POST', Config::get('urls.VM_SERVER') . '/boxes', [
            'form_params' => [
                "os" => "ubuntu",
                "os_version" => "trusty64",
                "nb_core" => 1,
                "ram" => 512,
                "name" => "test_vm_name",
            ]
        ]);
        echo $res->getStatusCode();
        // "200"
        echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        echo $res->getBody();
        // {"type":"User"...'
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}