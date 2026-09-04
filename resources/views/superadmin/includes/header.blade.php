<div class="header">
  <!-- navbar -->
  <nav class="navbar-classic navbar navbar-expand-lg">
    <a id="nav-toggle" href="#"><i
        data-feather="menu"
        class="nav-icon me-2 icon-xs"></i></a>
  
    <!--Navbar nav -->
    <ul class="navbar-nav navbar-right-wrap ms-auto d-flex align-items-center nav-top-wrap">
      <li class="me-5">
        <small class="fw-bold text-secondary d-none d-md-block" id="nepali-today-bs"></small>
        <small class="fw-bold d-none d-md-block" style="color:#dc2626;">{{ \Carbon\Carbon::now()->format('Y-m-d, l') }}  </small>
      </li>
      <!-- List -->
      <li class="dropdown ms-2">
        <a class="rounded-circle" href="#" role="button" id="dropdownUser"
          data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         
          <div class="avatar avatar-md avatar-indicators avatar-online">
          @if(Auth::user()->getFirstMediaUrl('profile_image'))
              <img alt="avatar" src="{{ Auth::user()->getFirstMediaUrl('profile_image', 'thumb') }}"
                  class="rounded-circle" />
          @else
              <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                  style="width: 40px; height: 40px;">
                  <i data-feather="user" class="text-muted" style="width: 20px; height: 20px;"></i>
              </div>
          @endif
          </div>
          
        </a>
        <div class="dropdown-menu dropdown-menu-end"
          aria-labelledby="dropdownUser">
          <div class="px-4 pb-0 pt-2">

            <div class="lh-1">
              <h5 class="mb-1">Hello, {{ Auth::user()->name }}</h5>
              <p class="mb-0 text-muted small">Superadmin</p>
            </div>
            <div class=" dropdown-divider mt-3 mb-2"></div>
          </div>

          <ul class="list-unstyled">
            <li>
              <a class="dropdown-item" href="{{ route('profile.edit') }}">
                <i class="me-2 icon-xxs dropdown-item-icon" data-feather="user"></i>My
                Profile
              </a>
            </li>
            
            <li>
              <a class="dropdown-item" href="/">
                <i class="me-2 icon-xxs dropdown-item-icon"
                  data-feather="home"></i>Go to Homepage
              </a>
            </li>
            <li>
              <form action="{{route('logout')}}" method="POST">
                @csrf
                <button class="dropdown-item" type="submit">
                  <i class="me-2 icon-xxs dropdown-item-icon"
                    data-feather="power"></i>Sign Out
                </button>
              </form>
            </li>
          </ul>

        </div>
      </li>
    </ul>
  </nav>
</div>
