<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Carbon;
use DB;
use Illuminate\Support\Collection;

class Access extends Controller
{
    /**
     *
     */
    
    function __construct()
    {
        $this->middleware(['role_or_permission:администратор|система']);
    }

    public function index()
    {
        $permission = Permission::orderBy('id','DESC')->select('id', 'name')->get();

        $roles = DB::table('roles')
            ->leftjoin('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
            ->leftjoin('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('roles.id as role_id', 'roles.name as roles_name', 'permissions.id as permissions_id', 'permissions.name as permissions_name')
            ->get()->toArray();


        $roles_sort = $this->sort_array($roles);

        return response()->json(array('permissions' =>  $permission, 'roles' =>  $roles_sort));
    }

    public function permission_destroy(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        DB::table('permissions')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function role_destroy(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $role = Role::find($input['id']);
        $role->syncPermissions();

        DB::table('roles')
        ->where('id', $input['id'])
        ->delete();

        return response()->json(array('status' => 'ok'));
    }

    public function get_info_permissions(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $permission = DB::table('permissions')
        ->where('id', $input['id'])
        ->select('id','name')
        ->first();

        return response()->json(array('name' => $permission->name));
    }

    public function save_permission(Request $request)
    {
        $validator = $this->validate($request, [
        	'id' => ['required', 'integer'],
            'name' => ['required', 'unique:permissions,name,'.$request->id, 'max:255'],
        ]);

        $input = $request->all();

        $permission = DB::table('permissions')
              ->where('id', $input['id'])
              ->update(['name' => $input['name']]);

        return response()->json(array('ok' => 'ok'));
    }

    public function add_permission(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => ['required', 'unique:permissions', 'max:255'],
        ]);

        $input = $request->all();

        $id = DB::table('permissions')->insertGetId(
		    ['name' => $input['name'], 'guard_name' => 'api', 'created_at' => now(), 'updated_at' => now()],
		);

        return response()->json(array('ok' => 'ok', 'new_id' => $id));
    }

    public function save_role(Request $request)
    {
        $validator = $this->validate($request, [
        	'role_id' => ['required', 'integer'],
            'role_name' => ['required', 'unique:roles,name,'.$request->role_id, 'max:255'],
            'role_permissions_selects' => ['required', 'Array'],
        ]);

        $input = $request->all();

        $role = Role::find($input['role_id']);
        $role->name = $input['role_name'];
        $role->save();

        $role->syncPermissions($input['role_permissions_selects']);

        $role_permissions = DB::table('roles')
            ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('permissions.id as permissions_id', 'permissions.name as permissions_name')
            ->where('roles.id', $input['role_id'])
            ->get();

        return response()->json(array('ok' => 'ok', 'role_permissions' => $role_permissions));

    }

    public function add_role(Request $request)
    {
        $validator = $this->validate($request, [
            'role_name' => ['required', 'unique:roles,name,'.$request->role_id, 'max:255'],
            'role_permissions_selects' => ['required', 'Array'],
        ]);

        $input = $request->all();

        $id = DB::table('roles')->insertGetId(
		    ['name' => $input['role_name'], 'guard_name' => 'api', 'created_at' => now(), 'updated_at' => now()],
		);


        $role = Role::find($id);
        $role->syncPermissions($input['role_permissions_selects']);

        $role = DB::table('roles')
            ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('roles.id as role_id', 'roles.name as roles_name', 'permissions.id as permissions_id', 'permissions.name as permissions_name')
            ->where('roles.id', $id)
            ->get()->toArray();

        $role_sort = $this->sort_array($role);

        return response()->json(array('ok' => 'ok', 'role_permissions' => $role_sort));

    }


    public function get_info_role(Request $request)
    {
        $validator = $this->validate($request, [
            'id' => ['required', 'integer'],
        ]);

        $input = $request->all();

        $role = DB::table('roles')
        ->where('id', $input['id'])
        ->select('id','name')
        ->first();

        $permissions = DB::table('permissions')->select('id','name')->get();

        $permissions_selects = $roles = DB::table('role_has_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('permissions.id as permissions_id')
            ->where('role_has_permissions.role_id', $input['id'])
            ->get();

        return response()->json(array('name' => $role->name, 'permissions' => $permissions, 'permissions_selects' => $permissions_selects));
    }

    public function get_roles_list(Request $request)
    {
        $roles = DB::table('roles')
            ->select('roles.id as role_id', 'roles.name as role_name')
            ->get();

        return response()->json($roles);
    }

    public function sort_array($array)
	{
		if(!$array)
			return false; 

	    $return = array();
        $temp_val = '';
        $temp_role = -1;

	    foreach($array as $key=>$val) {

            if($val->role_id == $temp_val){

            	$return[$temp_role]['role_permissions'][] = array('permission_id' => $val->permissions_id, 'permissions_name' => $val->permissions_name);
               
            } else {
            	$temp_val = $val->role_id;
            	$temp_role++;
            	
                $return[$temp_role] = array('role_id' => $val->role_id, 'role_name' => $val->roles_name, 'role_permissions' => array(array('permission_id' => $val->permissions_id, 'permissions_name' => $val->permissions_name)));
            }

	    }

	    return $return;
	}
}