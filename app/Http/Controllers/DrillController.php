<?php

namespace App\Http\Controllers;

use App\Models\Drill;
use App\Models\DrillTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;

class DrillController extends Controller
{
    private string $searchParameter = "";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tags = Tag::all();

        $drills = Drill::query()
            ->select('id', 'name', 'description', 'link')->orderBy('id', 'asc')->paginate(10);

        $search = $this->searchParameter;

        $tagIds = $request->get("tags");
        if (empty($tagIds)) {
            $tagIds = [];
        }

        return view('drills.index', compact('drills', 'search', 'tags', 'tagIds'))
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
        return view("drills.create", compact("tags"));
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
            'description' => 'required_without:link',
            'link' => 'required_without:description'
        ]);

        $drill = new Drill();
        $drill->name = $request['name'];
        $drill->description = $request['description'];
        $drill->link = $request['link'];

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('videos', ['disk' => 'my_files']);
            $drill->video = $path;
        }

        $drill->save();

        foreach ($request['tags'] as $tagId) {
            $drillTag = new DrillTag();
            $drillTag->drill_id = $drill->id;
            $drillTag->tag_id = $tagId;
            $drillTag->save();
        }
        

        return redirect()->route('drills.show', $drill->id)
                        ->with('success', 'Drill created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Drill  $drill
     * @return \Illuminate\Http\Response
     */
    public function show(Drill $drill)
    {
        $tags = Drill::query()
            ->select('tags.id', 'tags.name')
            ->join("drill_tags", "drills.id", "=", "drill_tags.drill_id")
            ->join("tags", "tags.id", "=", "drill_tags.tag_id")
            ->where("drills.id", "=", $drill->id)
            ->distinct()
            ->get();
        return view('drills.show', compact('drill', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Drill  $drill
     * @return \Illuminate\Http\Response
     */
    public function edit(Drill $drill)
    {
        $tags = Tag::all();
        $drillTags = DrillTag::query()
            ->where('drill_id', '=', $drill->id)
            ->pluck('tag_id')
            ->toArray();

        return view('drills.edit', compact('drill', 'tags', 'drillTags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Drill  $drill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Drill $drill)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required_without:link',
            'link' => 'required_without:description'
        ]);
   
        $drill->name = $request['name'];
        $drill->description = $request['description'];
        $drill->link = $request['link'];

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('videos', ['disk' => 'my_files']);
            $drill->video = $path;
        }
        
        $drill->save();

        $existingTags = [];
        foreach ($drill->drillTags()->get() as $drillTag) {
            if (!in_array($drillTag->tag_id, $request['tags'])) {
                $drillTag->delete();
            } else {
                $existingTags[] = $drillTag->tag_id;
            }
        }

        foreach ($request['tags'] as $tagId) {
            if (!in_array($tagId, $existingTags)) {
                $drillsTag = new DrillTag();
                $drillsTag->tag_id = $tagId;
                $drillsTag->drill_id = $drill->id;
                $drillsTag->save();
            }
        }

        return redirect()->route('drills.show', $drill->id)
            ->with('success', 'Drill updated successfully.');
    }

    public function search(Request $request)
    {
        $tags = Tag::all();

        $tagIds = $request->get("tags");
        if (empty($tagIds)) {
            $tagIds = [];
        }

        $search = $request->get("name");

        $drillsQuery = Drill::query()
            ->select('drills.id', 'drills.name', 'drills.description', 'drills.link')
            ->distinct()
            ->join("drill_tags", "drills.id", "=", "drill_tags.drill_id")
            ->join("tags", "tags.id", "=", "drill_tags.tag_id")
            ->where('drills.name', 'like', '%' . $search . '%')
        ;

        if (!empty($tagIds)) {
            $drillsQuery = $drillsQuery->whereIn("tags.id", $tagIds);
        }

        $drills = $drillsQuery
            ->orderBy('id')
            ->paginate(10);

        $drills->appends($request->all());

        return view('drills.index', compact('drills', 'search', 'tags', 'tagIds'))
            ->with(request()->input('page'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Drill  $drill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Drill $drill)
    {
        $drill->delete();

        return redirect()->route('drills.index')
                        ->with('success', 'Drill deleted successfully');
    }
}
