<aside id="sidebar" class="sidebar">    
    <ul class="sidebar-nav" id="sidebar-nav">
      @auth
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>學校動作</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('upload_students') }}">
              <i class="bi bi-circle"></i><span>上傳老師及學生名單</span>
            </a>
          </li>
          <li>
            <a href="{{ route('student_type') }}">
              <i class="bi bi-circle"></i><span>設定學生編班類別</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->
      @endauth
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
                <i class="bi bi-circle"></i><span>開始編班</span>
              </a>
            </li>
            <li>
              <a href="{{ route('assign_group_admin') }}">
                <i class="bi bi-circle"></i><span>指定下次「編班中心」學校</span>
              </a>
            </li>
            <li>
              <a href="">
                <i class="bi bi-circle"></i><span>「編班中心」學校 log</span>
              </a>
            </li>
          </ul>
        </li>
        @endif
      @endauth
    </ul>

  </aside><!-- End Sidebar-->