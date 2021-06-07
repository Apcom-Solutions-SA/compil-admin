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
use Illuminate\Support\Collection;

// use Spatie\Tags\Tag;

class NoteController extends Controller
{
    // https://www.geeksforgeeks.org/how-to-encrypt-and-decrypt-a-php-string/
    protected $ciphering = "AES-128-CTR";
    protected $options = 0;

    public function test()
    {
        return SODIUM_LIBRARY_VERSION;
    }

    public function check_key(int $id, Request $request)
    {
        $note = Note::find($id);
        if (!$note) return response()->json([
            'message' => trans('front.object_not_found')
        ], 404);

        if (!$request->key) return response()->json([
            'message' => trans('front.decryption_key_is_required')
        ], 400);

        if ($request->key !== $note->key) return response()->json([
            'message' => trans('front.decryption_key_is_incorrect')
        ], 403);

        $content = $this->decrypt_content($note);
        return response()->json([
            'content' => collect($content)
        ]);
    }

    protected function decrypt_content(Note $note): Collection
    {
        $content = [];
        $locales = getLocales();
        foreach ($locales as $locale) {
            $encryption = $note->getTranslation('content', $locale);  // string
            if ($encryption) {
                $decryption = openssl_decrypt(
                    $encryption,
                    $this->ciphering,
                    $note->encryption_key,
                    $this->options,
                    $note->iv
                );
                if ($decryption) {
                    $content[$locale] = $decryption;
                }
            }
        }
        return collect($content);
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
        $note = Note::create(['user_id' => $request->user()->id]);
        // translable attributes
        $locales = getLocales();
        foreach ($note->translatable as $attribute) {
            if ($attribute == 'content' && $request->key && strlen($request->key) > 0) {
                // Use OpenSSl encryption method
                $iv_length = openssl_cipher_iv_length($this->ciphering);
                // Use random_bytes() function which gives randomly 16 digit values
                $encryption_iv = random_bytes($iv_length);
                $encryption_key = openssl_digest($request->key, 'MD5', TRUE);
                $note->key = $request->key;
                $note->encryption_key = $encryption_key;
                $note->iv = $encryption_iv;
            }
            foreach ($locales as $locale) {
                $value = $request->input($attribute)[$locale] ?? null;
                if ($value) {
                    if ($attribute == 'content' && $request->key && strlen($request->key) > 0) {
                        // encryption                        
                        $encryption = openssl_encrypt(
                            $value,
                            $this->ciphering,
                            $encryption_key,
                            $this->options,
                            $encryption_iv
                        );
                        if ($encryption) {
                            $value = $encryption;
                        }
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

    public function show(Request $request, string $reference)
    {
        $note = Note::with(['user'])->where('reference', $reference)->first();
        $data = [
            'note' => $note
        ];

        if ($request->with_key && $request->user()->id === $note->user_id && $note->key) {
            $data['key'] = $note->key;
            $data['content'] = $this->decrypt_content($note);
        }

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * The HTTP 403 Forbidden client error status response code indicates that the server understood the request but refuses to authorize it. This status is similar to 401 , but in this case, re-authenticating will make no difference.
     */
    public function update(Request $request, int $id)
    {
        $note = Note::find($id);
        if ($request->user()->id != $note->user_id) {
            return response()->json([
                'message' => __('front.permission_denied')
            ], 403);
        }
        // translable attributes
        $locales = getLocales();
        foreach ($note->translatable as $attribute) {
            if ($attribute == 'content' && $request->key && strlen($request->key) > 0) {
                // Use OpenSSl encryption method
                $iv_length = openssl_cipher_iv_length($this->ciphering);
                // Use random_bytes() function which gives randomly 16 digit values
                $encryption_iv = random_bytes($iv_length);
                $encryption_key = openssl_digest($request->key, 'MD5', TRUE);
                $note->key = $request->key;
                $note->encryption_key = $encryption_key;
                $note->iv = $encryption_iv;
            }
            foreach ($locales as $locale) {
                $value = $request->input($attribute)[$locale] ?? null;
                if ($value) {
                    if ($attribute == 'content' && $request->key && strlen($request->key) > 0) {
                        // encryption
                        $encryption = openssl_encrypt(
                            $value,
                            $this->ciphering,
                            $encryption_key,
                            $this->options,
                            $encryption_iv
                        );
                        if ($encryption) {
                            $value = $encryption;
                        }
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
    public function destroy(int $id)
    {
        $note = Note::find($id);
        $note->delete();
        return response()->json([
            'status' => 'success',
        ]);
    }
}
