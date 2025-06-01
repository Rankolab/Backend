<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $primaryKey = "id";
    public $incrementing = true;
    protected $keyType = "int";

    protected $fillable = [
        "name",
        "email",
        "password",
        "role",
        "license_key",
        "license_status",
        "license_type",
        "license_start_date",
        "license_end_date",
        "dashboard_settings",
    ];

    protected $hidden = [
        "password",
        "remember_token",
    ];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
            "license_start_date" => "datetime",
            "license_end_date" => "datetime",
            "dashboard_settings" => "array",
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName("user_activity")
            ->setDescriptionForEvent(fn(string $eventName) => "User {$this->name} was {$eventName}");
    }

    // --- Relationships ---

    /**
     * Get the roles associated with the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, "role_user");
    }

    /**
     * Get the websites associated with the user.
     */
    public function websites()
    {
        // Assuming a Website model exists and has a user_id foreign key
        return $this->hasMany(Website::class);
    }

    /**
     * Get the licenses associated with the user.
     */
    public function licenses()
    {
        // Assuming a License model exists and has a user_id foreign key
        return $this->hasMany(License::class);
    }

    /**
     * Get the purchases associated with the user.
     */
    public function purchases()
    {
        // Assuming a Purchase model exists and has a user_id foreign key
        return $this->hasMany(Purchase::class);
    }

    // --- End Relationships ---

    // --- Role & Permission Helpers ---

    public function hasRole($roleName)
    {
        if (is_string($roleName)) {
            if ($this->role === $roleName) {
                return true;
            }
            return $this->roles()->where("name", $roleName)->exists();
        }
        return false;
    }

    public function hasPermission($permissionName)
    {
        if ($this->hasRole("superadmin") || $this->hasRole("admin")) {
            return true;
        }
        return $this->roles()->whereHas("permissions", function ($query) use ($permissionName) {
            $query->where("name", $permissionName);
        })->exists();
    }

    public function assignRole($roleName)
    {
        $role = Role::firstOrCreate(["name" => $roleName]);
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    public function syncRoles(array $roleIds)
    {
        $this->roles()->sync($roleIds);
    }

    // --- End Role & Permission Helpers ---

    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the affiliate record associated with the user.
     */
    public function affiliate()
    {
        return $this->hasOne(Affiliate::class);
    }
}
    /**
     * Get the external API keys associated with the user.
     */
    public function externalApiKeys()
    {
        return $this->hasMany(ExternalApiKey::class);
    }

    // --- End Relationships ---

