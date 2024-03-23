<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\SessionDrill;
use App\Models\Tag;
use Illuminate\Http\Request;
use SessionGenerator\Services\Generator;

class SessionController extends Controller
{
    private string $searchParameter = "";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sessions = Session::select('id','name','tag_id')
            ->orderBy('created_at','desc')
            ->paginate(10);

        $search = $this->searchParameter;

        $currentSessionId = Session::select('id')
            ->orderBy('created_at','desc')
            ->first();

        return view('sessions.index',compact('sessions','search','currentSessionId'))
            ->with(request()->input('page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        return view("sessions.create",compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'tag' => 'required',
        ]);

        $session = new Session();        
        $session->name = $request['name'];
        $session->tag_id = $request['tag'];
        $session->save();

        return redirect()->route('sessions.show',$session->id)
                        ->with('success','Session created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Session $session)
    {
        $drills = SessionDrill::select('drills.id','drills.name','drills.description','drills.link','session_drills.id as session_drill_id')
            ->join("drills","drills.id","=","session_drills.drill_id")
            ->where("session_id","=",$session->id)
            ->get();
        return view('sessions.show',compact('session','drills'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Session $session)
    {
        $tags = Tag::all();
        return view('sessions.edit',compact('session','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Session $session)
    {
        $request->validate([
            'name' => 'required',
            'tag' => 'required',
        ]);
   
        $session->name = $request['name'];
        $session->tag_id = $request['tag'];
        $session->save();

        return redirect()->route('sessions.index')
                        ->with('success','Session updated successfully.');
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

    public function duplicate($id)
    {
        $session = Session::find($id);
        $generator = new Generator($session);
        $duplicateSessionId = $generator->duplicate();

        return redirect()->route('sessions.show',$duplicateSessionId);        
    }

    public function generate($id)
    {
        $session = Session::find($id);
        $generator = new Generator($session);
        $generator->run();

        return redirect()->route('sessions.show',$session->id);        
    }

    public function regenerate($id)
    {
        SessionDrill::where("session_id",$id)->delete();

        return $this->generate($id);
    }

    public function search(Request $request)
    {
        $this->searchParameter = $request->get("name");
        $sessions = Session::select('id','name','tag_id')
            ->where('name','like','%'.$this->searchParameter.'%')
            ->orderBy('created_at','desc')
            ->paginate(10);

        $search = $this->searchParameter;

        $currentSessionId = Session::select('id')
            ->orderBy('created_at','desc')
            ->first();

        return view('sessions.index',compact('sessions','search','currentSessionId'))
            ->with(request()->input('page'));
    }
}
