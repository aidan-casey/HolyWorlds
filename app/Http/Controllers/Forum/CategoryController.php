<?php
namespace App\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Events\Forum\UserViewingCategory;
use App\Events\Forum\UserViewingIndex;
use App\Models\Forum\Category;

class CategoryController extends BaseController
{
    /**
     * GET: Return an index of categories view (the forum index).
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::where("category_id", 0)->orderBy("weight", "asc");

        $categories = $categories->get()->filter(function ($category)
        {
            return empty( $category->permission ) || Auth::user() != null && Auth::user()->hasPermission( $category->permission );
        });

        event(new UserViewingIndex);

        return view('forum.category.index', compact('categories'));
    }

    /**
     * GET: Return a category view.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $category = Category::find( $id );

        if (is_null($category) || !$category->exists)
        {
            return view("errors.404");
        }

        if ( empty( $category->permission ) || Auth::user() != null && Auth::user()->hasPermission( $category->permission ) )
        {
            event(new UserViewingCategory($category));

            $categories = [];
            if (Gate::allows('moveCategories')) {
                $categories = Category::where("category_id", 0)->get();
            }

            return view('forum.category.show', compact('categories', 'category', 'threads'));
        }
    }

    /**
     * POST: Store a new category.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $category = $this->api('category.store')->parameters($request->all())->post();

        Forum::alert('success', 'categories.created');

        return redirect(Forum::route('category.show', $category));
    }

    /**
     * PATCH: Update a category.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $action = $request->input('action');

        $category = $this->api("category.{$action}", $request->route('category'))->parameters($request->all())->patch();

        Forum::alert('success', 'categories.updated', 1);

        return redirect(Forum::route('category.show', $category));
    }

    /**
     * DELETE: Delete a category.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $this->api('category.delete', $request->route('category'))->parameters($request->all())->delete();

        Forum::alert('success', 'categories.deleted', 1);

        return redirect(config('forum.routing.root'));
    }

    protected function translationFile()
    {
        return 'categories';
    }
}