<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Validator;
use Auth;
use Carbon\Carbon;
use App\Models\Suscribe;
use Excel;
use DB;

class SuscribeController extends Controller
{
    public function __construct(){
		  $this->middleware('auth:admin-web',['except' => 'addSuscribe']);
    }

    public function listSuscribe(){      
    	return view('admin.suscribe.list');
    }

        /* Return json of customers */
    public function ajaxSuscribe(Request $request)
    {
        $datatable = $request['datatable'];
        $pagination = $datatable['pagination'];
        $perPage = intval($pagination['perpage']);
        $start = array_key_exists('page',$pagination) ? intval($pagination['page']) : 1;
        $pages = array_key_exists('pages',$pagination) ? intval($pagination['pages']) : 0;

        $sort = $datatable['sort'];
        $order['dir'] = $sort['sort'];
        $order['column'] = $sort['field'];

        if($order['dir'] == null){ $order['dir'] = 'asc';$order['column'] = 'email';} 

        $query =  isset($datatable['query']) ? $datatable['query'] : ["generalSearch" => null];
        $search = array_key_exists('generalSearch',$query) ? $query['generalSearch'] : "";

        //Obtener datos dependiendo a los parametros
        $data = Suscribe::listado($perPage, $pages, $start, $search, $order);
        return response()->json($data);
    }

    public function addSuscribe()
    {   
        $data = Input::all();
        {
         $validator = Validator::make($data, ['email' => 'required|email|max:100']);
         //$validator->validate();
         if($validator->fails()){
             $data['flash_type']  = 'error'; //<--FLASH MESSAGE
             $data['flash_title'] = $validator->errors()->first(); //<--FLASH MESSAGE
             $data['flash_message'] = 'Intente de nuevo'; //<--FLASH MESSAGE    
            return response()->json($data, 200);
         }
        }
        $email = Input::get('email');
        $suscribe = Suscribe::firstOrCreate(['email' => $email]);
        $data['flash_type']  = 'success'; //<--FLASH MESSAGE
        $data['flash_title'] = '¡Gracias por suscribirse!'; //<--FLASH MESSAGE
        $data['flash_message'] = 'Muy pronto recibirá nuestras noticias a su correo <b>'.$suscribe->email.'</b>'; //<--FLASH MESSAGE
        return response()->json($data);
    }

    public function deleteSuscribe(Request $request)
    {   
        $suscribe = !$request->has('id') ? Suscribe::find($request->id) : false;
        if($suscribe){          
          $suscribe_id = $suscribe->email;
          $suscribe->delete();
          session()->flash('flash_type','success'); //<--FLASH MESSAGE
          session()->flash('flash_title','Listo'); //<--FLASH MESSAGE
          session()->flash('flash_message','Se eliminó suscriptor correctamente'); //<--FLASH MESSAGE
        }else{
          session()->flash('flash_type','danger'); //<--FLASH MESSAGE
          session()->flash('flash_title','Error'); //<--FLASH MESSAGE
          session()->flash('flash_message','Intente más tarde'); //<--FLASH MESSAGE
        }
        return redirect()->back();
    }

    public function exportSuscribers()
    {
        //$data1 = Suscribe::select('email','created_at')->get();
        $data = DB::select('select email, DATE_FORMAT(created_at,"%d/%m/%Y") as fecha from suscribe');
        $data = json_decode(json_encode($data),true);
        $titles = ['A2' => 'HEC suscriptores','A5' => 'Correo electrónico','B5' => 'Fecha de inscripción'];
        return Excel::create('HEC-suscriptores', function($excel) use ($data, $titles) {
            $excel->setCreator('Home Express Center')->setCompany('Home Express Center');
            $excel->sheet('suscriptores', function($sheet) use ($data,$titles)
            {
                
                $sheet->mergeCells('A2:B2');
				$sheet->setStyle(array(
				    'font' => array(
				        'name'      =>  'Arial',
				        'size'      =>  12,
				        'bold'      =>  false
				    )
				));
                $sheet->fromArray($data, null, 'A5', true);
                foreach ($titles as $key => $value) {//poniendo titulos
                    $sheet->cell($key, function($cell) use($value){
                        $cell->setValue($value);
                        $cell->setValignment('center');
                        $cell->setAlignment('center');
                    });
                }
                $sheet->getStyle('A5:C5')->applyFromArray([//Dando formato a titulos                 
                    'font' => array(
                        'bold'      =>  true
                    )]
                );
                $sheet->getStyle('A2:C2')->applyFromArray([//Dando formato a titulos                 
                    'font' => array(
                        'bold'      =>  true,
                        'size'      =>  15,
                    )]
                );
            });
        })->download('xls');
    }
}