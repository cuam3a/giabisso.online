<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Config;
use App\Models\Content;
use App\Models\ShipmentTypes;
use App\Models\QuestionAnswer;
use App\Models\Faq;
use App\Mail\QuestionsResponse;
use Validator;
use Storage;
use DB;


class ConfigurationController extends Controller
{
    public function __construct(){
		$this->middleware('auth:admin-web');
    }
    
    public function slider(){
        $data['images'] = Slider::select('id','path', 'description', 'order', 'status', 'status as status_filtered', 'created_at')->orderBy('order', 'asc')->get()->toJson();
        $data['statuses'] = Slider::$status;
    	return view('admin.slider.slider',$data);
    }

    public function addImageSliderForm(){
        return view('admin.slider.add-slider-img');
    }

    public function estatus($slider_img){
        $slider = Slider::find($slider_img);
        $slider->status = !$slider->status;
        $slider->save();
        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Imagen actualizada.'); //<--FLASH MESSAGE
        session()->flash('flash_message', $slider->description.' cambió de estatus'); //<--FLASH MESSAGE
    	return redirect()->route('admin-config-slider');
    }

    public function saveSliderImg(Request $request, Slider $slider){
        $data = $request->all();

        // New record
        if(!$request->id){
            $next = Slider::max('order');
            if($next == ''){
                $next = 0;
            }
            $next++;

            $validator = Validator::make($data, 
                [
                    'description' => 'required',
                    'image' => 'required|image|mimes:jpg,jpeg,png'
                ],
                [   
                    'description.required' => 'Introduzca una descripción',
                    'image.required' => 'Seleccione una imagen',
                    'image.image' => 'El archivo debe ser una imagen',
                    'image.mimes' => 'Tipos soportados: jgp, jpeg, png',
                ]
            );
        // Edit record
        }else{
            $slider = Slider::find($request->id);
            $next = $slider->order;
            
            if($request->hasFile('image')) {
                Storage::disk('public_uploads')->delete(str_replace('/uploads','',$slider->path)); 
            }
                

            $validator = Validator::make($data, 
                [
                    'description' => 'required',
                    'image' => 'image|mimes:jpg,jpeg,png'
                ],
                [   
                    'description.required' => 'Introduzca una descripción',
                    'image.image' => 'El archivo debe ser una imagen',
                    'image.mimes' => 'Tipos soportados: jgp, jpeg, png',
                ]
            );

        }
        
        $validator->validate();

        if ($request->hasFile('image')) {
            $slider->path = "/uploads/".$request->file('image')->store('slider','public_uploads');              
            $slider->image_name = $request->file('image')->getClientOriginalName();
            $slider->image_type = '';
        }

        
        $slider->description = $request->description;

        // Add http when link not empty
        if(substr( $request->link, 0, 4 ) != "http" && $request->link != ''){
            $request->link = 'http://'.$request->link;
        }

        $slider->link = $request->link;
        $slider->order = $next;
        $save = $slider->save();

        if($save){
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','Slider.'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Slider guardado'); //<--FLASH MESSAGE
            return redirect()->route('admin-config-slider');
        }
        else{
            session()->flash('flash_type','warning'); //<--FLASH MESSAGE
            session()->flash('flash_title','Slider.'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Ocurrió un error'); //<--FLASH MESSAGE
        }
        
        
        
    }

    public function editmageSliderForm($slider){
        $data['slider'] = Slider::find($slider);

        /* Redirect when no slider */
        if(!$data['slider']){
            return redirect()->route('admin-config-slider');
        }
        
        return view('admin.slider.edit-slider-img', $data);
    }

    public function changeOrder(Request $request){
        $curr = Slider::where('id', $request->id)->first();

        if($request->order == 'up' && $curr->order > 1){
            $besides = Slider::where('order', $curr->order - 1)->first();
            $tmp = $curr->order;
            $curr->order = $besides->order;
            $besides->order = $tmp;

            $besides->save();
            $curr->save();
        }

        $last = Slider::max('order');
        if($request->order == 'down' && $curr->order < $last){
            $besides = Slider::where('order', ($curr->order + 1))->first();
            $tmp = $curr->order;
            $curr->order = $besides->order;
            $besides->order = $tmp;

            $besides->save();
            $curr->save();
        }

        return redirect()->route('admin-config-slider');
    }

