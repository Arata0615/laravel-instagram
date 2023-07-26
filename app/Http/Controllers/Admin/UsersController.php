<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $all_users = $this->user->withTrashed()->latest()->paginate(10);

        if($request->search){
            $all_users = $this->user->withTrashed()->where('name', 'LIKE', '%'.$request->search.'%')->latest()->paginate(10);
        }

        return view('admin.users.index')
                ->with('all_users', $all_users)
                ->with('search', $request->search);
        // withTrashed() - include the soft deleted users in the query result
    }

    public function deactivate($id)
    {
        $this->user->destroy($id);
        return redirect()->back();
    }

    public function activate($id)
    {
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
        // onlyTrashed() - retrieves soft deletd users only; not neccesary; to narrow down the choices
        // restore() - to "un-delete" a soft deleted model
    }
}
