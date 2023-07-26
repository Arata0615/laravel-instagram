<!-- Clickable post image -->
<div class="container p-0">
    <a href="{{ route('post.show', $post->id) }}">
        <img src="{{ $post->image }}" alt="{{ $post->image }}" class="w-100">
    </a>
</div>
<div class="card-body">
    <!-- heart button + no.of likes + categories -->
    <div class="row align-items-center">
        <div class="col-auto">
            @if($post->isLiked())
            <form action="{{ route('like.destroy', $post->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm shadow-none ps-0"><i class="fa-solid fa-heart text-danger"></i></button>
                <button type="button" class="btn shadow-none ps-0" data-bs-toggle="modal" data-bs-target="#likes-post-{{ $post->id }}">{{ $post->likes->count() }}</button>
            </form>
            @else
            <form action="{{ route('like.store', $post->id) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-sm shadow-none ps-0"><i class="fa-regular fa-heart"></i></button>
                <button type="button" class="btn shadow-none ps-0" data-bs-toggle="modal" data-bs-target="#likes-post-{{ $post->id }}">{{ $post->likes->count() }}</button>
            </form>
            @endif
            <!-- likes of the post -->
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
    <!-- owner + description -->
    <div class="row">
        <div class="col">
            <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark"><strong>{{ $post->user->name }}</strong></a>
            &nbsp;
            <p class="d-inline fw-light">{{ $post->description }}</p>
        </div>
    </div>

    <!-- Comments -->
    @include('users.posts.contents.comments')
</div>