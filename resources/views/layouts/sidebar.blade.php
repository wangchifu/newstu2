<aside id="sidebar" class="sidebar">    
    <ul class="sidebar-nav" id="sidebar-nav">               
      @auth
        @if(auth()->user()->school->group_admin==1)        
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
                <i class="bi bi-circle"></i><span>指定下所學校</span>
              </a>
            </li>
            <li>
              <a href="{{ route('group_log') }}">
                <i class="bi bi-circle"></i><span>重要操作記錄</span>
              </a>
            </li>
          </ul>
        </li>
        @endif
      @endauth
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav2" data-bs-toggle="collapse" href="#">
          <i class="bi bi-table"></i><span>關於本站</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav2" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="https://newstu.chc.edu.tw/chc_center_classv3" target="_blank">
              <i class="bi bi-circle"></i><span>舊版編班系統的連結</span>
            </a>
          </li>
          <li>
            <a href="{{ route('about') }}">
              <i class="bi bi-circle"></i><span>版權申明與系統功能</span>
            </a>
          </li>
          <li>
            <a href="https://newstu.chc.edu.tw/cloudschool_newstu.pdf" target="_blank">
              <i class="bi bi-circle"></i><span>校務系統的新生手冊</span>
            </a>
          </li>
          <li>
            <a href="{{ route('teach') }}">
              <i class="bi bi-circle"></i><span>系統使用教學之影片</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>

  </aside><!-- End Sidebar-->