    public function deleteSlider($slider){
        $curr = Slider::where('id', $slider)->first();
        $path = $curr->path;

        if($curr)
            $del = $curr->delete();
        else{
            $del = null;
        }

        if($del){
            
            $this->reOrderSlider();

            Storage::disk('public_uploads')->delete(str_replace('/uploads','',$path));
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','Slider'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Imagen eliminada.'); //<--FLASH MESSAGE
        }else{
            session()->flash('flash_type','warning'); //<--FLASH MESSAGE
            session()->flash('flash_title','Slider'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Ocurrió un error'); //<--FLASH MESSAGE
        }

        return redirect()->route('admin-config-slider');
    }

    public function reOrderSlider(){
        $slider = Slider::orderBy('order', 'asc')->get();

        for($i=0; $i<count($slider); $i++){
            $el = $slider[$i];
            $el->order = $i+1;
            $el->save();
        }

    }

    public function seo(){
        $data['seo_keywords'] = Config::select('value')->where('name', 'KEYWORDS')->first();
        $data['seo_description'] = Config::select('value')->where('name', 'DESCRIPTION')->first();

        return view('admin.seo.seo',$data);
    }

    public function seoSave(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, 
            [
                'keywords' => 'required',
                'description' => 'required',
            ],
            [   
                'keywords.required' => 'Introduzca al menos una palabra clave',
                'description.required' => 'Introduzca la descripción para el sitio',
            ]
        );
        
        $validator->validate();

        $seo_keywords = Config::firstOrNew(['name' => 'KEYWORDS', 'type' => 'SEO']);
        $seo_description = Config::firstOrNew(['name' => 'DESCRIPTION', 'type' => 'SEO']);
        
        $seo_keywords->value = $request->keywords;
        $seo_description->value = $request->description;

        $save_keywords = $seo_keywords->save();
        $save_description = $seo_description->save();


