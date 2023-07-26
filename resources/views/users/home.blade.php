@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="row gx-5">
    <div class="col-8">
        <!-- PROBLEM: Already Following someone that dont have not any post -->
        @if(Auth::user()->posts->count() > 0 || Auth::user()->following->count() > 0)
            @if($all_posts->isNotEmpty())
                @foreach($all_posts as $post)
                    @if($post->user->isFollowed() || $post->user->id == Auth::user()->id)
                    <div class="card mb-4">
                        @include('users.posts.contents.title')
                        @include('users.posts.contents.body')
                    </div>
                    @endif
                @endforeach
            @else
            <!-- If the site dose'nt have any posts yet. -->
            <div class="text-center">
                <h2>Share Photos</h2>
                <p class="text-muted">When you share photos, they'll apper on your profile.</p>
                <a href="{{ route('post.create') }}" class="text-decoration-none">Share your first photo</a>
            </div>
            @endif
        @else
        <!-- if you are not following anyone, OR dont have any post -->
        <div class="text-center">
            <h2>Share Photos</h2>
            <p class="text-muted">When you share photos, they'll apper on your profile.</p>
            <a href="{{ route('post.create') }}" class="text-decoration-none">Share your first photo</a>
        </div>
        @endif
    </div>
    <div class="col-4">
        <!-- Profile Overview -->
        <div class="row bg-white align-items-center shadow-sm rounded py-1 mb-5">
            <div class="col-auto">
                <a href="{{ route('profile.show', Auth::user()->id) }}" class="text-secondary">
                    @if(Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->avatar }}" class="rounded-circle overview-avatar">
                    @else
                    <i class="fa-solid fa-circle-user overview-icon"></i>
                    @endif
                </a>
            </div>
            <div class="col ps-0 mt-3">
                <a href="{{ route('profile.show', Auth::user()->id) }}" class="text-decoration-none text-dark">{{ Auth::user()->name }}</a>
                <p class="text-muted small">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <!-- Suggestions -->
        @include('users.posts.contents.suggestions')
    </div>
</div>
@endsection
