@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
<style>
.col-4 {
    overflow-y: scroll;
}

.card-body {
    position: absolute;
    top: 65px;
}
</style>

<div class="row border shadow">
    <div class="col p-0">
        <img src="{{ $post->image }}" alt="{{ $post->image }}" class="w-100">
    </div>
    <div class="col-4 px-0 bg-white">
        <div class="card border-0">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <a href="{{ route('profile.show', $post->user->id) }}" class="text-secondary">
                            @if($post->user->avatar)
                            <img src="{{ $post->user->avatar }}" alt="{{ $post->user->avatar }}" class="rounded-circle user-avatar">
                            @else
                            <i class="fa-solid fa-circle-user user-icon"></i>
                            @endif
                        </a>
                    </div>
                    <div class="col ps-0">
                        <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark">{{ $post->user->name }}</a>
                    </div>
                    <div class="col-auto text-end">
                        <!-- If you are the owner of the post, you can Edit or Delete this post -->
                        @if(Auth::user()->id === $post->user->id)
                        <div class="dropdown">
                            <button class="btn btn-sm shadow-none" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item">
                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                </a>
                                <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete-post-{{ $post->id }}">
                                    <i class="fa-regular fa-trash-can"></i> Delete
                                </button>
                            </div>
                        </div>
                        @else
                        <!-- If you are not the owner of the post, show a Follow/Unfollow button. To be dissucussed soon -->
                            @if($post->user->isFollowed())
                            <form action="{{ route('follow.destroy', $post->user->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border-0 bg-transparent text-secondary p-0">Following</button>
                            </form>
                            @else
                            <form action="{{ route('follow.store', $post->user->id) }}" method="post" class="d-inline">
                                @csrf
                                <button type="submit" class="border-0 bg-transparent text-primary p-0">Follow</button>
                            </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body w-100">
                <div class="row align-items-center">
                    <div class="col-auto">
                        @if($post->isLiked())
                        <form action="{{ route('like.destroy', $post->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm shadow-none ps-0"><i class="fa-solid fa-heart text-danger"></i></button>
                            <button type="button" class="btn shadow-none ps-0" data-bs-toggle="modal" data-bs-target="#likes-modal">{{ $post->likes->count() }}</button>
                        </form>
                        @else
                        <form action="{{ route('like.store', $post->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-sm shadow-none ps-0"><i class="fa-regular fa-heart"></i></button>
                            <button type="button" class="btn shadow-none ps-0" data-bs-toggle="modal" data-bs-target="#likes-modal">{{ $post->likes->count() }}</button>
                        </form>
                        @endif
                        @include('users.posts.modal.likes')
                    </div>
                    <div class="col text-end">
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
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <strong>{{ $post->user->name }}</strong>
                        &nbsp;
                        <div class="d-inline fw-light">{{ $post->description }}</div>
                    </div>
                </div>

                <!-- Comments -->
                <div class="mt-4">
                    <form action="{{ route('comment.store', $post->id) }}" method="post">
                        @csrf
                        <div class="input-group">
                            <textarea name="comment_body{{ $post->id }}" rows="1" class="form-control form-control-sm" placeholder="Add a comment...">{{ old('comment_body'. $post->id) }}</textarea>
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Post</button>
                        </div>
                        @error('comment_body' . $post->id)
                        <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </form>

                    @if ($post->comments->isNotEmpty())
                    <ul class="list-group mt-2">
                        @foreach($post->comments as $comment)
                        <li class="list-group-item border-0 p-0 mb-2">
                        <a href="{{ route('profile.show', $comment->user->id) }}" class="text-decoration-none text-dark"><strong>{{ $comment->user->name }}</strong></a>
                            &nbsp;<p class="d-inline fw-light">{{ $comment->body }}</p>

                            <form action="{{ route('comment.destroy', $comment->id) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <span class="small text-muted">{{ date("D, M d Y", strtotime($comment->created_at)) }}</span>

                                @if ($comment->user->id === Auth::user()->id)
                                    &middot;
                                    <button type="submit" class="border-0 bg-transparent text-danger p-0 small">Delete</button>
                                @endif
                            </form>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-post-{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger"><i class="fa-solid fa-circle-exclamation"></i> Delete Post</h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this post?</p>
                <div class="mt-3">
                    <img src="{{ $post->image }}" alt="{{ $post->image }}" class="delete-post-img">
                    <p class="mt-1 text-muted">{{ $post->description }}</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('post.destroy', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection