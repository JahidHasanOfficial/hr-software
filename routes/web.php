<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\BranchController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\DesignationController;
use App\Http\Controllers\Backend\ShiftController;
use App\Http\Controllers\Backend\AttendanceController;
use App\Http\Controllers\Backend\HolidayController;
use App\Http\Controllers\Backend\WeeklyOffController;
use App\Http\Controllers\Backend\RosterController;
use App\Http\Controllers\Backend\AttendanceRequestController;
use App\Http\Controllers\Backend\AttendanceManualLogController;
use App\Http\Controllers\Backend\AttendanceReportController;
use App\Http\Controllers\Backend\LeaveController;
use App\Http\Controllers\Backend\LeaveTypeController;
use App\Http\Controllers\Backend\SalaryComponentController;
use App\Http\Controllers\Backend\EmployeeSalaryController;
use App\Http\Controllers\Backend\PayrollController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard')
        ->middleware('permission:dashboard access|dashboard.real_time_overview|dashboard.statistics_cards|dashboard.recent_employees');

    /**
     * User Management
     */
    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:user.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:user.create');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('users.store')->middleware('permission:user.store');
    Route::get('/admin/users/{user}/show', [UserController::class, 'show'])->name('users.show')->middleware('permission:user.show');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:user.edit');
    Route::put('/admin/users/{user}/update', [UserController::class, 'update'])->name('users.update')->middleware('permission:user.update');
    Route::delete('/admin/users/{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:user.destroy');

    /**
     * Role Management
     */
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:role.index');
    Route::get('/admin/roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:role.create');
    Route::post('/admin/roles/store', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:role.store');
    Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:role.edit');
    Route::put('/admin/roles/{role}/update', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:role.update');
    Route::delete('/admin/roles/{role}/destroy', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:role.destroy');

    /**
     * Organization (Companies)
     */
    Route::get('/admin/companies', [CompanyController::class, 'index'])->name('companies.index')->middleware('permission:company.index');
    Route::get('/admin/companies/create', [CompanyController::class, 'create'])->name('companies.create')->middleware('permission:company.create');
    Route::post('/admin/companies/store', [CompanyController::class, 'store'])->name('companies.store')->middleware('permission:company.store');
    Route::get('/admin/companies/{company}/show', [CompanyController::class, 'show'])->name('companies.show')->middleware('permission:company.show');
    Route::get('/admin/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit')->middleware('permission:company.edit');
    Route::put('/admin/companies/{company}/update', [CompanyController::class, 'update'])->name('companies.update')->middleware('permission:company.update');
    Route::delete('/admin/companies/{company}/destroy', [CompanyController::class, 'destroy'])->name('companies.destroy')->middleware('permission:company.destroy');

    /**
     * Branches
     */
    Route::get('/admin/branches', [BranchController::class, 'index'])->name('branches.index')->middleware('permission:branch.index');
    Route::get('/admin/branches/create', [BranchController::class, 'create'])->name('branches.create')->middleware('permission:branch.create');
    Route::post('/admin/branches/store', [BranchController::class, 'store'])->name('branches.store')->middleware('permission:branch.store');
    Route::get('/admin/branches/{branch}/edit', [BranchController::class, 'edit'])->name('branches.edit')->middleware('permission:branch.edit');
    Route::put('/admin/branches/{branch}/update', [BranchController::class, 'update'])->name('branches.update')->middleware('permission:branch.update');
    Route::delete('/admin/branches/{branch}/destroy', [BranchController::class, 'destroy'])->name('branches.destroy')->middleware('permission:branch.destroy');

    /**
     * Departments
     */
    Route::get('/admin/departments', [DepartmentController::class, 'index'])->name('departments.index')->middleware('permission:department.index');
    Route::get('/admin/departments/create', [DepartmentController::class, 'create'])->name('departments.create')->middleware('permission:department.create');
    Route::post('/admin/departments/store', [DepartmentController::class, 'store'])->name('departments.store')->middleware('permission:department.store');
    Route::get('/admin/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit')->middleware('permission:department.edit');
    Route::put('/admin/departments/{department}/update', [DepartmentController::class, 'update'])->name('departments.update')->middleware('permission:department.update');
    Route::delete('/admin/departments/{department}/destroy', [DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('permission:department.destroy');

    /**
     * Designations
     */
    Route::get('/admin/designations', [DesignationController::class, 'index'])->name('designations.index')->middleware('permission:designation.index');
    Route::get('/admin/designations/create', [DesignationController::class, 'create'])->name('designations.create')->middleware('permission:designation.create');
    Route::post('/admin/designations/store', [DesignationController::class, 'store'])->name('designations.store')->middleware('permission:designation.store');
    Route::get('/admin/designations/{designation}/edit', [DesignationController::class, 'edit'])->name('designations.edit')->middleware('permission:designation.edit');
    Route::put('/admin/designations/{designation}/update', [DesignationController::class, 'update'])->name('designations.update')->middleware('permission:designation.update');
    Route::delete('/admin/designations/{designation}/destroy', [DesignationController::class, 'destroy'])->name('designations.destroy')->middleware('permission:designation.destroy');

    /**
     * Attendance Tracking (Granular Permissions)
     */
    Route::post('/admin/attendances/check-in', [AttendanceController::class, 'checkIn'])->name('attendances.check_in')->middleware('permission:attendance.check_in');
    Route::post('/admin/attendances/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check_out')->middleware('permission:attendance.check_out');
    Route::get('/admin/attendances/my-logs', [AttendanceController::class, 'logs'])->name('attendances.logs')->middleware('permission:attendance.logs');

    /**
     * Attendance Management (Admin/HR only)
     */
    Route::middleware('permission:attendance management')->group(function() {
        // Attendance Corrections & Exports
        Route::post('/admin/attendances/manual-correction', [AttendanceController::class, 'storeManualCorrection'])->name('attendances.manual_correction')->middleware('permission:attendance.correction');
        Route::get('/admin/attendances/export', [AttendanceController::class, 'exportCsv'])->name('attendances.export')->middleware('permission:attendance.export');

        // Shift Management
        Route::get('/admin/shifts', [ShiftController::class, 'index'])->name('shifts.index')->middleware('permission:shift.index');
        Route::get('/admin/shifts/create', [ShiftController::class, 'create'])->name('shifts.create')->middleware('permission:shift.create');
        Route::post('/admin/shifts/store', [ShiftController::class, 'store'])->name('shifts.store')->middleware('permission:shift.store');
        Route::get('/admin/shifts/{shift}/edit', [ShiftController::class, 'edit'])->name('shifts.edit')->middleware('permission:shift.edit');
        Route::put('/admin/shifts/{shift}/update', [ShiftController::class, 'update'])->name('shifts.update')->middleware('permission:shift.update');
        Route::delete('/admin/shifts/{shift}/destroy', [ShiftController::class, 'destroy'])->name('shifts.destroy')->middleware('permission:shift.destroy');

        // Attendance Tracking Overview
        Route::get('/admin/attendances', [AttendanceController::class, 'index'])->name('attendances.index')->middleware('permission:attendance.index');

        // Professional Attendance Features
        Route::get('/admin/holidays', [HolidayController::class, 'index'])->name('holidays.index')->middleware('permission:holiday.index');
        Route::get('/admin/holidays/create', [HolidayController::class, 'create'])->name('holidays.create')->middleware('permission:holiday.create');
        Route::post('/admin/holidays/store', [HolidayController::class, 'store'])->name('holidays.store')->middleware('permission:holiday.store');
        Route::get('/admin/holidays/{holiday}/edit', [HolidayController::class, 'edit'])->name('holidays.edit')->middleware('permission:holiday.edit');
        Route::put('/admin/holidays/{holiday}/update', [HolidayController::class, 'update'])->name('holidays.update')->middleware('permission:holiday.update');
        Route::delete('/admin/holidays/{holiday}/destroy', [HolidayController::class, 'destroy'])->name('holidays.destroy')->middleware('permission:holiday.destroy');
        
        Route::get('/admin/weekly-offs', [WeeklyOffController::class, 'index'])->name('weekly-offs.index')->middleware('permission:weekly_off.index');
        Route::get('/admin/weekly-offs/create', [WeeklyOffController::class, 'create'])->name('weekly-offs.create')->middleware('permission:weekly_off.create');
        Route::post('/admin/weekly-offs/store', [WeeklyOffController::class, 'store'])->name('weekly-offs.store')->middleware('permission:weekly_off.store');
        Route::get('/admin/weekly-offs/{weeklyOff}/edit', [WeeklyOffController::class, 'edit'])->name('weekly-offs.edit')->middleware('permission:weekly_off.edit');
        Route::put('/admin/weekly-offs/{weeklyOff}/update', [WeeklyOffController::class, 'update'])->name('weekly-offs.update')->middleware('permission:weekly_off.update');
        Route::delete('/admin/weekly-offs/{weeklyOff}/destroy', [WeeklyOffController::class, 'destroy'])->name('weekly-offs.destroy')->middleware('permission:weekly_off.destroy');
        
        Route::get('/admin/rosters', [RosterController::class, 'index'])->name('rosters.index')->middleware('permission:roster.index');
        Route::get('/admin/rosters/create', [RosterController::class, 'create'])->name('rosters.create')->middleware('permission:roster.create');
        Route::post('/admin/rosters/store', [RosterController::class, 'store'])->name('rosters.store')->middleware('permission:roster.store');
        Route::get('/admin/rosters/{roster}/edit', [RosterController::class, 'edit'])->name('rosters.edit')->middleware('permission:roster.edit');
        Route::put('/admin/rosters/{roster}/update', [RosterController::class, 'update'])->name('rosters.update')->middleware('permission:roster.update');
        Route::delete('/admin/rosters/{roster}/destroy', [RosterController::class, 'destroy'])->name('rosters.destroy')->middleware('permission:roster.destroy');
        
        Route::get('/admin/attendance-requests', [AttendanceRequestController::class, 'index'])->name('attendance-requests.index')->middleware('permission:attendance_request.index');
        Route::get('/admin/attendance-manual-logs', [AttendanceManualLogController::class, 'index'])->name('attendance-manual-logs.index')->middleware('permission:attendance_manual_log.index');
    });

    // Leave Management
    Route::get('/admin/leave-types', [LeaveTypeController::class, 'index'])->name('leave-types.index')->middleware('permission:leave_type.index');
    Route::get('/admin/leave-types/create', [LeaveTypeController::class, 'create'])->name('leave-types.create')->middleware('permission:leave_type.create');
    Route::post('/admin/leave-types/store', [LeaveTypeController::class, 'store'])->name('leave-types.store')->middleware('permission:leave_type.store');
    Route::get('/admin/leave-types/{leaveType}/edit', [LeaveTypeController::class, 'edit'])->name('leave-types.edit')->middleware('permission:leave_type.edit');
    Route::put('/admin/leave-types/{leaveType}/update', [LeaveTypeController::class, 'update'])->name('leave-types.update')->middleware('permission:leave_type.update');
    Route::delete('/admin/leave-types/{leaveType}/destroy', [LeaveTypeController::class, 'destroy'])->name('leave-types.destroy')->middleware('permission:leave_type.destroy'); 
    
    Route::get('/admin/leaves', [LeaveController::class, 'index'])->name('leaves.index')->middleware('permission:leave.index');
    Route::get('/admin/leaves/create', [LeaveController::class, 'create'])->name('leaves.create')->middleware('permission:leave.create');
    Route::post('/admin/leaves/store', [LeaveController::class, 'store'])->name('leaves.store')->middleware('permission:leave.store');
    Route::get('/admin/leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit')->middleware('permission:leave.edit');
    Route::put('/admin/leaves/{leave}/update', [LeaveController::class, 'update'])->name('leaves.update')->middleware('permission:leave.update');
    Route::delete('/admin/leaves/{leave}/destroy', [LeaveController::class, 'destroy'])->name('leaves.destroy')->middleware('permission:leave.destroy');
    Route::post('/admin/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve')->middleware('permission:leave.approve');
    Route::post('/admin/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject')->middleware('permission:leave.reject');
    // Leave Balances       
    Route::get('/admin/leave-balances', [LeaveController::class, 'balances'])->name('leaves.balances')->middleware('permission:leave_type.index');
    // Attendance Reports
    Route::get('/admin/reports/attendance/summary', [AttendanceReportController::class, 'summary'])->name('reports.attendance.summary')->middleware('permission:attendance.report');
    Route::get('/admin/reports/attendance/detailed', [AttendanceReportController::class, 'detailed'])->name('reports.attendance.detailed')->middleware('permission:attendance.report');

    // Payroll Management
    Route::middleware('permission:payroll management')->group(function () {
        Route::get('/admin/salary-components', [SalaryComponentController::class, 'index'])->name('salary-components.index');
        Route::post('/admin/salary-components', [SalaryComponentController::class, 'store'])->name('salary-components.store');
        Route::put('/admin/salary-components/{component}', [SalaryComponentController::class, 'update'])->name('salary-components.update');
        Route::delete('/admin/salary-components/{component}', [SalaryComponentController::class, 'destroy'])->name('salary-components.destroy');
        Route::get('/admin/employee-salary', [EmployeeSalaryController::class, 'index'])->name('employee-salary.index');
        Route::get('/admin/employee-salary/{id}/edit', [EmployeeSalaryController::class, 'edit'])->name('employee-salary.edit');
        Route::put('/admin/employee-salary/{id}', [EmployeeSalaryController::class, 'update'])->name('employee-salary.update');

        // Payroll Processing Cycles
        Route::get('/admin/payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::get('/admin/payroll/create', [PayrollController::class, 'create'])->name('payroll.create');
        Route::get('/admin/payroll/preview', [PayrollController::class, 'preview'])->name('payroll.preview');
        Route::post('/admin/payroll/store', [PayrollController::class, 'store'])->name('payroll.store');
        Route::get('/admin/payroll/{id}', [PayrollController::class, 'show'])->name('payroll.show');
        Route::get('/admin/payslip/{id}', [PayrollController::class, 'payslip'])->name('payroll.payslip');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('permission:profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('permission:profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('permission:profile.destroy');
});

require __DIR__.'/auth.php';
