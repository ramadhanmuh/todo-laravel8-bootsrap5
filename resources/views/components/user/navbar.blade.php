<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('user.home') }}">
            <img src="{{ url('assets/images/logo.png') }}" alt="logo" height="40">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ $navbarActive === 'home' ? 'active' : '' }}" href="{{ route('user.home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $navbarActive === 'tasks' ? 'active' : '' }}" href="{{ route('user.tasks.index') }}">Tugas</a>
                </li>
            </ul>
            <div class="mt-2 mt-lg-0">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ request()->get('userAuth')->username }}
                </a>
                <ul class="dropdown-menu dropdown-menu-start dropdown-menu-lg-end">
                    <li><a class="dropdown-item {{ $navbarActive === 'profile' ? 'active' : '' }}" href="{{ route('user.profile.index') }}">Profil</a></li>
                    <li><a class="dropdown-item {{ $navbarActive === 'change-password' ? 'active' : '' }}" href="{{ route('user.change-password.edit') }}">Ubah Kata Sandi</a></li>
                    <li>
                        <form class="dropdown-item" action="{{ route('user.logout') }}" method="POST">
                            @csrf
                            <button class="btn p-0 w-100 text-start" type="submit">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>