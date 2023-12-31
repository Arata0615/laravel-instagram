@extends('layouts.app')

@section('title', 'Following')

@section('content')
@include('users.profile.header')

<div style="margin-top: 100px;">
    @if($user->following->count() != 0)
    <div class="container col-4">
        <h3 class="h2 text-muted text-center">Following</h3>

        @foreach($user->following as $following)
        <div class="row align-items-center mt-3">
            <div class="col-auto">
                <a href="{{ route('profile.show', $following->following->id) }}">
                    @if($following->following->avatar)
                    <img src="{{ $following->following->avatar }}" alt="{{ $following->following->avatar }}" class="rounded-circle user-avatar">
                    @else
                    <i class="fa-solid fa-circle-user text-secondary user-icon"></i>
                    @endif
                </a>
            </div>
            <div class="col ps-0 text-truncate">
                <a href="{{ route('profile.show', $following->following->id) }}" class="text-decoration-none text-dark fw-bold small">{{ $following->following->name }}</a>
            </div>
            <div class="col-auto text-end">
                @if($following->following->id !== Auth::user()->id)
                    @if($following->following->isfollowed())
                    <form action="{{ route('follow.destroy', $following->following->id) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="border-0 bg-transparent p-0 text-secondary btn-sm">Following</button>
                    </form>
                    @else
                    <form action="{{ route('follow.store', $following->following->id) }}" method="post" class="d-inline">
                        @csrf
                        <button type="submit" class="border-0 bg-transparent p-0 text-primary btn-sm">Follow</button>
                    </form>
                    @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <h3 class="h2 text-muted text-center">No following yet.</h3>
    @endif
</div>
@endsection