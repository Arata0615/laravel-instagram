@if($suggested_users)
    <div class="row align-items-center mb-3">
        <div class="col">
            <p class="text-muted fw-bold m-0">Suggestion For You</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('suggested') }}" class="text-decoration-none fw-bold text-dark small">See all</a>
        </div>
    </div>
    @foreach(array_slice($suggested_users, 0, 10) as $user)
    <div class="row mb-3 align-items-center">
        <div class="col-auto">
            @if($user->avatar)
            <a href="{{ route('profile.show', $user->id) }}">
                <img src="{{ $user->avatar }}" alt="{{ $user->avatar }}" class="rounded-circle user-avatar">
            </a>
            @else
            <a href="{{ route('profile.show', $user->id) }}">
                <i class="fa-solid fa-circle-user text-secondary user-icon"></i>
            </a>
            @endif
        </div>
        <div class="col ps-0 text-truncate">
            <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark small">{{ $user->name }}</a>
        </div>
        <div class="col-auto">
            <form action="{{ route('follow.store', $user->id) }}" method="post" class='d-inline'>
                @csrf
                <button type="submit" class="border-0 bg-transparent text-primary p-0 btn-sm">Follow</button>
            </form>
        </div>
    </div>
    @endforeach
@endif