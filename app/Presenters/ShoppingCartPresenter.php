<?php

namespace App\Presenters;

use Illuminate\Support\Facades\View;
use Auth;

class ShoppingCartPresenter extends Presenter{

    public function registerToCheckOut(){
        $data=[];
        $data['mesagge']='SIGUIENTE';
        $data['route']='delivery-info';
        if (Auth::guard('customer-web')->check()){
            $data['href']='delivery-info';
            $data['class']='btn btn-sm btn-success btn-block';
        }else{
            $data['href']='#';
            $data['class']='btn btn-sm btn-dark btn-block void modalLogin';
        }
        return View::make('presenters.register-to-checkout', $data);
    }
    
}