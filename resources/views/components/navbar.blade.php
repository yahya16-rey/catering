<nav class="navbar navbar-expand-lg" style="background-color: rgba(0, 0, 0, 0.603); display: flex; position: fixed; right: 0; left: 0; top: 0; z-index: 1;">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-light ms-3" href="/" style="color: #ffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
      Dinda<span style="color: #b6895b;">Catering</span>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-3 mb-lg-0">
        <li class="nav-item me-3">
          <a class="nav-link active text-light" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item me-3">
          <a class="nav-link text-light" href="/categories">Daftar Menu</a>
        </li>
        <li class="nav-item me-3">
          <a class="nav-link text-light" href="/about">Tentang Kami</a>
        </li>
        

       {{-- Jika yang login adalah customer --}}
@if(auth()->guard('customers')->check())
    <li class="nav-item dropdown me-3">
        <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::guard('customers')->user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="#">Dashboard Customer</a></li>
            <li>
                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button class="dropdown-item" type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </li>

{{-- Jika yang login adalah admin --}}
@elseif(auth()->check())
    <li class="nav-item dropdown me-3">
        <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard Admin</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item" type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </li>

{{-- Kalau tidak ada yang login --}}
@else
    <li class="nav-item me-2">
        <a class="btn btn-outline-primary" href="{{ route('customer.login') }}">Login</a>
    </li>
@endif

      </ul>
    </div>
  </div>
</nav>

<style>
  .btn-custom {
    background-color: white;
    border: 2px solid #8e6e46;
    color: #8e6e46;
  }

  .btn-custom:hover {
    background-color: #8e6e46;
    color: white;
    border-color: #8e6e46;
  }

  .nav-link:hover {
    color: #b6895b !important;
  }

  .btn-outline-danger:hover,
  .btn-outline-success:hover {
    color: #b6895b !important;
    border-color: #b6895b !important;
  }
</style>
