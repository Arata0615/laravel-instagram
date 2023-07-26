<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{   
    private $post;
    private $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $requset)
    {   
        $all_posts = $this->post->latest()->get();
        $suggested_users = $this->getSuggestedUsers();
        shuffle($suggested_users);
        
        return view('users.home')
            ->with('all_posts', $all_posts)
            ->with('suggested_users', $suggested_users);
    }

    # Get the users that the Auth user is not following
    private function getSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];
        
        foreach($all_users as $user){
            if(!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }
        // Loop through all users. If you are NOT following the user, add it in the suggested users.

        return $suggested_users;
    }

    public function explore(Request $requset)
    {
        $all_users = $this->user->where('name', 'LIKE', '%'.$requset->search.'%')->paginate(10);

        return view('users.search')
                ->with('all_users', $all_users)
                ->with('search', $requset->search);
    }

    public function suggestedAllUsers()
    {
        $all_suggested_users = $this->getSuggestedUsers();

        return view('users.suggested')->with('all_suggested_users', $all_suggested_users);
    }
}
