<div class="sidebar" id="sidebar">
    <div class="p-4">
        <h4 class="text-white text-center mb-4">
            <i class="bi bi-heart-fill me-2"></i>
            {{ __('dashboard') }}
        </h4>
    </div>

    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2 me-2"></i>
            {{ __('home') }}
        </a>
        <!-- Users Information Dropdown -->
        @if(auth()->user()->hasPermission('view-users') || auth()->user()->hasPermission('view-roles') || Auth::user()->hasRole('admin'))
        <div class="nav-item">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'active' : '' }}"
               href="#" id="usersDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#usersCollapse" aria-expanded="{{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'true' : 'false' }}" aria-controls="usersCollapse">
                <i class="bi bi-person-lines-fill me-2"></i>
                {{ __('Users Information') }}
            </a>
            <div class="collapse {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'show' : '' }}" id="usersCollapse">
                <div class="nav flex-column ms-3">
                    @if(auth()->user()->hasPermission('view-users') || Auth::user()->hasRole('admin'))
                    <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}"
                       href="{{ route('users.index') }}">
                        <i class="bi bi-people me-2"></i>
                        {{ __('users') }}
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('view-roles') || Auth::user()->hasRole('admin'))
                    <a class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}"
                       href="{{ route('roles.index') }}">
                        <i class="bi bi-shield-check me-2"></i>
                        {{ __('roles') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->hasPermission('view-categories') || auth()->user()->hasPermission('view-products') || Auth::user()->hasRole('admin'))
        <div class="nav-item">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('categories.*') || request()->routeIs('products.*') ? 'active' : '' }}"
               href="#" id="usersDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#ProductsCollapse" aria-expanded="{{ request()->routeIs('categories.*') || request()->routeIs('products.*') ? 'true' : 'false' }}" aria-controls="usersCollapse">
                <i class="bi bi-tags me-2"></i>
                {{ __('Categories and Products') }}
            </a>
            <div class="collapse {{ request()->routeIs('categories.*') || request()->routeIs('products.*') ? 'show' : '' }}" id="ProductsCollapse">
                <div class="nav flex-column ms-3">
                    @if(auth()->user()->hasPermission('view-categories') || Auth::user()->hasRole('admin'))
                    <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}"
                       href="{{ route('categories.index') }}">
                        <i class="bi bi-tags me-2"></i>
                        {{ __('categories') }}
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('view-products') || Auth::user()->hasRole('admin'))
                    <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}"
                       href="{{ route('products.index') }}">
                        <i class="bi bi-shield-check me-2"></i>
                        {{ __('products') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasPermission('view-stores') || Auth::user()->hasRole('admin'))
        <a class="nav-link {{ request()->routeIs('stores.index') ? 'active' : '' }}" href="">
            <i class="bi bi-shop me-2"></i>
            {{ __('stores') }}
        </a>
        @endif
        @if(auth()->user()->hasPermission('view-reports') || Auth::user()->hasRole('admin'))
        <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="">
            <i class="bi bi-graph-up me-2"></i>
            {{ __('reports') }}
        </a>
        @endif
        @if(auth()->user()->hasPermission('view-settings') || Auth::user()->hasRole('admin'))
        <a class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}" href="">
            <i class="bi bi-gear me-2"></i>
            {{ __('settings') }}
        </a>
        @endif
    </nav>
</div>
