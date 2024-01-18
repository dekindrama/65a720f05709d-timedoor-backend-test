<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <title>@yield('title')</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="p-5">
  <div class="flex gap-5 capitalize">
    <a href="{{ route('books.index') }}">book list</a>
    <a href="{{ route('books.famous-author') }}">famous author</a>
    <a href="{{ route('books.insert-rating') }}">Insert rating</a>
  </div>
  <hr class="mb-10 border border-black">
  @yield('content')
</body>
</html>
