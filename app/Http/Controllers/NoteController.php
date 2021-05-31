<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\Note;
use App\Filters\NoteFilters;
use App\Models\UserRelation;
use App\Models\UserSetting;
// use Spatie\Tags\Tag;

class NoteController extends Controller
{
    public function encrypt()
    {
        return SODIUM_LIBRARY_VERSION;
    }


    public function index()
    {
        $groups = Group::where('table_name', 'notes')->orderBy('name')->get(['id', 'name']);
        return view('notes.index', [
            'title' => "Notes",
            'page' => 'notes',
            'groups' => $groups,
        ]);
    }

    public function index_api(Request $request,  NoteFilters $filters)
    {
        $notes = Note::orderBy('updated_at', 'desc')->filter($filters);
        $per_note = $request->per_note ?? setting('admin.per_page');
        return $notes->paginate($per_note);
    }

    /** 
     * Afficher les dernières notes en fonction de (dernière modification) plus récents
     */
    public function index_user(Request $request, NoteFilters $filters)
    {
        $notes = Note::orderBy('updated_at', 'desc')->filter($filters);

        // filter blocked
        $user = $request->user();
        $blocked = UserRelation::where([
            'subject_id' => $user->id,
            'block' => 1
        ])->pluck('object_id');
        $notes->whereNotIn('user_id', $blocked);

        // for search, filter according to user setting
        if ($request->search) {
            $user_setting = UserSetting::where('user_id', $user->id)->first();
            if ($user_setting?->set_min) {
                $min = $user_setting->min;
                // Todo filter authors 
            }
        }

        if ($request->hide_others) {
            $notes->where('user_id', $user->id);
        }

        $per_note = $request->per_note ?? setting('site.per_page');
        return $notes->paginate($per_note);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $note = Note::create($request->only(['introduction', 'key']) + ['user_id' => $request->user()->id]);
        // translable attributes
        $locales = getLocales();
        foreach ($note->translatable as $attribute) {
            foreach ($locales as $locale) {
                $value = $request->input($attribute)[$locale] ?? null;
                if ($value) {
                    if ($attribute == 'content' && $request->key && strlen($request->key) > 0) {
                        // Generating an encryption key and a nonce
                        $key   = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES); // 256 bit
                        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES); // 24 bytes
                        $value = sodium_crypto_secretbox($value, $nonce, $key);
                        $note->encryption_key = $key; 
                        $note->nonce = $nonce; 
                    }
                    $note->setTranslation($attribute, $locale, $value);
                }
            }
        }
        $note->save();

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
        return response()->json([
            'note' => $note
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * The HTTP 403 Forbidden client error status response code indicates that the server understood the request but refuses to authorize it. This status is similar to 401 , but in this case, re-authenticating will make no difference.
     */
    public function update(Request $request, Note $note)
    {
        if ($request->user()->id != $note->user_id) {
            return response()->json([
                'message' => __('front.permission_denied')
            ], 403);
        }
        $note->update($request->only(['introduction']));

        // translable attributes
        $locales = getLocales();
        foreach ($note->translatable as $attribute) {
            foreach ($locales as $locale) {
                $value = $request->input($attribute)[$locale] ?? null;
                if ($value) {
                    if ($attribute == 'content' && $request->key && strlen($request->key) > 0) {
                        // TODO encrypt
                    }
                    $note->setTranslation($attribute, $locale, $value);
                }
            }
        }
        $note->save();

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
