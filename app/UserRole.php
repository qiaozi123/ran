<?php

namespace App;

use Bican\Roles\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Bican\Roles\Traits\HasRoleAndPermission;


class UserRole extends Model
{

    protected $table = 'role_user';


    public function is($slug)
    {
        $role = Role::where(['slug'=>$slug])->find();
    }
}
