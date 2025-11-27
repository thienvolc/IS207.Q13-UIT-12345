<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','Admin - PinkCapy')</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"/>

  <!-- Admin CSS -->
  @vite(['resources/css/admin.css', 'resources/js/admin.js'])

  <style>
    :root { --accent:#f6244e; --muted:#6c757d; --bg:#f6f7fb; --card:#ffffff; }
  </style>
</head>

<body class="admin-body">

  <div class="d-flex admin-root">

    {{-- SIDEBAR --}}
    @include('admin.partials.sidebar')

    {{-- MAIN --}}
    <div class="admin-main flex-grow-1 d-flex flex-column">

      {{-- HEADER --}}
      @include('admin.partials.header')

      {{-- CONTENT --}}
      <main class="admin-content p-4">
          @yield('content')
      </main>

      {{-- FOOTER --}}
      @include('admin.partials.footer')

    </div>
  </div>

  @stack('scripts')
</body>
</html>
