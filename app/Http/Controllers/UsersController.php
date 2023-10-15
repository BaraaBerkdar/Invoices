<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UsersController extends Controller
{
    /**
     * Display all users
     * 
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:عرض صلاحية', ['only' => ['index']]);
        $this->middleware('permission:اضافة مستخدم', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل مستخدم', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف مستخدم', ['only' => ['destroy']]);
        
    }
    





    public function index() 
    {
        $data = User::latest()->paginate(10);

        return view('users.show_users', compact('data'));
    }

    /**
     * Show form for creating user
     * 
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {   $roles=Role::pluck('name','name')->all();
        return view('users.Add_user',compact('roles'));
    }

    /**
     * Store a newly created user
     * 
     * @param User $user
     * @param StoreUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, Request $request) 
    {
        //For demo purposes only. When creating user or inviting a user
        // you should create a generated random password and email it to the user
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles_name' => 'required'
            ]);
            
            $input = $request->all();
            $input['status']=$request->Status;
            

            $input['password'] = Hash::make($input['password']);
            
            $user = User::create($input);
            $user->assignRole($request->input('roles_name'));
            return redirect()->route('users.index')
            ->with('success','تم اضافة المستخدم بنجاح');
            }

    

    /**
     * Show user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) 
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Edit user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        // return view('users.edit', [
        //     'user' => $user,
        //     'userRole' => $user->roles->pluck('name')->toArray(),
        //     'roles' => Role::latest()->get()
        // ]);
        $user=User::find($id);
        $rolename=$user->roles->pluck('name');
        $roles=Role::pluck('name')->all();
        
        return view('users.edit',compact('user','rolename','roles'));

    }

    /**
     * Update user data
     * 
     * @param User $user
     * @param UpdateUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id) 
    {   $user=User::find($id);
        $user->update([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>Hash::make($request['password']),
            "status"=>$request->Status,
        ]);
        // DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        // $user->assignRole($request->input('roles'));
       
        $user->syncRoles($request->input('roles'));

        return redirect()->route('users.index')
            ->withSuccess(__('تم التعديل بنجاح'));
    }

    /**
     * Delete user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) 
    {
        $user->delete();

        return redirect()->route('users.index')
            ->withSuccess(__('User deleted successfully.'));
    }
}
 