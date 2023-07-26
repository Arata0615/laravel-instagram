@extends('layouts.app')

@section('title', 'Admin: Users')

@section('content')
<div class="row mb-3">
    <div class="col-3 ms-auto">
        <form action="{{ route('admin.users') }}">
            <input type="search" name="search" class="form-control form-control-sm" placeholder="Search for names" value="{{ $search }}">
        </form>
    </div>
</div>

<table class="table table-hover align-middle bg-white border text-secondary">
    <thead class="small table-success text-secondary">
        <tr>
            <th></th>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>CREATED AT</th>
            <th>STATUS</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if($all_users->isEmpty())
            <tr>
                <td colspan=6 class="lead text-muted text-center">No users match your search.</td>
            </tr>
        @else
            @foreach($all_users as $user)
            <tr>
                <td>
                    @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->avatar }}" class="rounded-circle d-block mx-auto admin-users-avatar">
                    @else
                    <i class="fa-solid fa-circle-user d-block text-center admin-users-icon"></i>
                    @endif
                </td>
                <td>
                    <a href="{{ route('profile.show' , $user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $user->name }}</a>
                </td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at }}</td>
                <td>
                    @if($user->trashed())
                    <i class="fa-regular fa-circle text-secondary"></i>&nbsp; Inactive
                    @else
                    <i class="fa-solid fa-circle text-success"></i>&nbsp; Active
                    @endif
                </td>
                <td>
                    @if(Auth::user()->id != $user->id)
                    <div class="dropdown">
                        <button class="btn btn-sm" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>

                        @if($user->trashed())
                        <div class="dropdown-menu">
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#activate-user-{{ $user->id }}">
                                <i class="fa-solid fa-user-check"></i> Activate {{ $user->name }}
                            </button>
                        </div>
                        @else
                        <div class="dropdown-menu">
                            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deactivate-user-{{ $user->id }}">
                                <i class="fa-solid fa-user-slash"></i> Deactivate {{ $user->name }}
                            </button>
                        </div>
                        @endif

                    </div>
                    @include('admin.users.modal.status')
                    @endif
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
{{ $all_users->appends(request()->query())->links() }}
</div>
@endsection