<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'company_id',
        'branch_id',
        'department_id',
        'designation_id',
        'shift_id',
        'status',
        'joining_date',
        'salary',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationships
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Get the effective shift for the user based on hierarchy:
     * 1. Roster (Specific date scheduling)
     * 2. Individual User Shift (Permanent)
     * 3. Department Shift
     * 4. Branch Shift
     */
    public function getEffectiveShift($date = null)
    {
        $date = $date ?: now()->toDateString();

        // 1. Check Roster
        $roster = $this->rosters()->where('date', $date)->first();
        if ($roster) {
            return $roster->shift;
        }

        // 2. Fallbacks
        if ($this->shift_id) {
            return $this->shift;
        }

        if ($this->department_id && $this->department && $this->department->shift_id) {
            return $this->department->shift;
        }

        if ($this->branch_id && $this->branch && $this->branch->shift_id) {
            return $this->branch->shift;
        }

        return null;
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function rosters()
    {
        return $this->hasMany(Roster::class);
    }

    public function attendanceRequests()
    {
        return $this->hasMany(AttendanceRequest::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
