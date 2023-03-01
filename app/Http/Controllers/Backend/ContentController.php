<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Domain\ContentRequest;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.contents');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($path)
    {
        $content = Content::wherePath($path)->first();
        if (!$content) {
            return redirect()->route('frontend.index');
        }

        return view('frontend.content')
            ->with('content', $content);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.content.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContentRequest $request)
    {
        Content::create($request->validated());

        return redirect()->route('admin.content')
            ->withFlashSuccess(__('The content page was successfully created.'));

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $content
     */
    public function edit(Content $content)
    {
        return view('backend.content.edit')
            ->withContent($content);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Content $content
     */
    public function update(ContentRequest $request, Content $content)
    {
        $content->update($request->validated());
        return redirect()->route('admin.content')
            ->withFlashSuccess(__('The content page was successfully updated.'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Content $content
     * @return mixed
     */
    public function destroy(Content $content)
    {
        if ($content->system) {
            return redirect()->route('admin.content')
                ->withFlashInfo(__('This page required.'));
        }
        $content->delete();

        return redirect()->route('admin.content')
            ->withFlashSuccess(__('The domain was successfully deleted.'));
    }
}
