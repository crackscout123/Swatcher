<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Roles: superadmin | admin | editor | viewer
 */
class Role extends Model
{
    use HasFactory;

    public const SUPERADMIN = 'superadmin';
    public const ADMIN      = 'admin';
    public const EDITOR     = 'editor';
    public const VIEWER     = 'viewer';

    protected $fillable = ['name', 'label', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
