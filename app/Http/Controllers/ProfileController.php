<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{   
    const LOCAL_STORAGE_FOLDER = 'public/avatars/';
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show($id)
    {
        $user = $this->user->findOrFail($id);
        return view('users.profile.show')->with('user', $user);
    }

    public function followers($id)
    {
        $user = $this->user->findOrFail($id);
        return view('users.profile.followers')->with('user', $user);
    }

    public function following($id)
    {
        $user = $this->user->findOrFail($id);
        return view('users.profile.following')->with('user', $user);
    }

    public function edit()
    {
        $user = $this->user->findOrFail(Auth::user()->id);
        return view('users.profile.edit')->with('user', $user);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:50',
            'email' => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            'avatar' => 'mimes:jpg,png,jpeg,gif|max:1048',
            'intro' =>  'max:150'
        ]);
        
        $user = $this->user->findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->intro;
        
        // If the user uploads an avatar...
        if($request->avatar){
            // if the user has an avatar currently, delete it from the local storage.
            if($user->avatar){
                $this->deleteAvatar($user->avatar);
            }
            // Save the new avatar in the local storage.
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));;
        }

        $user->save();

        return redirect()->route('profile.show', Auth::user()->id);
    }

    public function updatePassword(Request $request)
    {   
        $user = $this->user->findOrFail(Auth::user()->id);

        if(!password_verify($request->current_password, $user->password)){
            return redirect()
                        ->back()
                        ->with('warning', 'Unable to change your password.')
                        ->with('error_current_password', "That's not your current password. Try again."); 
        };

        if(password_verify($request->new_password, $user->password)){
            return redirect()
                        ->back()
                        ->with('warning', 'Unable to change your password.')
                        ->with('error_new_password', 'New password cannot be the same as your current password. Try again.');
        }

        $request->validate([
            'new_password' => ['required', Password::min(8)->numbers()->letters(), 'confirmed',]
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    private function deleteAvatar($avatar_name)
    {
        $avatar_path = self::LOCAL_STORAGE_FOLDER . $avatar_name;
        // $avatar_path = "public/avatars/168294626.jpeg";

        if(Storage::disk('local')->exists($avatar_path)){
            Storage::disk('local')->delete($avatar_path);
        }
    }

    private function saveAvatar($request)
    {
        # Rename the image to the CURRENT TIME to avoid overwriting
        $avatar_name = time() . "." . $request->avatar->extension();
        //$image_name = '16823621234.jpeg';

        # Save the image inside storage/app/public/avatars/
        $request->avatar->storeAs(self::LOCAL_STORAGE_FOLDER, $avatar_name);

        return $avatar_name;
    }
}
