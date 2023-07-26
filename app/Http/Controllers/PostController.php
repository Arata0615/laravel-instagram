<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Post;
use App\Models\Category;

class PostController extends Controller
{   
    const LOCAL_STORAGE_FOLDER = 'public/images/';
    private $post;
    private $category;

    public function __construct(Post $post, Category $category){
        $this->post = $post;
        $this->category = $category;
    }

    public function create()
    {
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    public function store(Request $request)
    {   
        #validate all from data
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'required|mimes:jpg,png,jpeg,gif|max:1048'
        ]);

        # Save the post
        $this->post->user_id = Auth::user()->id;
        $this->post->description = $request->description;
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->save();

        # Save the category to the category_post pivot table
        foreach ($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
        }
        $this->post->categoryPost()->createMany($category_post);

        # Go back to homepage
        return redirect()->route('index');
    }

    // private function saveImage($request)
    // {
    //     # Rename the image to the CURRENT TIME to avoid overwriting
    //     $image_name = time() . "." . $request->image->extension();
    //     //$image_name = '16823621234.jpeg';

    //     # Save the image inside storage/app/public/images/
    //     $request->image->storeAs(self::LOCAL_STORAGE_FOLDER, $image_name);

    //     $this->post->image = 

    //     return $image_name;
    // }

    public function show($id)
    {
        $post = $this->post->findOrFail($id);
        return view('users.posts.show')->with('post', $post);
    }

    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        if ($post->user_id !== Auth::user()->id){
            return redirect()->route('index');
        }

        $all_categories = $this->category->all();

        # Get all the category ids of this post. Save in an array
        $selected_categories = [];
        foreach($post->categoryPost as $category_post){
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit')
                    ->with('post', $post)
                    ->with('all_categories', $all_categories)
                    ->with('selected_categories', $selected_categories);
    }

    public function update(Request $request, $id)
    {   
        # 1. Validate the data from the form
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'mimes:jpg,png,jpeg,gif|max:1048'
        ]);

        # 2. Update the post
        $post = $this->post->findOrFail($id);
        $post->description = $request->description;

        // if there is a new image...
        if ($request->image){
            # Delete the previous image from the local storage
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));

            # Move the new image to the local storage
            // $post->image = $this->saveImage($request);
        }

        $post->save();

        # 3. Delete all recors from category_post related to this post
        $post->categoryPost()->delete();
        // DELETE FROM category_post WHERE post_id = $post->idate

        # 4.Save the new categories to category_post table
        foreach ($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
        }
        $post->categoryPost()->createMany($category_post);

        return redirect()->route('post.show', $id);
    }

    // private function deleteImage($image_name)
    // {
    //     $image_path = self::LOCAL_STORAGE_FOLDER . $image_name;
    //     // $image_path = 'public/images/1651749056.jpg;'

    //     # If the image is existing, delete
    //     if (Storage::disk('local')->exists($image_path)){
    //         Storage::disk('local')->delete($image_path);
    //     }
    // }

    public function destroy($id)
    {   
        $post = $this->post->findOrFail($id);
        $this->deleteImage($post->image);
        // $this->deleteImage(162826432.jpeg)
        $post->forceDelete();
        // $this->post->destroy($id);
        // $post->destroy($id);
        
        return redirect()->route('index');
    }
}
