<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostsController extends Controller
{   
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function index(Request $request)
    {
        $all_posts = $this->post->withTrashed()->latest()->paginate(3);

        if($request->search){
            $all_posts = $this->post->withTrashed()->where('description', 'LIKE', '%'.$request->search.'%')->paginate(3);
        }

        return view('admin.posts.index')
                ->with('all_posts', $all_posts)
                ->with('search', $request->search);
    }

    public function hide($id)
    {
        $this->post->destroy($id);
        return redirect()->back();
    }

    public function unhide($id)
    {
        $this->post->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }
}
