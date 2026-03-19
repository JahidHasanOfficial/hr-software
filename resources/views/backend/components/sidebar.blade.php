      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
              <div class="nav-profile-image">
                @if(Auth::user()->image)
                    <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="profile">
                @else
                    <img src="{{ asset('backend/images/faces/face1.jpg') }}" alt="profile">
                @endif
                <span class="login-status online"></span> <!--change to offline or busy as needed-->              
              </div>
              <div class="nav-profile-text d-flex flex-column">
                <span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
                <span class="text-secondary text-small">{{ Auth::user()->getRoleNames()->first() }}</span>
              </div>
              <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
              <span class="menu-title">Dashboard</span>
              <i class="mdi mdi-home menu-icon"></i>
            </a>
          </li>
          @can('organization management')
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#org-management" aria-expanded="false" aria-controls="org-management">
              <span class="menu-title">Organization</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-office-building menu-icon"></i>
            </a>
            <div class="collapse" id="org-management">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('companies.index') }}">Companies</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('branches.index') }}">Branches</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('departments.index') }}">Departments</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('designations.index') }}">Designations</a></li>
              </ul>
            </div>
          </li>
          @endcan

          @can('employee management')
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#user-management" aria-expanded="false" aria-controls="user-management">
              <span class="menu-title">Employee Management</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-account-multiple menu-icon"></i>
            </a>
            <div class="collapse" id="user-management">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('users.index') }}">Employee List</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('users.create') }}">Add New Employee</a></li>
              </ul>
            </div>
          </li>
          @endcan

          @if(auth()->user()->can('attendance.index') || auth()->user()->can('shift.index') || auth()->user()->can('attendance.logs'))
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#attendance-management" aria-expanded="false" aria-controls="attendance-management">
              <span class="menu-title">Attendance & Tracking</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-calendar-clock menu-icon"></i>
            </a>
            <div class="collapse" id="attendance-management">
              <ul class="nav flex-column sub-menu">
                @can('attendance.index')
                <li class="nav-item"> <a class="nav-link" href="{{ route('attendances.index') }}">Attendance Logs</a></li>
                @endcan
                @can('shift.index')
                <li class="nav-item"> <a class="nav-link" href="{{ route('shifts.index') }}">Shift Management</a></li>
                @endcan
                @can('attendance.logs')
                <li class="nav-item"> <a class="nav-link" href="{{ route('attendances.logs') }}">My Attendance</a></li>
                @endcan
              </ul>
            </div>
          </li>
          @endif

          @can('role permission management')
          <li class="nav-item">
            <a class="nav-link" href="{{ route('roles.index') }}">
              <span class="menu-title">Roles & Permissions</span>
              <i class="mdi mdi-shield-account menu-icon"></i>
            </a>
          </li>
          @endcan

          @can('payroll management')
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="menu-title">Payroll</span>
              <i class="mdi mdi-currency-usd menu-icon"></i>
            </a>
          </li>
          @endcan

          @can('leave management')
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="menu-title">Leave Applications</span>
              <i class="mdi mdi-airplane-takeoff menu-icon"></i>
            </a>
          </li>
          @endcan

        </ul>
      </nav>
