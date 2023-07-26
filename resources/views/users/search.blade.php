@extends('layouts.app')

@section('title', 'Search for user')

@section('content')
    @if($all_users->count() == 0)
        <h3 class="text-muted text-center">No users match your search.</h3>
    @else
        <div class="container col-6 bg-white shadow-sm p-5">
            <p class="h5 text-muted mb-5 text-center">Search results for "<spam class="fw-bold">{{ $search }}</spam>"</p>

            @foreach($all_users as $user)
            <div class="row align-items-center mt-3">
                <div class="col-auto">
                    <a href="{{ route('profile.show', $user->id) }}">
                    @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->avatar }}" class="rounded-circle user-avatar">
                    @else
                    <i class="fa-solid fa-circle-user text-secondary user-icon"></i>
                    @endif
                    </a>
                </div>
                <div class="col ps-0 text-truncate">
                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold small">{{ $user->name }}</a>
                    <p class="text-muted small mb-1">{{ $user->email }}</p>
                </div>
                <div class="col-auto text-end">
                    @if($user->id !== Auth::user()->id)
                        @if($user->isfollowed())
                        <form action="{{ route('follow.destroy', $user->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="border-0 bg-transparent p-0 text-secondary btn-sm">Following</button>
                        </form>
                        @else
                        <form action="{{ route('follow.store', $user->id) }}" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="border-0 bg-transparent p-0 text-primary btn-sm">Follow</button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $all_users->appends(request()->query())->links() }}
        </div>
    @endif
@endsection