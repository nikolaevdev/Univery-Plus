<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Groups;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Arr;

class Users extends Controller
{
	
	/**
     * Ограничение прав
     *
     */
    function __construct()
    {
        $this->middleware('role:администратор');
    }
	
	public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        return view('admin.users.index',compact('data'))
        ->with('i', ($request->input('page', 1) - 1) * 5);
	}
	
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
		
        return view('admin.users.create',compact('roles'));
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
        $messages = [
            'personal_score.unique' => 'Пользователь с таким лицевым счетом уже создан.',
        ];

        $this->validate($request, [
            'firstname' => ['required', 'string', 'max:50'],
            'surname' => ['required', 'string', 'max:50'],
            'middlename' => ['string', 'max:50'],
            'sex' => ['integer', 'max:2'],
            'personal_score' => ['sometimes', 'nullable', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => 'array'
        ], $messages);

        $input = $request->all();
		
        $input['password'] = Hash::make($input['password']);

        $input['full_name'] = $input['firstname']." ".$input['surname'];
        $input['full_name_middlename'] = $input['firstname']." ".$input['surname']." ".$input['middlename'];

        $user = User::create($input);

        if(!empty($input['roles'])){ 

            $user->assignRole($request->input('roles'));
        }


        return redirect()->route('users.index')
                        ->with('success', 'Пользователь успешно зарегестрирован');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show',compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','id')->all();
        $role = DB::table("model_has_roles")->where("model_id",$id)
            ->pluck('role_id', 'role_id')
            ->all();



        return view('admin.users.edit',compact('user', 'roles', 'role'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'personal_score.unique' => 'Пользователь с таким лицевым счетом уже создан.',
        ];

        $this->validate($request, [
		    'firstname' => ['required', 'string', 'max:50'],
            'surname' => ['required', 'string', 'max:50'],
            'middlename' => ['string', 'max:50'],
            'sex' => ['integer', 'max:2'],
            'personal_score' => ['sometimes', 'nullable', 'string', 'max:50', 'unique:users,personal_score,'.$id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'password' => ['sometimes', 'nullable',  'string', 'min:8', 'confirmed'],
            'roles' => ['sometimes', 'array'],
        ], $messages);


        $input = $request->all();

        $input['full_name'] = $input['firstname']." ".$input['surname'];
        $input['full_name_middlename'] = $input['firstname']." ".$input['surname']." ".$input['middlename'];
		
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        $user = User::find($id);
        $user->update($input);

        if(!empty($input['roles'])){ 

           DB::table('model_has_roles')->where('model_id', $id)->delete();

           $user->assignRole($request->input('roles'));
        }

        return redirect()->route('users.index')
                        ->with('success', 'Пользователь успешно обновлен');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        return redirect()->route('users.index')
                        ->with('success', 'Пользователь успешно удален');
    }
	
	
}
