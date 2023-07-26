<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Post;

class CategoriesController extends Controller
{   
    private $category;
    private $post;

    public function __construct(Category $category, Post $post)
    {
        $this->category = $category;
        $this->post = $post;
    }

    public function index(Request $request)
    {
        $all_categories = $this->category->orderBy('updated_at', 'desc')->paginate(10);
        $all_posts = $this->post->get();
        $uncategorized_count = 0;

        if($request->search){
            $all_categories = $this->category->where('name', 'LIKE', '%'.$request->search.'%')->orderBy('updated_at', 'desc')->paginate(10);
        }

        // Check if the post is existing inthe category_post table. if not, meaning the post is UNCATEGORIZED.
        foreach($all_posts as $post){
            if($post->categoryPost()->count() == 0){
                $uncategorized_count++;
            }
        }

        return view('admin.categories.index')
                ->with('all_categories', $all_categories)
                ->with('uncategorized_count', $uncategorized_count)
                ->with('search', $request->search);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:50|unique:categories,name'
        ]);

        $this->category->name = ucwords(strtolower($request->name));
        $this->category->save();

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {   
        $request->validate([
            'new_name' => 'required|min:1|max:50|unique:categories,name,' . $id
        ]);

        $category = $this->category->findOrFail($id);
        $category->name = ucwords(strtolower($request->new_name));

        $category->save();

        return redirect()->back();
    }

    public function destroy($id)
    {
        $this->category->destroy($id);
        return redirect()->back();
    }
}
