<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Page;
use App\Models\Group;

class PageController extends Controller
{


    public function list_api()
    {
        return  Page::orderBy('id')->get();
    }

    public function index()
    {
        $groups = Group::where('table_name', 'pages')->orderBy('name')->get(['id', 'name']);
        return view('pages.index', [
            'title' => "Pages",
            'page' => 'pages',
            'groups' => $groups,
            'locales' => getLocales()
        ]);
    }

    public function index_api(Request $request)
    {
        $pages = Page::orderBy('id', 'desc');

        if ($request->search) {
            $search = $request->search;
            $pages->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('body', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->group_id > 0) {
            $pages->where('group_id', $request->group_id);
        }

        $per_page = $request->per_page ?? setting('admin.per_page');
        return $pages->paginate($per_page);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $page = new Page();

        // translable attributes
        $locales = getLocales();
        foreach ($page->translatable as $attribute) {            
            foreach ($locales as $locale) {
                $value = $request->input($attribute)[$locale] ?? null;
                if ($value) $page->setTranslation($attribute, $locale, $value);
            }
        }
        $page->save();

        return response()->json([
            'page' => $page,
        ]);
    }

    // only show page use slug
    public function show(string $slug, Request $request)
    {
        $page = Page::where('slug', $slug)->first();
        return view('pages.show', [
            'page' => $page,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $page->update($request->only(['active', 'footer']));
        // translable attributes
        $locales = getLocales();
        foreach ($page->translatable as $attribute) {            
            foreach ($locales as $locale) {
                $value = $request->input($attribute)[$locale] ?? null;
                if ($value) $page->setTranslation($attribute, $locale, $value);
            }
        }
        $page->save();

        return response()->json([
            'page' => $page,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return response()->json([
            'status' => 'success',
        ]);
    }
}
