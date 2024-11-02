<aside id="sidebar" class="sidebar">    
    <ul class="sidebar-nav" id="sidebar-nav">      
      @auth
        @if(auth()->user()->school->group_admin==1)
        <li class="nav-heading">編班中心學校</li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>編班作業</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('start') }}">
                <i class="bi bi-circle"></i><span>開始編班作業</span>
              </a>
            </li>
            <li>
              <a href="{{ route('assign_group_admin') }}">
                <i class="bi bi-circle"></i><span>指定下個學校</span>
              </a>
            </li>
            <li>
              <a href="">
                <i class="bi bi-circle"></i><span>log 記錄</span>
              </a>
            </li>
          </ul>
        </li>
        @endif
      @endauth
    </ul>

  </aside><!-- End Sidebar-->