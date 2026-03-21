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
          <li class="nav-item {{ request()->routeIs(['companies.*', 'branches.*', 'departments.*', 'designations.*']) ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#org-management" aria-expanded="{{ request()->routeIs(['companies.*', 'branches.*', 'departments.*', 'designations.*']) ? 'true' : 'false' }}" aria-controls="org-management">
              <span class="menu-title">Organization</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-office-building menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs(['companies.*', 'branches.*', 'departments.*', 'designations.*']) ? 'show' : '' }}" id="org-management">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">Companies</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}" href="{{ route('branches.index') }}">Branches</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">Departments</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('designations.*') ? 'active' : '' }}" href="{{ route('designations.index') }}">Designations</a></li>
              </ul>
            </div>
          </li>
          @endcan

          @can('employee management')
          <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#user-management" aria-expanded="{{ request()->routeIs('users.*') ? 'true' : 'false' }}" aria-controls="user-management">
              <span class="menu-title">Employee Management</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-account-multiple menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('users.*') ? 'show' : '' }}" id="user-management">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">Employee List</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}" href="{{ route('users.create') }}">Add New Employee</a></li>
              </ul>
            </div>
          </li>
          @endcan

          @if(auth()->user()->can('attendance.index') || auth()->user()->can('shift.index') || auth()->user()->can('attendance.logs'))
          <li class="nav-item {{ request()->routeIs(['attendances.*', 'attendance-requests.*', 'rosters.*', 'holidays.*', 'weekly-offs.*', 'shifts.*']) ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#attendance-management" aria-expanded="{{ request()->routeIs(['attendances.*', 'attendance-requests.*', 'rosters.*', 'holidays.*', 'weekly-offs.*', 'shifts.*']) ? 'true' : 'false' }}" aria-controls="attendance-management">
              <span class="menu-title">Attendance & Tracking</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-calendar-clock menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs(['attendances.*', 'attendance-requests.*', 'rosters.*', 'holidays.*', 'weekly-offs.*', 'shifts.*']) ? 'show' : '' }}" id="attendance-management">
              <ul class="nav flex-column sub-menu">
                @can('attendance.index')
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('attendances.index') ? 'active' : '' }}" href="{{ route('attendances.index') }}">Attendance Logs</a></li>
                @endcan
                
                @can('attendance_request.index')
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('attendance-requests.index') ? 'active' : '' }}" href="{{ route('attendance-requests.index') }}">Attendance Requests</a></li>
                @endcan

                @can('attendance management')
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('rosters.*') ? 'active' : '' }}" href="{{ route('rosters.index') }}">Rosters / Scheduling</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('holidays.*') ? 'active' : '' }}" href="{{ route('holidays.index') }}">Holidays</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('weekly-offs.*') ? 'active' : '' }}" href="{{ route('weekly-offs.index') }}">Weekly Offs</a></li>
                @endcan

                @can('shift.index')
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('shifts.*') ? 'active' : '' }}" href="{{ route('shifts.index') }}">Shift Management</a></li>
                @endcan
                @can('attendance.logs')
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('attendances.logs') ? 'active' : '' }}" href="{{ route('attendances.logs') }}">My Attendance</a></li>
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

          @if(auth()->user()->can('leave.index') || auth()->user()->can('leave_type.index'))
          <li class="nav-item {{ request()->routeIs(['leaves.*', 'leave-types.*']) ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#leave-menu" aria-expanded="{{ request()->routeIs(['leaves.*', 'leave-types.*']) ? 'true' : 'false' }}" aria-controls="leave-menu">
              <span class="menu-title">Leave Management</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-calendar-text menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs(['leaves.*', 'leave-types.*']) ? 'show' : '' }}" id="leave-menu">
              <ul class="nav flex-column sub-menu">
                @can('leave.index')
                <li class="nav-item"> 
                  <a class="nav-link {{ request()->routeIs('leaves.*') && !request()->has('type') ? 'active' : '' }}" href="{{ route('leaves.index', ['type' => 'personal']) }}">My Leaves</a>
                </li>
                @if(auth()->user()->can('leave.approve'))
                <li class="nav-item"> 
                  <a class="nav-link {{ request()->routeIs('leaves.index') && !request()->has('type') ? 'active' : '' }}" href="{{ route('leaves.index') }}">Manage Applications</a>
                </li>
                @endif
                @endcan
                @can('leave_type.index')
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('leave-types.*') ? 'active' : '' }}" href="{{ route('leave-types.index') }}">Leave Types</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('leaves.balances') ? 'active' : '' }}" href="{{ route('leaves.balances') }}">Global Balances</a></li>
                @endcan
              </ul>
            </div>
          </li>
          @endif

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

          @can('attendance.report')
          <li class="nav-item {{ request()->routeIs('reports.attendance.*') ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#report-menu" aria-expanded="{{ request()->routeIs('reports.attendance.*') ? 'true' : 'false' }}" aria-controls="report-menu">
              <span class="menu-title">Attendance Reports</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-chart-areaspline menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('reports.attendance.*') ? 'show' : '' }}" id="report-menu">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('reports.attendance.summary') ? 'active' : '' }}" href="{{ route('reports.attendance.summary') }}">Summary Report</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('reports.attendance.detailed') ? 'active' : '' }}" href="{{ route('reports.attendance.detailed') }}">Detailed Report</a></li>
              </ul>
            </div>
          </li>
          @endcan

          @can('payroll management')
          <li class="nav-item {{ request()->routeIs(['salary-components.*', 'employee-salary.*']) ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#payroll-menu" aria-expanded="{{ request()->routeIs(['salary-components.*', 'employee-salary.*']) ? 'true' : 'false' }}" aria-controls="payroll-menu">
              <span class="menu-title">Payroll Management</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-finance menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs(['salary-components.*', 'employee-salary.*']) ? 'show' : '' }}" id="payroll-menu">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('salary-components.*') ? 'active' : '' }}" href="{{ route('salary-components.index') }}">Salary Components</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('employee-salary.*') ? 'active' : '' }}" href="{{ route('employee-salary.index') }}">Salary Setup</a></li>
              </ul>
            </div>
          </li>
          @endcan

          {{-- Recruitment ATS --}}
          <li class="nav-item-divider mt-3"></li>
          <li class="nav-item {{ request()->routeIs('recruitment.*') ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#recruitment-menu" aria-expanded="{{ request()->routeIs('recruitment.*') ? 'true' : 'false' }}" aria-controls="recruitment-menu">
              <span class="menu-title">Recruitment (ATS)</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-account-search menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('recruitment.*') ? 'show' : '' }}" id="recruitment-menu">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('recruitment.job-requisitions.*') ? 'active' : '' }}" href="{{ route('recruitment.job-requisitions.index') }}">Requisitions</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('recruitment.job-posts.*') ? 'active' : '' }}" href="{{ route('recruitment.job-posts.index') }}">Job Posts</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('recruitment.candidates.index') || request()->routeIs('recruitment.candidates.show') || request()->routeIs('recruitment.candidates.edit') ? 'active' : '' }}" href="{{ route('recruitment.candidates.index') }}">Candidates</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('recruitment.candidates.kanban') ? 'active' : '' }}" href="{{ route('recruitment.candidates.kanban') }}">Pipeline (Kanban)</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('recruitment.interviews.*') ? 'active' : '' }}" href="{{ route('recruitment.interviews.index') }}">Interviews</a></li>
                <li class="nav-item"> <a class="nav-link {{ request()->routeIs('recruitment.offers.*') ? 'active' : '' }}" href="{{ route('recruitment.offers.index') }}">Offers</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
