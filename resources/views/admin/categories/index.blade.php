@extends('layouts.app')

@section('title', 'Admin: Categories')

@section('content')
<div class="row">
    <form action="{{ route('admin.categories.store') }}" method="post" class="row col-6 mb-4">
        @csrf
        <div class="col-7 pe-0">
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Add a category..." autofocus>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i></button>
        </div>
        @error('name')
            <p class="text-danger small">{{ $message }}</p>
        @enderror
    </form>
    <form action="{{ route('admin.categories') }}" class="col-2">
        <input type="search" name="search" class="form-control form-control-sm  mt-1 ms-4" placeholder="Search" value="{{ $search }}">
    </form>
</div>

<div class="row">
    <div class="col-8">
        @error('new_name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <table class="table table-hover align-middle bg-white border text-secondary table-sm text-center">
            <thead class="small table-warning text-secondary">
                <tr>
                    <th>#</th>
                    <th>NAME</th>
                    <th>COUNT</th>
                    <th>LAST UPDATED</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if($search && $all_categories->isEmpty())
                <tr>
                    <td colspan=6 class="text-muted text-center">No categories match your search.</td>
                </tr>
                @elseif(empty($search) && $all_categories->isNotEmpty())
                    @foreach($all_categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td class="text-dark">{{ $category->name }}</td>
                        <td>{{ $category->categoryPost->count() }}</td>
                        <td>{{ $category->updated_at }}</td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#update-category-{{ $category->id }}" title="Edit"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#destroy-category-{{ $category->id }}" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                    @include('admin.categories.modal.action')
                    @endforeach
                    <tr>
                        <td></td>
                        <td class="text-dark fw-bold">Uncategorized</td>
                        <td>{{ $uncategorized_count }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @elseif($search && $all_categories->isNotEmpty())
                    @foreach($all_categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td class="text-dark">{{ $category->name }}</td>
                        <td>{{ $category->categoryPost->count() }}</td>
                        <td>{{ $category->updated_at }}</td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#update-category-{{ $category->id }}" title="Edit"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#destroy-category-{{ $category->id }}" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                    @include('admin.categories.modal.action')
                    @endforeach
                @elseif($all_categories->isEmpty())
                <tr>
                    <td colspan=6 class="text-muted text-center">No categories yet.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-center">
{{ $all_categories->appends(request()->query())->links() }}
</div>
@endsection