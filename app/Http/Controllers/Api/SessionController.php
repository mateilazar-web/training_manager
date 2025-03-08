<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\SessionDrill;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Session::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response('The method is not implemented', 501)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function show(Session $session)
    {
        return response('The method is not implemented', 501)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Session $session)
    {
        return response('The method is not implemented', 501)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function destroy(Session $session)
    {
        return response('The method is not implemented', 501)
            ->header('Content-Type', 'text/plain');
    }

    public function reorder(Request $request, Session $session)
    {
        if (empty($request['drillIds'])) {
            return response('Session drills not provided', 400)
                ->header('Content-Type', 'text/plain');
        }

        $collection = SessionDrill::query()
            ->where("session_id", "=", $session->id)->get(['id']);
        SessionDrill::destroy($collection->toArray());
        
        foreach ($request['drillIds'] as $drillId) {
            $sessionDrill = new SessionDrill();
            $sessionDrill->session_id = $session->id;
            $sessionDrill->drill_id = $drillId;
            $sessionDrill->save();
        }

        return response('Drills saved in correct order', 200)
            ->header('Content-Type', 'text/plain');
    }
}
