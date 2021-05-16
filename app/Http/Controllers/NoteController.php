<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Group;
use App\Models\Note;

class NoteController extends Controller
{
    public function index()
    {
        $groups = Group::where('table_name', 'notes')->orderBy('name')->get(['id', 'name']);
        return view('notes.index', [
            'title' => "Notes",
            'page' => 'notes',
            'groups' => $groups,
        ]);
    }

    public function index_api(Request $request)
    {
        $notes = Note::orderBy('id', 'desc');

        if ($request->search) {
            $search = $request->search;
            $notes->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('content', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->group_id > 0) {
            $notes->where('group_id', $request->group_id);
        }

        $per_note = $request->per_note ?? setting('admin.per_page');
        return $notes->paginate($per_note);
    }

    public function index_user(Request $request)
    {
        $user = $request->user();
        $notes = Note::orderBy('id', 'desc')->where('user_id', $user->id);

        $per_note = $request->per_note ?? setting('admin.per_page');
        return $notes->paginate($per_note);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            //   'group_id' => 'nullable|exists:groups,id',
        ]);

        $note = Note::create($request->only(['title', 'introduction', 'content']) + ['user_id' => $request->user()->id]);

        if ($request->tags){
            $note->attachTags($request->tags);
        }

        // reference
        // y: A two digit representation of a year
        // m: Numeric representation of a month, with leading zeros, 01 through 12
        $number = Note::whereRaw('YEAR(created_at) = ?', [date('Y')])->count();
        $reference = date('y.') . sprintf('%03d', $number + 1);   // 4 digits with padding 0
        $note->reference = $reference;
        $note->update();

        return response()->json([
            'note' => $note,
        ]);
    }

    public function show(Request $request, int $id)
    {
        $note = Note::with(['user'])->find($id);
        $tags = $note->tags; 
        return response()->json([
            'note' => $note,
            'tags' => $tags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {        
        $note->update($request->only(['title', 'introduction', 'content']));
        if ($request->tags){            
            $note->syncTags($request->tags);
        }
        return response()->json([
            'note' => $note,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json([
            'status' => 'success',
        ]);
    }
}
