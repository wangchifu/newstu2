<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('index') }}" class="logo d-flex align-items-center">
        <img src="{{ asset('img/logo.png') }}" alt="">
        <span class="d-none d-lg-block">彰化新生編班2</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        @auth
          @if(auth()->user()->school->group_admin==1)
            <li class="nav-item dropdown">
              <a class="nav-link nav-icon" href="{{ route('start') }}">
                <i class="bi bi-play-fill"></i> 開始編班            
              </a>
            </li>
          @endif
        @endauth
        

        

        <li class="nav-item dropdown pe-3">
          @auth
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset('img/school.png') }}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->school->name }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">              
              <h6>{{ auth()->user()->name }}</h6>
              <span>{{ auth()->user()->title }}</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('upload_students') }}">
                <i class="bi bi-person"></i>
                <span>1.上傳名冊</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('student_type') }}">
                <i class="bi bi-gear"></i>
                <span>2.設定學生</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('test_start') }}">
                <i class="bi bi-sign-intersection-t"></i>
                <span>3.測試編班</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('school_log') }}">
                <i class="bi bi-eye"></i>
                <span>操作記錄</span>
              </a>
            </li>       
            @impersonating
              <li>
                <a class="dropdown-item d-flex align-items-center" href="#!" onclick="sw_confirm1('確定結束模擬？','{{ route('impersonate_leave') }}')">
                  <i class="bi bi-android"></i>                  
                  <span>結束模擬</span>
                </a>
              </li>      
            @endImpersonating   
            <li>
              <a class="dropdown-item d-flex align-items-center" href="#" onclick="sw_confirm2('確定登出嗎？','logout_form')">
                <i class="bi bi-box-arrow-right"></i>
                <span>登出</span>
              </a>
              <form action="{{ route('logout') }}" method="post" id="logout_form">
                @csrf
              </form>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->
        @endauth
        @guest
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="{{ route('glogin') }}">            
            <span class="d-none d-md-block ps-2">登入</span>
          </a>
        @endguest
      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->