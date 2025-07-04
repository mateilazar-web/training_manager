<?php

namespace App\Http\Controllers;

use App\Models\DrillTag;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::query()->first()->paginate(10);

        $data = compact('tags');
        $data['page'] = request()->input('page');


        return response()->view('tags.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view("tags.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Tag::query()->create($request->all());

        return redirect()->route('tags.index')
                        ->with('success', 'Tag created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $drills = $tag->drillTags()
            ->getQuery()
            ->join("drills", "drills.id", "=", "drill_tags.drill_id")
            ->where("drill_tags.tag_id", "=", $tag->id)
            ->select('drills.id', 'drills.name', 'drills.description', 'drills.link')
            ->paginate(10);

        return response()->view('tags.show', compact('tag', 'drills'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return response()->view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required'
        ]);
   
        $tag->name = $request['name'];
        $tag->save();

        return redirect()->route('tags.show', $tag->id)
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Tag $tag)
    {
        DrillTag::query()->where("tag_id", $tag->id)->delete();
        $tag->delete();

        return redirect()->route('tags.index')
                        ->with('success', 'Tag deleted successfully');
    }
}
