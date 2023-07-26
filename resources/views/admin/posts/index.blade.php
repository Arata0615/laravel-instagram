@extends('layouts.app')

@section('title', 'Admin: Posts')

@section('content')
<div class="row mb-3">
    <div class="col-3 ms-auto">
        <form action="{{ route('admin.posts') }}">
            <input type="search" name="search" class="form-control form-control-sm" placeholder="Search for posts" value="{{ $search }}">
        </form>
    </div>
</div>


<table class="table table-hover align-middle bg-white border text-secondary">
    <thead class="small table-primary text-secondary">
        <tr>
            <th></th>
            <th></th>
            <th>CATEGORY</th>
            <th>OWNER</th>
            <th>CREATED AT</th>
            <th>STATUS</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if($search && $all_posts->isEmpty())
        <tr>
            <td colspan=6 class="text-muted text-center">No posts match your search.</td>
        </tr>
        @elseif($all_posts->isNotEmpty())
            @foreach($all_posts as $post)
            <tr>
                <td class="text-end">{{ $post->id }}</td>
                <td>
                <a href="{{ route('post.show' , $post->id) }}"><img src="{{ $post->image }}" alt="{{ $post->image }}" class="d-block mx-auto admin-posts-img"></a>
                </td>
                <td>
                    @if($post->categoryPost->count() == 0)
                        <div class="badge bg-secondary text-wrap">
                            Uncategorized
                        </div>
                    @else
                        @foreach($post->categoryPost as $category_post)
                        <div class="badge bg-secondary bg-opacity-50 text-wrap">
                            {{ $category_post->category->name }}
                        </div>
                        @endforeach
                    @endif
                </td>
                <td>
                    <a href="{{ route('profile.show' , $post->user->id) }}" class="text-decoration-none text-dark">{{ $post->user->name }}</a>
                </td>
                <td >{{ $post->created_at }}</td>
                <td>
                    @if($post->trashed())
                    <i class="fa-solid fa-circle-minus"></i>&nbsp; Hidden
                    @else
                    <i class="fa-solid fa-circle text-primary"></i>&nbsp; Visible
                    @endif
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>

                        @if($post->trashed())
                        <div class="dropdown-menu">
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#unhide-post-{{ $post->id }}">
                                <i class="fa-solid fa-eye"></i> Unhide Post {{ $post->id }}
                            </button>
                        </div>
                        @else
                        <div class="dropdown-menu">
                            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#hide-post-{{ $post->id }}">
                                <i class="fa-solid fa-eye-slash text-danger"></i> Hide Post {{ $post->id }}
                            </button>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
            @include('admin.posts.modal.status')
            @endforeach
        @elseif($all_posts->isEmpty())
            <tr>
                <td colspan=6 class="text-muted text-center">No posts yet.</td>
            </tr>
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
{{ $all_posts->appends(request()->query())->links() }}
</div>
@endsection