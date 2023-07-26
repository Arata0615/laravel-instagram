@extends('layouts.app')

@section('title', 'Search for user')

@section('content')
    @if(count($all_suggested_users) == 0)
        <h3 class="text-muted text-center">No users match your search.</h3>
    @else
        <div class="container col-7 bg-white shadow-sm p-5">
            <p class="h5 mb-5 text-center">suggested</p>

            @foreach($all_suggested_users as $user)
            <div class="row align-items-center mt-3">
                <div class="col-auto">
                    <a href="{{ route('profile.show', $user->id) }}">
                    @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->avatar }}" class="rounded-circle overview-avatar">
                    @else
                    <i class="fa-solid fa-circle-user text-secondary overview-icon"></i>
                    @endif
                    </a>
                </div>
                <div class="col ps-0 text-truncate">
                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold small">{{ $user->name }}</a>
                    <p class="text-muted small mb-0">{{ $user->email }}</p>
                    @if($user->isFollowing())
                        <p class="text-muted small mb-0">Follows you</p>
                    @elseif($user->followers->count() == 0)
                        <p class="text-muted small mb-0">No followers yet</p>
                    @elseif($user->followers->count() > 0)
                        <p class="text-muted small mb-0">Followed by {{ $user->followers->count() }} {{ $user->followers->count() == 1 ? 'user' : 'users' }}</p>
                    @endif
                </div>
                <div class="col-auto text-end">
                    <form action="{{ route('follow.store', $user->id) }}" method="post" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary border-0">Follow</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
@endsection