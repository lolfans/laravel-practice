<?php
/**
 * Created by PhpStorm.
 * User: cy
 * Date: 2019/12/2
 * Time: 11:36
 */
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Role;
use App\User;
use App\Permission;
class RbacController extends Controller{


    public function create()
    {
        $owner = new Role();
        $owner->name = 'owner';
        $owner->display_name = 'Project Owner';
        $owner->description = 'User is the owner of a given project';
        $owner->save();

        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'User Administrator';
        $admin->description = 'User is allowed to manage and edit other users';
        $admin->save();


    }

    public function addRoleToUser()
    {
        $user = User::where('name', '=', 'hhh')->first();

        $admin = Role::where('name','admin')->first();
        //调用EntrustUserTrait提供的attachRole方法
        $user->attachRole($admin); // 参数可以是Role对象，数组或id

        // 或者也可以使用Eloquent原生的方法
//        $user->roles()->attach($admin->id); //只需传递id即可

    }

    public function giveToUser()
    {
        $createPost = new Permission();
        $createPost->name = 'create-post';
        $createPost->display_name = 'Create Posts';
        $createPost->description = 'create new blog posts';
        $createPost->save();

        $editUser = new Permission();
        $editUser->name = 'edit-user';
        $editUser->display_name = 'Edit Users';
        $editUser->description = 'edit existing users';
        $editUser->save();

        $owner = Role::where('name','owner')->first();

        $owner->attachPermission($createPost);
        $owner->attachPermission($editUser);
    }

    //完成上述操作后，下面我们可以检查相应角色和权限：
    public function check(User $user)
    {
        $user->hasRole('owner'); // false
        $user->hasRole('admin'); // true
        $user->can('edit-user'); // true
        $user->can('create-post'); // true
    }
}

