<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the companies this user belongs to.
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)->withPivot('role_id')->withTimestamps();
    }

    /**
     * Get the roles this user has across different companies.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_user')->withPivot('company_id')->withTimestamps();
    }

    /**
     * Get the employee record for this user.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get the activity logs for this user.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the role for a specific company.
     */
    public function getRoleForCompany(Company $company): ?Role
    {
        $companyUser = $this->companies()->where('company_id', $company->id)->first();
        return $companyUser ? Role::find($companyUser->pivot->role_id) : null;
    }

    /**
     * Check if user has a specific permission in a company.
     */
    public function hasPermissionInCompany(string $permission, int $companyId): bool
    {
        $companyUser = $this->companies()->where('company_id', $companyId)->first();
        if (!$companyUser) {
            return false;
        }

        $role = Role::find($companyUser->pivot->role_id);
        return $role ? $role->hasPermission($permission) : false;
    }

    /**
     * Check if user belongs to a specific company.
     */
    public function belongsToCompany(Company $company): bool
    {
        return $this->companies()->where('company_id', $company->id)->exists();
    }
}
