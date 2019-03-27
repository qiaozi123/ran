<?php

namespace App;

use Bican\Roles\Models\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Bican\Roles\Traits\HasRoleAndPermission;


class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function role($userid, $slug) //验证用户是什么角色
    {
        $role = Role::where(['slug'=>$slug])->first();
        $res = UserRole::where(['role_id'=>$role->id,'user_id'=>$userid])->first();
        if (empty($res)){
            return false;
        }else{
            return true;
        }
    }

    public static function roledata($userid)
    {
        $data = UserRole::where(['user_id'=>$userid])
            ->join('users','user_id','users.id')
            ->join('roles','roles.id','role_user.role_id')
            ->select('roles.name')
            ->first();
        return $data;
    }


}
