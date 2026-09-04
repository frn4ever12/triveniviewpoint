@php
    $currentTenant = auth()->user()?->tenant;
    $bsDate = \App\Helpers\NepaliDateHelper::convertToBS(\Carbon\Carbon::now());
@endphp

<div class="header bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg">
        <a id="nav-toggle" href="#" class="btn btn-light btn-sm me-3">
            <i data-feather="menu" class="nav-icon icon-xs"></i>
        </a>

        <!-- Restaurant Branding & Branch Selector -->
        <div class="d-flex align-items-center">
            <div class="me-4 d-none d-md-block">
                <h5 class="mb-0 fw-bold text-primary">Shree Foodies</h5>
                <small class="text-muted">Restaurant Management</small>
            </div>
            
            @if($currentTenant)
            <div class="dropdown">
                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i data-feather="map-pin" class="icon-xs me-1"></i>
                    {{ $currentTenant->name ?? 'Main Branch' }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Main Branch</a></li>
                    <li><a class="dropdown-item" href="#">Thamel Branch</a></li>
                    <li><a class="dropdown-item" href="#">Lazimpat Branch</a></li>
                </ul>
            </div>
            @endif
        </div>

        <!-- Right Side -->
        <ul class="navbar-nav navbar-right-wrap ms-auto d-flex align-items-center">
            
            <!-- BS & AD Dates -->
            <li class="nav-item me-3 d-none d-lg-block">
                <div class="text-end">
                    <small class="d-block fw-bold text-danger">{{ $bsDate ?? '2081-02-25' }} BS</small>
                    <small class="d-block text-muted">{{ \Carbon\Carbon::now()->format('Y-m-d') }} AD</small>
                </div>
            </li>

            <!-- Global Search -->
            <li class="nav-item me-3">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" class="form-control" placeholder="Search orders, items, customers...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i data-feather="search" class="icon-xs"></i>
                    </button>
                </div>
            </li>

            <!-- Restaurant Status -->
            <li class="nav-item me-3 d-none d-md-block">
                <span class="badge bg-success rounded-pill">
                    <i data-feather="check-circle" class="icon-xs me-1"></i> Open
                </span>
            </li>

            <!-- Notifications -->
            <li class="nav-item me-3 dropdown">
                <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">
                    <i data-feather="bell" class="icon-xs"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                        5
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                    <div class="px-3 py-2 border-bottom">
                        <h6 class="mb-0">Notifications</h6>
                    </div>
                    <div class="px-3 py-2">
                        <a href="#" class="text-decoration-none text-dark">
                            <div class="d-flex align-items-start mb-2">
                                <div class="bg-warning bg-opacity-10 rounded p-2 me-2">
                                    <i data-feather="alert-triangle" class="icon-xs text-warning"></i>
                                </div>
                                <div>
                                    <small class="fw-bold">Low Stock Alert</small>
                                    <p class="mb-0 text-muted small">Chicken Momo is running low</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="text-decoration-none text-dark">
                            <div class="d-flex align-items-start mb-2">
                                <div class="bg-info bg-opacity-10 rounded p-2 me-2">
                                    <i data-feather="truck" class="icon-xs text-info"></i>
                                </div>
                                <div>
                                    <small class="fw-bold">New Delivery Order</small>
                                    <p class="mb-0 text-muted small">Order #1025 assigned to driver</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="text-decoration-none text-dark">
                            <div class="d-flex align-items-start">
                                <div class="bg-success bg-opacity-10 rounded p-2 me-2">
                                    <i data-feather="check" class="icon-xs text-success"></i>
                                </div>
                                <div>
                                    <small class="fw-bold">Order Completed</small>
                                    <p class="mb-0 text-muted small">Table 08 order completed</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="px-3 py-2 border-top text-center">
                        <a href="#" class="text-primary small">View All Notifications</a>
                    </div>
                </div>
            </li>

            <!-- User Profile -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                    @if(Auth::user()->getFirstMediaUrl('profile_image'))
                        <img alt="avatar" src="{{ Auth::user()->getFirstMediaUrl('profile_image', 'thumb') }}"
                            class="rounded-circle me-2" style="width: 32px; height: 32px;" />
                    @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" 
                            style="width: 32px; height: 32px;">
                            <span class="text-white fw-bold small">{{ substr(auth()->user()->name, 0, 2) }}</span>
                        </div>
                    @endif
                    <span class="d-none d-md-block">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="px-4 pb-0 pt-2">
                        <h5 class="mb-1">Hello, {{ Auth::user()->name }}</h5>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                        <div class="dropdown-divider mt-3 mb-2"></div>
                    </div>
                    <ul class="list-unstyled">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="me-2 icon-xxs dropdown-item-icon" data-feather="user"></i>My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/">
                                <i class="me-2 icon-xxs dropdown-item-icon" data-feather="home"></i>Go to Homepage
                            </a>
                        </li>
                        <li>
                            <form action="{{route('logout')}}" method="POST">
                                @csrf
                                <button class="dropdown-item" type="submit">
                                    <i class="me-2 icon-xxs dropdown-item-icon" data-feather="power"></i>Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</div>
