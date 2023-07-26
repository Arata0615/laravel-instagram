@extends('layouts.app')

@section('title', 'Edit Profile')
    
@section('content')
<div class="row justify-content-center">
    <div class="col-8">
        @if(session('warning'))
            <div class="alert alert-danger" role="alert">
                {{ session('warning') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="bg-white shadow-sm p-5">
            <h3 class="text-muted fw-light">Update Profile</h3>
            <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="row mb-3">
                    <div class="col-4">
                        @if ($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->avatar }}" class="img-thumbnail rounded-circle profile-avatar">
                        @else
                        <i class="fa-solid fa-circle-user d-inline text-center text-secondary profile-icon"></i>
                        @endif
                    </div>
                    <div class="col-auto mt-5">
                        <input type="file" name="avatar" id="avatar" class="form-control">
                        <p class="text-muted mb-0 small">Acceptable formats:jpeg,jpg,png,gif only</p>
                        <p class="text-muted small">Max file size is 1048kB</p>
                        @error('avatar')
                        <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" autofocus>
                    @error('name')
                    <p class="text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
                    @error('email')
                    <p class="text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="intro" class="form-label fw-bold">Introduction</label>
                    <textarea name="intro" id="intro"  rows="5" class="form-control" placeholder="Describe yourself">{{ old('intro', $user->introduction) }}</textarea>
                    @error('intro')
                    <p class="text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning px-5">Save</button>
            </form>
        </div>
    </div>
</div>
<div class="row justify-content-center mt-5">
    <div class="col-8">
        <div class="bg-white shadow-sm p-5">
            <h3 class="text-muted fw-light">Update Password</h3>
            <form action="{{ route('profile.password') }}" method="post">
                @csrf
                @method('PATCH')
                
                <div class="my-3">
                    <label for="current_password" class="form-label fw-bold">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control">
                    @if(session('error_current_password'))
                       <p class="text-danger small">{{ session('error_current_password') }}</p>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label fw-bold">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                    <p class="text-muted small mb-0">Your password must be at least 8 characters and contain letters and numbers.</p>
                    @if(session('error_new_password'))
                       <p class="text-danger small">{{ session('error_new_password') }}</p>
                    @endif
                    @error('new_password')
                        <p class="text-danger small">{{ $message }}</p>
                    @enderror

                </div>
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-warning px-5">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection