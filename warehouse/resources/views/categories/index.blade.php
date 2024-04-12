<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <title>Categories</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-warning">
    <div class="container-fluid">
      <a class="navbar-brand h1" href="{{ route('categories.index') }}">CRUDcategories</a>
      <div class="justify-end ">
        <div class="col ">
          <a class="btn btn-sm btn-success" href="{{ route('categories.create') }}">Add Post</a>
          <a class="btn btn-sm btn-success" href="{{ route('logout') }}">Log Out</a>
        </div>
      </div>
    </div>
  </nav>
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
                  // Reload the page after successful deletion
                  location.reload();
              }
          });
      }
  </script>


</body>
</html>
