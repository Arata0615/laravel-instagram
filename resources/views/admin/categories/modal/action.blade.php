<!-- Update -->
<div class="modal fade" id="update-category-{{ $category->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-warning">
            <div class="modal-header border-warning">
                <h5 class="modal-title text-dark">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Category
                </h5>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    
                    <input type="text" name="new_name" class="form-control mb-4" placeholder="Category name" autofocus value="{{ $category->name }}">
                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm text">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="destroy-category-{{ $category->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger">
                    <i class="fa-solid fa-trash-can"></i> Delete Category
                </h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $category->name }}</strong> category?</p>
                <p class="text-muted">This action will affect all the posts under this category. Posts without a category will fall under Uncategorized.</p>
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm text">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>