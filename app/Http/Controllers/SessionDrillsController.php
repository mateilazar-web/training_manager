<?php

namespace App\Http\Controllers;

use App\Models\Drill;
use App\Models\Session;
use App\Models\SessionDrill;
use App\Models\Tag;
use Illuminate\Http\Request;

class SessionDrillsController extends Controller
{
    private $searchParameter;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(501);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($sessionId)
    {
        abort(501);
    }

    public function search($sessionId, Request $request)
    {
        [$drills,$search,$sessionId,$tags,$tagIds] = $this->getSearchContent($sessionId, $request);

        return view('session_drills.create', compact('drills', 'search', 'sessionId', 'tags', 'tagIds'))
            ->with(request()->input('page'));
    }

    private function getSearchContent($sessionId, Request $request)
    {
        $tagId = Session::query()->where("id", $sessionId)->first()->tag_id;
        $tags = Tag::all();
        $tagIds = $request->get("tags");
        if (empty($tagIds)) {
            $tagIds = [];
        }

        $search = "";
        if (!empty($request->get("name"))) {
            $search = $request->get("name");
        }

        $drills = $this->getDrills($tagId, $tagIds, $search);
        $drills->appends($request->all());

        return [$drills, $search, $sessionId, $tags, $tagIds];
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
            'drill' => 'required',
            'sessionId' => 'required',
        ]);

        $sessionDrill = new SessionDrill();
        $sessionDrill->drill_id = $request["drill"];
        $sessionDrill->session_id = $request["sessionId"];
        $sessionDrill->save();

        $session = $sessionDrill->session;

        $drills = SessionDrill::query()
            ->select('drills.id', 'drills.name', 'drills.description', 'session_drills.id as session_drill_id')
            ->join("drills", "drills.id", "=", "session_drills.drill_id")
            ->where("session_id", "=", $sessionDrill->session_id)
            ->get();

        return redirect()->route('sessions.show', $session->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(501);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(501);
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
        abort(501);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sessionDrill = SessionDrill::query()->where("id", $id)->first();
        $sessionId = $sessionDrill->session_id;
        $session = $sessionDrill->session;
        $sessionDrill->delete();
        
        $drills = SessionDrill::query()->select('drills.id', 'drills.name', 'drills.description', 'session_drills.id as session_drill_id')
            ->join("drills", "drills.id", "=", "session_drills.drill_id")
            ->where("session_id", "=", $sessionId)
            ->get();
            
        return response('Session drill removed', 200)
            ->header('Content-Type', 'text/plain');
    }

    public function replaceList(int $sessionDrillId, Request $request)
    {
        $sessionDrill = SessionDrill::query()->where("id", $sessionDrillId)->first();
        $drill = Drill::query()->where("id", $sessionDrill->drill_id)->first();

        [$drills,$search,$sessionId,$tags,$tagIds] = $this->getSearchContent($sessionDrill->session_id, $request);

        return view('session_drills.replace_list', compact('drill', 'sessionDrill', 'drills', 'search', 'tags', 'tagIds'))
            ->with(request()->input('page'));
    }

    private function getDrills($tagId, $tagIds, $search)
    {
        $drillsQuery = Drill::query()->select('drills.id', 'drills.name', 'drills.description', 'drills.link')
            ->distinct()
            ->join("drill_tags", "drills.id", "=", "drill_tags.drill_id")
            ->join("tags", "tags.id", "=", "drill_tags.tag_id");

        if (empty($tagIds)) {
            $drillsQuery = $drillsQuery->where("tags.id", "=", $tagId);
        } else {
            $drillsQuery = $drillsQuery->whereIn("tags.id", $tagIds);
        }
            
        if (!empty($search)) {
            $drillsQuery = $drillsQuery->where('drills.name', 'like', '%' . $search . '%');
        }
        $drills = $drillsQuery
            ->orderBy('drills.name', 'asc')
            ->paginate(10);
        

        return $drills;
    }

    public function replace(int $sessionDrillId, Request $request)
    {
        $sessionDrill = SessionDrill::query()->where("id", $sessionDrillId)->first();
        $sessionDrill->drill_id = $request->get("drill");
        $sessionDrill->save();

        $session = $sessionDrill->session;

        $drills = SessionDrill::query()
            ->select('drills.id', 'drills.name', 'drills.description', 'session_drills.id as session_drill_id')
            ->join("drills", "drills.id", "=", "session_drills.drill_id")
            ->where("session_id", "=", $sessionDrill->session_id)
            ->get();
        return view('sessions.show', compact('session', 'drills'));
    }
}