        if($save_keywords && $save_description){
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','SEO.'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Información almacenada'); //<--FLASH MESSAGE
            return redirect()->route('admin-seo');
        }
        else{
            session()->flash('flash_type','warning'); //<--FLASH MESSAGE
            session()->flash('flash_title','SEO.'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Ocurrió un error'); //<--FLASH MESSAGE
        }
    }

    public function socialNetworks(){
        $data['facebook'] = Config::select('value')->where('name', 'FACEBOOK')->first();
        $data['instagram'] = Config::select('value')->where('name', 'INSTAGRAM')->first();
        $data['youtube'] = Config::select('value')->where('name', 'YOUTUBE')->first();
        $data['twitter'] = Config::select('value')->where('name', 'TWITTER')->first();
        $data['googleplus'] = Config::select('value')->where('name', 'GOOGLEPLUS')->first();
        $data['pinterest'] = Config::select('value')->where('name', 'PINTEREST')->first();

        return view('admin.social.social', $data);
    }

    public function socialNetworksSave(Request $request){
        
        $facebook = Config::firstOrNew(['name' => 'FACEBOOK', 'type' => 'SOCIAL']);
        $instagram = Config::firstOrNew(['name' => 'INSTAGRAM', 'type' => 'SOCIAL']);
        $youtube = Config::firstOrNew(['name' => 'YOUTUBE', 'type' => 'SOCIAL']);
        $twitter = Config::firstOrNew(['name' => 'TWITTER', 'type' => 'SOCIAL']);
        $googleplus = Config::firstOrNew(['name' => 'GOOGLEPLUS', 'type' => 'SOCIAL']);
        $pinterest = Config::firstOrNew(['name' => 'PINTEREST', 'type' => 'SOCIAL']);

        $facebook->value = $request->facebook;
        $instagram->value = $request->instagram;
        $youtube->value = $request->youtube;
        $twitter->value = $request->twitter;
        $googleplus->value = $request->googleplus;
        $pinterest->value = $request->pinterest;

        $facebook->save();
        $instagram->save();
        $youtube->save();
        $twitter->save();
        $googleplus->save();
        $pinterest->save();

        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Redes sociales'); //<--FLASH MESSAGE
        session()->flash('flash_message', 'Información almacenada'); //<--FLASH MESSAGE
        return redirect()->route('admin-social-networks');

    }

    public function contact(){
        $data['corporation_name'] = Config::select('value')->where('name', 'CORPORATION_NAME')->first();
        $data['business_name'] = Config::select('value')->where('name', 'BUSINESS_NAME')->first();
        $data['address'] = Config::select('value')->where('name', 'ADDRESS')->first();
        $data['phone1'] = Config::select('value')->where('name', 'PHONE1')->first();
        $data['phone2'] = Config::select('value')->where('name', 'PHONE2')->first();
        $data['email_orders'] = Config::select('value')->where('name', 'EMAIL_ORDERS')->first();
        $data['email_contact'] = Config::select('value')->where('name', 'EMAIL_CONTACT')->first();
        $data['email_support'] = Config::select('value')->where('name', 'EMAIL_SUPPORT')->first();

        return view('admin.contact.contact', $data);

    }

    public function contactSave(Request $request){
        
        $data = $request->all();
        $validator = Validator::make($data, 
            [
                'corporation_name' => 'required',
                'business_name' => 'required',
                'phone1' => 'required|digits:10|numeric',
                'phone2' => 'nullable|digits:10|numeric',
                'email_orders' => array(
                                    'required', 
                                    'regex:/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/'
                                ),
                'email_contact' => array(
                                    'required', 
                                    'regex:/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/'
                                ),
                'email_support' => array(
                                    'required', 
                                    'regex:/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/'
                                ),
                'address' => 'required'
            ],
            [   
                'corporation_name.required' => 'Introduzca el nombre de la empresa',
                'business_name.required' => 'Introduzca la razón social',

                'phone1.required' => 'Introduzca el teléfono',
                'phone1.digits' => 'Número teléfono no válido',
                'phone1.numeric' => 'Número teléfono no válido',

                'phone2.digits' => 'Número teléfono 2 no válido',
                'phone2.numeric' => 'Número teléfono 2 no válido',

                'email_orders.required' => 'Introduzca el correo de pedidos',
                'email_orders.regex' => 'Correo de pedidos inválido',

                'email_contact.required' => 'Introduzca el correo de contacto',
                'email_contact.regex' => 'Correo de contacto inválido',

                'email_support.required' => 'Introduzca el correo soporte',
                'email_support.regex' => 'Correo de soporte invalido',

                'address.required' => 'Introduzca la dirección',

            ]
        );
        
        $validator->validate();

        $corporation_name = Config::firstOrNew(['name' => 'CORPORATION_NAME', 'type' => 'CONTACT']);
        $business_name = Config::firstOrNew(['name' => 'BUSINESS_NAME', 'type' => 'CONTACT']);
        $phone1 = Config::firstOrNew(['name' => 'PHONE1', 'type' => 'CONTACT']);
        $phone2 = Config::firstOrNew(['name' => 'PHONE2', 'type' => 'CONTACT']);
        $email_orders = Config::firstOrNew(['name' => 'EMAIL_ORDERS', 'type' => 'CONTACT']);
        $email_contact = Config::firstOrNew(['name' => 'EMAIL_CONTACT', 'type' => 'CONTACT']);
        $email_support = Config::firstOrNew(['name' => 'EMAIL_SUPPORT', 'type' => 'CONTACT']);
        $address = Config::firstOrNew(['name' => 'ADDRESS', 'type' => 'CONTACT']);

        $corporation_name->value = $request->corporation_name;
        $business_name->value = $request->business_name;
        $phone1->value = $request->phone1;
        $phone2->value = $request->phone2;
        $email_orders->value = $request->email_orders;
        $email_contact->value = $request->email_contact;
        $email_support->value = $request->email_support;
        $address->value = $request->address;

        $corporation_name->save();
        $business_name->save();
        $phone1->save();
        $phone2->save();
        $email_orders->save();
        $email_contact->save();
        $email_support->save();
        $address->save();

        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Contacto'); //<--FLASH MESSAGE
        session()->flash('flash_message', 'Información almacenada'); //<--FLASH MESSAGE

        return redirect()->route('admin-contact');
    }

    public function contents(){
        $data['content'] = Content::select('value')->where('name', 'DELIVERY_POLICY')->first();
        return view('admin.contents.contents', $data);
    }

    public function contentsSave(Request $request){
        $content = Content::firstOrNew(['name' => 'DELIVERY_POLICY', 'type' => 'POLICY']);

        $content->value = $request->content;
        $content->save();

        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Contenidos'); //<--FLASH MESSAGE
        session()->flash('flash_message', 'Información almacenada'); //<--FLASH MESSAGE
        
        return redirect()->route('admin-contents');
    }

    public function freeShipment(){
        $data['free_shipment'] = Config::select('value')->where('name', 'FREE_SHIPMENT')->first();
        $data['amount'] = Config::select('value')->where('name', 'FREE_SHIPMENT_AMOUNT')->first();
        $data['flat_rate'] = Config::select('value')->where('name', 'FLAT_RATE')->first();

        $data['shipment_types'] = ShipmentTypes::all();

        return view('admin.shipments.free-shipment',$data);
    }

    public function saveFreeShipment(Request $request){
        $data = $request->all();
        // print_r($data);
        // exit();
        $validator = Validator::make($data, 
            [
                'amount' => 'required_if:freeShipment,==,on'
            ],
            [   
                'amount.required_if' => 'Introduzca el monto mínimo',
            ]
        );
        
        $validator->validate();

        $free_shipment = Config::firstOrNew(['name' => 'FREE_SHIPMENT', 'type' => 'SHIPMENTS']);
        $amount = Config::firstOrNew(['name' => 'FREE_SHIPMENT_AMOUNT', 'type' => 'SHIPMENTS']);

        $aereo = ShipmentTypes::where('name', 'aereo')->first();
        $aereo->cost = $request->aereo;
        $aereo->description = $request->aereo_description;
        $aereo->save();

        $terrestre = ShipmentTypes::where('name', 'terrestre')->first();
        $terrestre->cost = $request->terrestre;
        $terrestre->description = $request->terrestre_description;
        $terrestre->save();


        if($request->freeShipment == 'on'){
            $free_shipment->value = 'ON';
            $amount->value = str_replace(',','',$request->amount);
        }

        else{
            $free_shipment->value = 'OFF';
            $amount->value = null;
        }


        DB::table('shipment_types')->where('id', '<>', $request->flat_rate)->update(array('default' => 0));

        $default_rate = ShipmentTypes::where('id', $request->flat_rate)->first();
        $default_rate->default = 1;
        $default_rate->save();

        // if($request->flat_rate == ''){
        //     $request->flat_rate = 0;
        // }
        

        $amount->save();
        $free_shipment->save();
        
        session()->flash('flash_type','success'); //<--FLASH MESSAGE
        session()->flash('flash_title','Envíos'); //<--FLASH MESSAGE
        session()->flash('flash_message', 'Información almacenada'); //<--FLASH MESSAGE

        return redirect()->route('admin-shipment-free');
    }

    public function faq(){
        $data['faq'] = Faq::select('id','title', 'content', 'order')->orderBy('order', 'asc')->get();
        
        return view('admin.faq.faq',$data);
    }

    public function faqForm(){
        return view('admin.faq.faq-form');
    }

    public function faqSave(Request $request, Faq $faq){
        $data = $request->all();
        $validator = Validator::make($data, 
            [
                'title' => 'required',
                'content' => 'required',
            ],
            [   
                'title.required' => 'Introduzca una pregunta',
                'content.required' => 'Introduzca el contenido',
            ]
        );

        $validator->validate();
        
        // New record
        if(!$request->id){
            $next = Faq::max('order');
            if($next == ''){
                $next = 0;
            }
            $next++;

        // Edit record
        }else{
            $faq = Faq::find($request->id);
            $next = $faq->order;
        }
        
        $validator->validate();

        
        $faq->title = $request->title;
        $faq->content = $request->content;
        $faq->order = $next;
        $save = $faq->save();

        if($save){
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','FAQ'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Pregunta almacenada correctamente'); //<--FLASH MESSAGE
            return redirect()->route('admin-faq');
        }
        else{
            session()->flash('flash_type','warning'); //<--FLASH MESSAGE
            session()->flash('flash_title','FAQ.'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Ocurrió un error'); //<--FLASH MESSAGE
        }
            
    }

    public function faqEditForm($faq){
        $data['faq'] = Faq::find($faq);

        /* Redirect when no faq */
        if(!$data['faq']){
            return redirect()->route('admin-faq');
        }
        
        return view('admin.faq.faq-edit-form', $data);
    }

    public function faqDelete($faq){
        $curr = Faq::where('id', $faq)->first();

        if($curr)
            $del = $curr->delete();
        else{
            $del = null;
        }
        
        $this->reOrderFaq();

        if($del){
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','FAQ'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Pregunta eliminada.'); //<--FLASH MESSAGE
        }else{
            session()->flash('flash_type','warning'); //<--FLASH MESSAGE
            session()->flash('flash_title','FAQ'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Ocurrió un error'); //<--FLASH MESSAGE
        }

        return redirect()->route('admin-faq');
    }

    public function reOrderFaq(){
        $faq = Faq::orderBy('order', 'asc')->get();

        for($i=0; $i<count($faq); $i++){
            $el = $faq[$i];
            $el->order = $i+1;
            $el->save();
        }

    }

    public function faqOrder(Request $request){
        $curr = Faq::where('id', $request->id)->first();

        if($request->order == 'up' && $curr->order > 1){
            $besides = Faq::where('order', $curr->order - 1)->first();
            if($besides != null){
                $tmp = $curr->order;
                $curr->order = $besides->order;
                $besides->order = $tmp;
                $besides->save();
            }
            
            $curr->save();
        }

        $last = Faq::max('order');
        if($request->order == 'down' && $curr->order < $last){
            $besides = Faq::where('order', ($curr->order + 1))->first();
            $tmp = $curr->order;
            $curr->order = $besides->order;
            $besides->order = $tmp;

            $besides->save();
            $curr->save();
        }

        return redirect()->route('admin-faq');
    }

    public function faqEdit(){
        
    }

    public function refundsForm(){
        $data['refunds_days'] = Config::where('name', 'REFUNDS_DAYS')->first();
        $data['refunds_instructions'] = Config::where('name', 'REFUNDS_INSTRUCTIONS')->first();

        return view('admin.refunds.refunds-form',$data);
    }

    public function refundsSave(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, 
            [
                'refunds_days' => 'required',
                'refunds_days' => 'integer|min:0',
                'refunds_instructions' => 'required',
            ],
            [   
                'refunds_days.required' => 'Introduzca el número de días',
                'refunds_days.integer' => 'Introduzca un número válido',
                'refunds_instructions.required' => 'Introduzca las instrucciones de devolución',
            ]
        );

        $validator->validate();

        $refunds_days = Config::firstOrNew(['name' => 'REFUNDS_DAYS', 'type' => 'REFUNDS']);
        $refunds_instructions = Config::firstOrNew(['name' => 'REFUNDS_INSTRUCTIONS', 'type' => 'REFUNDS']);

        $refunds_days->value = $request->refunds_days;
        $refunds_instructions->value = $request->refunds_instructions;

        if($refunds_days->save() && $refunds_instructions->save()){
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','Devoluciones'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Información almacenada'); //<--FLASH MESSAGE
            return redirect()->route('admin-config-refunds');
        }
        else{
            session()->flash('flash_type','warning'); //<--FLASH MESSAGE
            session()->flash('flash_title','Devoluciones'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Ocurrió un error'); //<--FLASH MESSAGE
        }

        return redirect()->route('admin-config-refunds');
    }

    public function creditForm(){
        $credit = Config::where('name', 'CREDIT')->select('value')->first();
        $credit_days = Config::where('name', 'CREDIT_DAYS')->select('value')->first();
        //dd($credit);
        if(empty($credit)){
            $data['credit'] = 0;
        }else{
            $data['credit'] = $credit->value;
        }

        if(empty($credit_days)){
            $data['credit_days'] = 0;
        }else{
            $data['credit_days'] = $credit_days->value;
        }

        return view('admin.credit.credit',$data);
    }

    public function creditSave(Request $request){
        $data = $request->all();

        $credit = Config::firstOrNew(['name' => 'CREDIT', 'type' => 'CREDIT']);
        $credit_days = Config::firstOrNew(['name' => 'CREDIT_DAYS', 'type' => 'CREDIT']);

        $credit->value = $request->credit;
        $credit_days->value = $request->credit_days;

        if($credit->save() && $credit_days->save()){
            session()->flash('flash_type','success'); //<--FLASH MESSAGE
            session()->flash('flash_title','Credito'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Información almacenada'); //<--FLASH MESSAGE
            return redirect()->route('admin-config-credit');
        }
        else{
            session()->flash('flash_type','warning'); //<--FLASH MESSAGE
            session()->flash('flash_title','Credito'); //<--FLASH MESSAGE
            session()->flash('flash_message', 'Ocurrió un error'); //<--FLASH MESSAGE
        }

        return redirect()->route('admin-config-credit');
    }
}
