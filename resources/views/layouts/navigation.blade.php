<nav class="navbar navbar-expand-lg navbar-dark bg-light shadow">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand text-dark" href="{{ route('dashboard') }}">
            {{-- Ganti dengan logo atau nama aplikasi --}}
            <img src="{{ asset ('images/logo fps-01.png') }}" alt="logo" width="35">
            {{ config('app.name', 'Laravel') }}
        </a>

        {{-- Toggle button untuk mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar content --}}
        <div class="collapse navbar-collapse mx-4" id="navbarSupportedContent">
            {{-- Left Side (Menu) --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <x-nav-link :active="request()->routeIs('dashboard')" href="{{ route('dashboard') }}">
                    Dashboard
                </x-nav-link>

                <x-nav-link :active="request()->routeIs('assets.index')" href="{{ route('assets.index') }}">
                    Daftar Asset
                </x-nav-link>

                @auth
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :active="request()->routeIs('admin.dashboard')" href="{{ route('admin.dashboard') }}">
                            Dashboard Admin
                        </x-nav-link>
                    @endif
                @endauth

                {{-- Tambahkan link lainnya di sini --}}
            </ul>

            {{-- Right Side (User dropdown) --}}
            <ul class="navbar-nav ms-auto">
                {{-- Auth Check --}}
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-black" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="">Profile</a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- Jika belum login --}}
                    <li class="nav-item">
                        <a class="nav-link text-black fw-bold" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
