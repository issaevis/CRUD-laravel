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

@yield('content')

</body>

</html>
