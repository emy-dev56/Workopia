<header class="bg-blue-900 text-white p-4" x-data={open:false}>
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-3xl font-semibold">
            <a href="{{ url('/') }}">Workopia</a>
        </h1>
        <nav class="hidden md:flex items-center space-x-4">
            <x-nav-link link="/" :active="request()->is('/')">Home</x-nav-link>
            <x-nav-link link="/jobs" :active="request()->is('jobs')">All Jobs</x-nav-link>
            @auth
                <x-nav-link link="/bookmarks" :active="request()->is('bookmarks')">Saved Jobs</x-nav-link>
                <x-logout-button />
                @if (Auth::user()->avatar)
                    <a href="{{ route('dashboard') }}">
                        <img src="/storage/{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}"
                            class="rounded-full mb-4 m-auto w-10 h-10 object-fit-cover" />
                    </a>
                @else
                    <a href="{{ route('dashboard') }}">
                        <img src="/storage/avatars/default-avatar.png" alt="{{ Auth::user()->name }}"
                            class="rounded-full mb-4 m-auto w-10 h-10 object-fit-cover" />
                    </a>
                @endif
                <x-button-link link="/jobs/create" icon="edit">Create Job</x-button-link>
            @else
                <x-nav-link link="/login" :active="request()->is('login')">Login</x-nav-link>
                <x-nav-link link="/register" :active="request()->is('register')">Register</x-nav-link>
            @endauth
        </nav>
        <button @click="open = !open" @click.away="open=false" id="hamburger"
            class="text-white md:hidden flex items-center">
            <i class="fa fa-bars text-2xl"></i>
        </button>
    </div>
    <!-- Mobile Menu -->
    <nav id="mobile-menu" class="md:hidden bg-blue-900 text-white mt-5 pb-4 space-y-2" x-show="open">
        <x-nav-link link="/jobs" :active="request()->is('jobs')" mobile>All Jobs</x-nav-link>
        @auth
            <x-nav-link link="/bookmarks" :active="request()->is('bookmarks')" mobile>Saved Jobs</x-nav-link>
            <x-nav-link link="/dashboard" :active="request()->is('dashboard')" icon="gauge" mobile>Dashboard</x-nav-link>
            <x-logout-button />
            <div class="py-2"></div>
            
            <x-button-link link="/jobs/create" icon="edit" :block="true">Create Job</x-button-link>
        @else
            <x-nav-link link="/login" :active="request()->is('login')" mobile>Login</x-nav-link>
            <x-nav-link link="/register" :active="request()->is('register')" mobile>Register</x-nav-link>
        @endauth
    </nav>
</header>
