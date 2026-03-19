@extends('backend.layouts.app')

@section('title', 'Dashboard - Purple Admin')

@section('content')
          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>                 
              </span>
              Dashboard
            </h3>
            <nav aria-label="breadcrumb">
              <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                  <span></span>Overview
                  <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
              </ul>
            </nav>
          </div>
          <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                  <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Total Employees
                    <i class="mdi mdi-account-multiple mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5">{{ $totalUsers }}</h2>
                  <h6 class="card-text">Total active/inactive staff</h6>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                  
                  <h4 class="font-weight-normal mb-3">Account Roles
                    <i class="mdi mdi-shield-check mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5">{{ $totalRoles }}</h2>
                  <h6 class="card-text">Defined access levels</h6>
                </div>
              </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="{{ asset('backend/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                                    
                  <h4 class="font-weight-normal mb-3">Total Monthly Payroll
                    <i class="mdi mdi-currency-usd mdi-24px float-right"></i>
                  </h4>
                  <h2 class="mb-5">${{ number_format($totalSalary, 2) }}</h2>
                  <h6 class="card-text">Combined base salaries</h6>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Recent Employees</h4>
                  <div class="table-responsive">
                    <table class="table hover">
                      <thead>
                        <tr>
                          <th> Employee </th>
                          <th> Designation </th>
                          <th> Status </th>
                          <th> Joined </th>
                          <th> Email </th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($recentUsers as $user)
                        <tr>
                          <td>
                            @if($user->image)
                                <img src="{{ asset('storage/' . $user->image) }}" class="mr-2" alt="image">
                            @else
                                <img src="{{ asset('backend/images/faces/face1.jpg') }}" class="mr-2" alt="image">
                            @endif
                            {{ $user->name }}
                          </td>
                          <td> {{ $user->designation ?? 'N/A' }} </td>
                          <td>
                            <label class="badge {{ $user->status == 'active' ? 'badge-gradient-success' : 'badge-gradient-danger' }}">
                                {{ strtoupper($user->status) }}
                            </label>
                          </td>
                          <td> {{ $user->joining_date ?? 'N/A' }} </td>
                          <td> {{ $user->email }} </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection
