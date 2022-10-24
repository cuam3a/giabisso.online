<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeAdmin;
use App\Mail\PasswordChangedAdmin;
use Auth;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\AdminModules;
use App\Models\Permission;
use Validator;
use Hash;

class AdminController extends Controller
{
	public function __construct(){
        $this->middleware('auth:admin-web');
    }

    public function listAdmins()
    {
        $data = [];
        $data['admins'] = Admin::all();//todas categorias
    	return view('admin.admins.list',$data);
    }
    
    /* Return json of admins */
    public function ajaxAdmins(Request $request)
    {
        $datatable = $request['datatable'];
        $pagination = $datatable['pagination'];
        $perPage = intval($pagination['perpage']);
        $start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
        $pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;

        $sort = $datatable['sort'];
        $order['dir'] = $sort['sort'];
        $order['column'] = $sort['field'];

        if($order['dir'] == null){ $order['dir'] = 'asc';$order['column'] = 'admin.id';} 

        $query =  isset($datatable['query']) ? $datatable['query'] : ["generalSearch" => null];
        $search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";

        //Obtener datos dependiendo a los parametros
        $data = Admin::listado($perPage, $pages, $start, $search, $order);
        return response()->json($data);
    }

    public function detail(Admin $admin)
    {
        $data = [];
        $data['admin'] = $admin;
        $data['modules'] = AdminModules::all();
        $data['permissions'] = $admin->permissionList();
    	return view('admin.admins.detail',$data);
    }

    public function saveAdmin(Request $request, Admin $admin)
    {   
        $data = $request->all();
        $passwordChanged = false;
        { //validaciones
            $validator = Validator::make($data, [
                'name' => 'required|max:100',
                'email' => 'required|email|max:100',
                'password' => 'required_if:admin_id,nullable',
            ]);   
            if($validator->fails()){
                $data['flash_type']  = 'error'; //<--FLASH MESSAGE
                $data['flash_title'] = $validator->errors()->first(); //<--FLASH MESSAGE
                $data['flash_message'] = 'Intente de nuevo'; //<--FLASH MESSAGE    
                return response()->json($data, 200);
            }
        }
        //Guardando admin
        $admin->name = $data['name'];
        $admin->email = $data['email'];
        if($data['password']){//Si cambio contraseña
            $passwordChanged = true;
            $admin->password = Hash::make($data['password']);
        }
        $admin->save();

        $data = collect($data)->filter(function ($value, $key) {
            return substr($key, 0, 7) == 'module_';
        });
        $data->all();

        $data = $data->map(function ($item, $key) {//obteniendo permisos
            return explode("_", $key)[1];
        });
        $data->all();

        Permission::where('admin_id',$admin->id)->delete();//borro permisos actuales
        foreach($data as $key => $value) {//se recapturan
            Permission::create(['admin_id' => $admin->id, 'module_id' => $value]);
        }
        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Listo'); //<--FLASH MESSAGE
        session()->flash('flash_message','Se guardó el usuario "'.$admin->name.'"'); //<--FLASH MESSAGE
        if($admin->wasRecentlyCreated){            
            Mail::to($admin->email)->send(new WelcomeAdmin($admin));
        }else{
            if($passwordChanged){                
                Mail::to($admin->email)->send(new PasswordChangedAdmin($admin));
            }
        }
        return redirect()->route('admin-list');
    }

    /* Function that check if email exists */
    public function emailExist(Request $request)
    {
        $data = ['email_exist' => true];
        $edit = $request->has('admin_id') ? Admin::find($request->admin_id) : false;
        $exist = Admin::where('email',$request->email)->first();

        if(!$exist){
            return 'true';
        }else{
            if($edit){                
                return 'true';
            }
        }
       return 'false';
    }

    public function deleteAdmin(Request $request)
    {   
        if(Admin::all()->count() == 1){
            session()->flash('flash_type','warning'); //<--FLASH MESSAGE
            session()->flash('flash_title','Atención'); //<--FLASH MESSAGE
            session()->flash('flash_message','El sistema necesita al menos un usuario'); //<--FLASH MESSAGE
            return redirect()->back();
        }  

        $admin = !$request->has('admin') ? Admin::find($request->admin) : false;
        if($admin){
            if(Auth::user()->id == $admin->id){
                session()->flash('flash_type','warning'); //<--FLASH MESSAGE
                session()->flash('flash_title','Atención'); //<--FLASH MESSAGE
                session()->flash('flash_message','No puede eliminar a si mismo'); //<--FLASH MESSAGE
            }else{
                $admin_id = $admin->email;
                $admin->delete();
                session()->flash('flash_type','success'); //<--FLASH MESSAGE
                session()->flash('flash_title','Listo'); //<--FLASH MESSAGE
                session()->flash('flash_message','Se eliminó usuario correctamente'); //<--FLASH MESSAGE
            }        
        }else{
            session()->flash('flash_type','danger'); //<--FLASH MESSAGE
            session()->flash('flash_title','Error'); //<--FLASH MESSAGE
            session()->flash('flash_message','No se encontró usuario'); //<--FLASH MESSAGE
        }
        return redirect()->back();
    }
}