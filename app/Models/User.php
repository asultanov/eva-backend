<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Guides\Diagnosis;
use App\Models\Guides\Therapy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'patronymic',
        'full_name',
        'birthday',
        'phone',
        'email',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }

    public function therapy()
    {
        return $this->belongsTo(Therapy::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function phone(): Attribute
    {
        return new Attribute(
            get: fn($value) => phoneFormatter($value, true),
            set: fn($value) => phoneFormatter($value),
        );
    }

    /**
     * @param string|array $permission
     * @param bool $require
     * @return bool
     * require передается тогда - когда первый аргумент массив
     * если require = true то все права перечислнные в массиве должны быть у пользователя, если нет всех прав - то
     * если условие верно - вернем истину, иначе ложь
     */
    public function canDo(string|array $permission, bool $require = FALSE)
    {
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $permName = $this->canDo($permName);
                if ($permName && !$require) {
                    return TRUE;
                } else if (!$permName && $require) {
                    return FALSE;
                }
            }
            return $require;
        } else {
            self::$currentAtelier = currentAtelierId();
            foreach ($this->currentRoles as $role) {
                foreach ($role->perms as $perm) {
                    if (str($perm->name)->is($permission)) {
                        return TRUE;
                    }
                }
            }
        }
    }

    /**
     * @param $name string|array ['role1', 'role2']
     * @param  $require bool
     * @return bool
     */
    public function hasRole($name, $require = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$require) {
                    return true;
                } elseif (!$hasRole && $require) {
                    return false;
                }
            }
            return $require;
        } else {
            foreach ($this->roles as $role) {
                if ($role->name == $name) {
                    return true;
                }
            }
        }
        return false;
    }
}
