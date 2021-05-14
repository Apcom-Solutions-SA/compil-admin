<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $note = Note::create($request->all() + ['user_id' => $request->user()->id]);

        return response()->json([
            'note' => $note,
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
        $note->update($request->all());

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
