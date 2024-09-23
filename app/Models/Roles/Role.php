<?php

namespace App\Models\Roles;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    public $table = 'roles';

    protected $fillable = ['name'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'role_id', 'id');
    }
}
