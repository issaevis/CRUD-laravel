 @extends('layouts.app')

  @section('content')
  <div class="container mt-5">
    <div class="row">
      @foreach ($categories as $category)
        <div class="col-sm">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">{{ $category->title }}</h5>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-sm">
                  <a href="{{ route('categories.edit', $category->id) }}"
            class="btn btn-primary btn-sm">Edit</a>
                </div>
                <div class="col-sm">
                      <button type="submit" class="btn btn-danger btn-sm delete-btn" onclick="deleteCategory({{ $category->id }})">Delete</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  <!-- jQuery script -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- AJAX script -->
  <script>
      function deleteCategory(categoryId) {
          $.ajax({
              url: "{{ route('categories.destroy', ['category' => ':categoryId']) }}".replace(':categoryId', categoryId),
              type: "DELETE",
              data: {
                  "_token": "{{ csrf_token() }}"
              },
              success: function() {
                  location.reload();
              }
          });
      }
  </script>

@stop
