<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(["Visitas","restore_cart"])->prefix('/')->group(function () {

    Route::get('/',function(){
        $data['html_title']                               = 'Renta de Maquinaria';
        return view('website.machinery');
    })->name('web-machinery');
    
    Route::get('/','WebsiteController@index')->name('website-home');
    //Route::get('/ingresar', [ 'as'                  => 'website-login', 'uses' => 'WebsiteController@login']);
    
    
    Route::post('/get-cities','WebsiteController@getCities')->name('get-cities');//AJAX
    Route::get('/preguntas-frecuentes', [ 'as'          => 'faq', 'uses' => 'WebsiteController@faq']);
    Route::get('/aviso-de-privacidad', [ 'as'           => 'privacy', 'uses' => 'WebsiteController@privacy']);
    Route::get('/politicas-envio-cancelacion', [ 'as'   => 'policy', 'uses' => 'WebsiteController@policy']);
    Route::get('/datos-envio', [ 'as'                   => 'delivery-info', 'uses' => 'WebsiteController@deliveryInfo']);
    Route::get('/nosotross', [ 'as'                      => 'about-us', 'uses' => 'WebsiteController@aboutUs']);
    Route::get('/contactoo', [ 'as'                      => 'contact-us', 'uses' => 'WebsiteController@contactUs']);
    Route::post('/enviar-email-contacto', [ 'as'        => 'send-email-contact', 'uses' => 'WebsiteController@sendEmailContact']);
    Route::get('/carrito', [ 'as'                       => 'cart', 'uses' => 'WebsiteController@cart']);
    Route::get('/programacion', [ 'as'                       => 'programming', 'uses' => 'WebsiteController@programming']);//GCUAMEA
    Route::get('/concepto', [ 'as'                       => 'concepto', 'uses' => 'WebsiteController@concepto']);//GCUAMEA
    Route::get('/nosotros', [ 'as'                       => 'nosotros', 'uses' => 'WebsiteController@nosotros']);//GCUAMEA
    Route::get('/politica-precios', [ 'as'                       => 'politica-precios', 'uses' => 'WebsiteController@politica_precios']);//GCUAMEA
    Route::get('/ventajas', [ 'as'                       => 'ventajas', 'uses' => 'WebsiteController@ventajas']);//GCUAMEA
    Route::get('/beneficios', [ 'as'                       => 'beneficios', 'uses' => 'WebsiteController@beneficios']);//GCUAMEA
    Route::get('/contacto', [ 'as'                       => 'contacto', 'uses' => 'WebsiteController@contacto']);//GCUAMEA
    Route::get('/liquidacion', [ 'as'                       => 'settlement-products', 'uses' => 'WebsiteController@settlementList']);//GCUAMEA
    
    Route::get('/producto/{id}/{slug}', [ 'as'        => 'product-detail', 'uses' => 'WebsiteController@productDetail']);
    Route::group([ 'prefix'                           => '/tienda'], function () {
        Route::get('/', [ 'as'                 => 'search-products', 'uses' => 'WebsiteController@categoryList']);    
        Route::get('/{slug?}/{child?}', [ 'as' => 'category-products', 'uses' => 'WebsiteController@categoryList']);
        //Route::get('/liquidacion/{liq?}', [ 'as' => 'settlement-products', 'uses' => 'WebsiteController@settlementList']);   
    });
    //Route::get('tienda/liquidacion','WebsiteController@settlementList')->name('settlement-products');
    Route::post('/guardar-pregunta','WebsiteController@addQuestion')->name('add-question-product');
    Route::post('/guardar-valoracion','WebsiteController@addRating')->name('add-rating');
    
    Route::group([ 'prefix'                           => '/carrito'], function () {
        Route::post('/agregar-producto/{product}/{ban?}','WebsiteController@addProductToCart')->name('add-product-to-cart');
        //Route::post('/volver-agregar-producto/{product}','WebsiteController@addProductToCartAgain')->name('add-product-to-cart-again');

        Route::get('/eliminar/{product}','WebsiteController@deleteProductFromCart')->name('delete-product-from-cart');
        Route::post('/actualizar','WebsiteController@updateProductCart')->name('update-product-cart');
        Route::get('/cotizacion','WebsiteController@exportOrderPDF')->name('export-order-pdf');//GCUAMEA
        Route::post('/agregar-producto-programacion/{product}/{ban?}','WebsiteController@addProductOrderProgramming')->name('add-product-to-programming');//GCUAMEA
        Route::get('/eliminar_producto/{product}','WebsiteController@deleteProductFromProgramming')->name('delete-product-from-programming');//GCUAMEA
        Route::post('/actualizar_pedido','WebsiteController@updateProductProgramming')->name('update-product-programming');//GCUAMEA
        Route::get('/generar-pedido-programado','WebsiteController@createOrderProgramming')->name('create-orderProgramming'); //GCUAMEA
    });
    
    Route::post('/mi-cuenta#devoluciones',['as'       => 'my-account-tab-refunds']);
    
    Route::group([ 'prefix'                           => '/mi-cuenta', 'namespace' => 'Customer'], function () {
        //Customer        
        Route::get('olvide-contrasena', [ 'as'            => 'forgot-password', 'uses' => 'LoginController@showForgotPasswordForm']);
        Route::post('contrasena/email', 'LoginController@sendResetLinkEmail')->name('send-reset-link-email');
        Route::match(['get','post'], '/recuperar-password/{email}/{token}', 'LoginController@recoverPassword')->name('recover-password-customer');
        Route::get('/ingresar', [ 'as'                    => 'website-login', 'uses' => 'LoginController@login']);
        Route::get('/', [ 'as'                            => 'my-account', 'uses' => 'CustomerController@myAccount']);
        Route::post('/nuevo-usuario', [ 'as'              => 'new-customer', 'uses' => 'LoginController@newCustomer']);
        Route::get('/ingresar-usuario', 'LoginController@signInCustomer')->name('signin-customer');
        Route::get('/cerrar-sesion', 'LoginController@logoutCustomer')->name('logout-customer');
        Route::get('/actualizar-favoritos/{product}', 'CustomerController@updateFavorites')->name('update-favorites');    
        Route::post('/guardar-direccion', 'CustomerController@saveAddress')->name('save-customer-address');    
        Route::get('/eliminar-direccion/{id}', 'CustomerController@delAddress')->name('del-customer-address');    
        Route::post('/actualizar-perfil', 'CustomerController@updateCustomerProfile')->name('update-customer-profile');
        Route::get('/exportar/{id}/pdf','CustomerController@exportOrderDetailPDF')->name('order-export-pdf');//GCUAMEA
    });
    
    Route::group(['prefix'                            => 'pedidos'], function(){                
        Route::get('/detalle-pago/{order}/{email}','Customer\CustomerController@orderDetail')->name('payment-detail');        
        //Mensajes dentro del pedido
        Route::post('/detalle-pago/{order}/{email}/enviar-mensaje','Customer\MessageController@sendMessage')->name('send-message');

        Route::get('/devoluciones','Customer\CustomerController@refundsProducts')->name('refunds-products');        
        Route::post('/devoluciones', 'Customer\CustomerController@refunds')->name('refunds-customer');    
        Route::post('/eliminar-devolucion', 'Customer\CustomerController@deleteRefunds')->name('refunds-customer-delete');    
        
        Route::get('/pago-realizado/{order}/{email}', 'Customer\CustomerController@orderDetail')->name('payment-success');
        Route::get('/pago-en-proceso', 'WebsiteController@processing')->name('payment-processing');
        Route::get('/pago-cancelado', 'WebsiteController@canceled')->name('payment-canceled');
        
        Route::group(['prefix'                            => 'notificaciones'], function(){
            Route::match(['get', 'post'],'/mp','WebsiteController@notifications_mp')->name('notificaciones-mercado-pago');
        });

    });   

});


  
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){

    Route::get('/login', 'LoginController@loginAdmin')->name('login-admin');
    Route::get('/ingresar-admin', 'LoginController@signInAdmin')->name('signin-admin');
    Route::get('/cerrar-sesion', 'LoginController@logoutAdmin')->name('logout-admin');
    //Password reset routes
    Route::post('/enviar-mail-password', 'LoginController@sendMailForgetPassword')->name('admin-send-mail-password');
    Route::match(['get','post'],'/recuperar-password/{email}/{token}', 'LoginController@recoverPassword')->name('admin-recover-password');

    Route::post('/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin-password-email');
    Route::get('/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin-password-request');
    Route::post('/password/reset', 'Auth\AdminResetPasswordController@reset');
    Route::get('/password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('admin-password-reset');
    

   

    //Modules
    Route::get('/','DashboardController@dashboard')->name('dashboard');
    
    Route::group(['prefix' => 'usuarios'], function(){
        Route::get('/','AdminController@listAdmins')->name('admin-list');
        Route::get('/ajax-admins','AdminController@ajaxAdmins')->name('admin-list-ajax');//AJAX
        Route::post('/admin-email-exist','AdminController@emailExist')->name('admin-email-exist');//AJAX
        Route::get('/{admin}/informacion/','AdminController@detail')->name('admin-info');
        Route::get('/crear','AdminController@detail')->name('admin-create');//AJAX
        Route::get('/guardar/{admin?}','AdminController@saveAdmin')->name('admin-save');//AJAX
        Route::get('/eliminar/{admin}/admin','AdminController@deleteAdmin')->name('admin-delete');//AJAX
    });
    Route::group(['prefix' => 'categorias'], function(){
        Route::get('/','CategoryController@listCategories')->name('admin-category-list');
        Route::get('/save-category','CategoryController@saveCategory')->name('admin-category-save');//AJAX
        Route::post('/save-category-orderby','CategoryController@saveCategoryOrderBy')->name('admin-category-orderby');//AJAX
        Route::get('/delete-category','CategoryController@deleteCategory')->name('admin-category-delete');//AJAX
        Route::post('/get-subcategories','CategoryController@getSubcategories')->name('admin-category-get-subcategories');//AJAX
        Route::post('/get-brands','BrandController@getBrandsInCategory')->name('admin-brands-get');//AJAX
    });
    Route::group(['prefix' => 'destacados'], function(){
        Route::get('/','CarouselController@listCarousels')->name('admin-carousels-list');
        Route::get('/get-productos','CarouselController@getProducts')->name('admin-get-products-carousel');
        Route::get('/destacados/{carousel}/productos','CarouselController@carouselDetail')->name('admin-carousel-detail');
        Route::get('/save-carousel','CarouselController@saveCarousel')->name('admin-carousel-save');//AJAX
        Route::post('/save-carousel-orderby','CarouselController@saveCarouselOrderBy')->name('admin-carousel-orderby');//AJAX
        Route::get('/delete-carousel','CarouselController@deleteCarousel')->name('admin-carousel-delete');//AJAX
        Route::post('/save-carousel-products','CarouselController@saveCarouselProducts')->name('admin-carousel-save-products');//AJAX
    });

    Route::group(['prefix' => 'clientes'], function(){
        Route::get('/','CustomerController@listCustomers')->name('admin-customer-list');
        Route::get('/ajax-customers','CustomerController@ajaxCustomers')->name('admin-customer-list-ajax');//AJAX
        Route::get('/cliente/exportar','CustomerController@exportClients')->name('admin-customer-exportClients');
        Route::get('/cliente/{customer}/informacion/','CustomerController@detail')->name('admin-customer-detail');
        Route::get('/cliente/{customer}/exportar','CustomerController@exportOrders')->name('admin-customer-export');
        Route::get('/interaciones/categorias','InteractionController@categories')->name('admin-customer-interaction-categories');
        Route::get('/interaciones/productos','InteractionController@products')->name('admin-customer-interaction-products');
        Route::get('/interaciones/exportado-por-categoria','InteractionController@exportCategories')->name('admin-customer-export-interaction-categories');
        Route::get('/interaciones/exportado-por-producto','InteractionController@exportProducts')->name('admin-customer-export-interaction-products');
        Route::get('/ajax-category-customer-views','InteractionController@ajaxCategoryCustomerViews')->name('admin-category-customer-view-list-ajax');//AJAX
        Route::get('/ajax-product-customer-views','InteractionController@ajaxProductCustomerViews')->name('admin-product-customer-view-list-ajax');//AJAX
        Route::get('/lista-de-precios','CustomerController@pricesLists')->name('admin-prices-list');//GCUAMEA
        Route::get('/ajax-pricesList','CustomerController@ajaxPricesList')->name('admin-prices-list-ajax');//AJAX GCUAMEA
        Route::get('/agregarLista/{name}','CustomerController@agrgarLista')->name('admin-customer-add-prices-list');//AJAX GCUAMEA
        Route::get('/lista-de-precios/{pricesList}','CustomerController@pricesListEdit')->name('admin-prices-list-edit');//GCUAMEA
        Route::get('/ajax-priceListProducts','CustomerController@ajaxPriceListProducts')->name('admin-prices-list-products-ajax');//AJAX GCUAMEA
        //Route::get('/ajax-priceListProductsEdit','CustomerController@ajaxPriceListProductsEdit')->name('admin-prices-list-products-edit-ajax');//AJAX GCUAMEA
        Route::get('/ajax-priceListProductsEdit','CustomerController@ajaxPriceListProductsEdit')->name('admin-prices-list-products-edit-ajax');//AJAX GCUAMEA
        Route::get('/lista-de-precios/{pricesList}/eliminar','CustomerController@deletePriceList')->name('admin-prices-list-delete');//AJAX GCUAMEA
        Route::post('/guardar','CustomerController@save')->name('admin-customer-save');//AJAX GCUAMEA
    });

    Route::group(['prefix' => 'productos'], function(){
        Route::get('/','ProductController@listProducts')->name('admin-product-list');
        Route::get('/ajax-products','ProductController@ajaxProducts')->name('admin-product-list-ajax');//AJAX
        Route::get('/ajax-brands','ProductController@getBrands')->name('admin-brand-list-ajax');//AJAX
        Route::get('/crear/producto/','ProductController@detail')->name('admin-product-create');
        Route::get('/producto/{product}/editar','ProductController@detail')->name('admin-product-edit');

        
        Route::get('/duplicar/producto/{product}','ProductController@duplicate')->name('admin-product-duplicate');
        //Route::get('/producto/{product}/duplicar','ProductController@detail')->name('admin-product-duplicate');

        Route::post('/producto/guardar/{product?}','ProductController@save')->name('admin-product-save');
        Route::get('/producto/{product}/estatus','ProductController@changeStatus')->name('admin-product-change-estatus');//AJAX
        Route::get('/producto/{product}/eliminar','ProductController@deleteProduct')->name('admin-product-delete');//AJAX
        Route::get('/importador','ProductController@importerView')->name('admin-product-importer-view');
        Route::post('/importar','ProductController@importProducts')->name('admin-product-import-products');//AJAX
        Route::post('/producto/agregar-imagen','ProductController@addImage')->name('admin-product-add-image');//AJAX
        Route::post('/producto/eliminar-imagen','ProductController@removeImage')->name('admin-product-remove-image');//AJAX
        Route::post('/producto/ordenar-imagenes','ProductController@sortImages')->name('admin-product-sort-images');//AJAX
        Route::get('/producto/exportar','ProductController@exportProducts')->name('admin-product-export');


        Route::get('/valoraciones', 'ProductController@ratings')->name('admin-ratings');
        Route::get('/cambiar-status/{id?}/{status?}', 'ProductController@changeRating')->name('change-review'); 
 
        Route::get('/preguntas-respuestas', 'ProductController@questionsAnswers')->name('admin-questions-answers'); 
        Route::post('/guardar-respuesta', 'ProductController@saveAnswer')->name('save-answers'); 
        Route::get('/eliminar-pregunta/{id?}', 'ProductController@trashQuestion')->name('trash-question'); 

        Route::get('/ajax-products-list','ProductController@ajaxGetPriceList')->name('admin-product-price-list-ajax');//AJAX GCUAMEA
        Route::post('/ajax-update-products-list','ProductController@ajaxUpdatePriceList')->name('admin-update-product-price-list-ajax');//AJAX GCUAMEA
        Route::post('/ajax-update-price-products-list','ProductController@ajaxPriceUpdatePriceList')->name('admin-update-price-product-price-list-ajax');//AJAX GCUAMEA
        Route::post('/ajax-update-liquidacion-status','ProductController@ajaxLiquidacionStatus')->name('admin-update-liquidacion-status-ajax');//AJAX GCUAMEA
        Route::post('/ajax-update-liquidacion-price','ProductController@ajaxLiquidacionPrice')->name('admin-update-liquidacion-price-ajax');//AJAX GCUAMEA
        Route::post('/ajax-get-price','ProductController@ajaxgetPrice')->name('admin-get-price-ajax');//AJAX GCUAMEA
    });
    
    Route::group(['prefix' => 'pedidos'], function(){
        Route::get('/','OrderController@listOrders')->name('admin-order-list');
        Route::get('/ajax-orders','OrderController@ajaxOrders')->name('admin-order-list-ajax');//AJAX
        Route::get('/pedido/{order}/detalle/','OrderController@detail')->name('admin-order-detail');
        Route::get('/status/{order}/change-payment/','OrderController@changePaymentStatus')->name('admin-order-change-payment-status-order');
        Route::get('/status/{order}/change-status/{statusnum}','OrderController@changeStatus')->name('admin-order-change-status-order');
        Route::post('/pedido/detalle-envio/guardar','OrderController@saveShippingDetails')->name('admin-order-save-shipping-details');  
        Route::get('/exportar','OrderController@exportOrders')->name('admin-order-export'); 
        Route::get('/exportar/{id}/pdf','OrderController@exportOrderDetailPDF')->name('admin-order-export-pdf');  
        Route::post('/generar-pedido','OrderController@createOrder')->name('create-order');       
        Route::get('/devoluciones','OrderController@listRefunds')->name('admin-refunds-list');
        Route::post('/guardar-devolucion','OrderController@saveRefund')->name('admin-save-refund');

        
        Route::get('/programacion','OrderController@listProgrammingOrders')->name('admin-order-programming-list');//GCUAMEA
        Route::get('/ajax-orders-programming','OrderController@ajaxProgrammingOrders')->name('admin-order-list-programming-ajax');//AJAX GCUAMEA
        //Route::get('/statusProgramming/{order}/change-status/{statusnum}','OrderController@changeStatusProgramming')->name('admin-order-change-status-order');//GCUAMEA
        Route::get('/pedido-programado/{order}/detalle/','OrderController@detailProgramming')->name('admin-order-programming-detail');//GCUAMEA
        Route::post('/pedido-programado/detalle/actualizar','OrderController@updateDetailsProgramming')->name('update-product-programming-admin');//GCUAMEA
        Route::get('/statusProgramming/{order}/change-status/cancelar','OrderController@cancelOrderProgramming')->name('admin-order-cancel-order');
    });

    Route::group(['prefix' => 'mensajes'], function(){
        Route::get('/','MessageController@messages')->name('admin-messages-list');
        Route::get('/pedido/{order}','MessageController@messagesOrder')->name('admin-messages-order');
        Route::post('/pedido/{order}/enviar','MessageController@sendMessage')->name('admin-send-message');
    });

    Route::group(['prefix' => 'configuracion'],function(){
        Route::get('/slider', 'ConfigurationController@slider')->name('admin-config-slider');
        Route::get('/agregar-imagen', 'ConfigurationController@addImageSliderForm')->name('admin-add-slider-img');
        Route::get('/editar-imagen/{slider}', 'ConfigurationController@editmageSliderForm')->name('admin-edit-slider-img')->where('slider', '[0-9]+');
        Route::post('/agregar-imagen', 'ConfigurationController@saveSliderImg')->name('admin-save-slider-img');
        Route::get('/slider/estatus/{slider_img}', 'ConfigurationController@estatus')->name('admin-toggle-status-slider');
        Route::post('/slider/orden', 'ConfigurationController@changeOrder')->name('admin-change-order-slider');
        Route::get('/slider/eliminar/{slider}', 'ConfigurationController@deleteSlider')->name('admin-delete-slider')->where('slider', '[0-9]+');
        Route::get('/seo', 'ConfigurationController@seo')->name('admin-seo');
        Route::post('/seo/agregar', 'ConfigurationController@seoSave')->name('admin-seo-save');
        Route::get('/redes-sociales', 'ConfigurationController@socialNetworks')->name('admin-social-networks');
        Route::post('/redes-sociales', 'ConfigurationController@socialNetworksSave')->name('admin-social-networks-save');
        Route::get('/contacto', 'ConfigurationController@contact')->name('admin-contact');
        Route::post('/contacto', 'ConfigurationController@contactSave')->name('admin-contact-save');
        Route::get('/contenidos', 'ConfigurationController@contents')->name('admin-contents');
        Route::post('/contenidos', 'ConfigurationController@contentsSave')->name('admin-contents-save');
        Route::get('/envio-gratuito', 'ConfigurationController@freeShipment')->name('admin-shipment-free');
        Route::post('/envio-gratuito', 'ConfigurationController@saveFreeShipment')->name('admin-shipment-free');
        Route::get('/faq', 'ConfigurationController@faq')->name('admin-faq');
        Route::get('/faq/agregar', 'ConfigurationController@faqForm')->name('admin-add-faq-form');
        Route::post('/faq/agregar', 'ConfigurationController@faqSave')->name('admin-add-faq-save');
        Route::post('/faq/orden', 'ConfigurationController@faqOrder')->name('admin-order-faq');
        Route::get('/faq/eliminar/{faq}', 'ConfigurationController@faqDelete')->name('admin-delete-faq')->where('faq', '[0-9]+');
        Route::get('/faq/editar/{faq}', 'ConfigurationController@faqEditForm')->name('admin-edit-faq')->where('faq', '[0-9]+');
        Route::get('/devoluciones', 'ConfigurationController@refundsForm')->name('admin-config-refunds'); 
        Route::post('/devoluciones/guardar', 'ConfigurationController@refundsSave')->name('admin-config-refunds-save');
        Route::get('/credito', 'ConfigurationController@creditForm')->name('admin-config-credit');//GCUAMEA
        Route::post('/credito/guardar', 'ConfigurationController@creditSave')->name('admin-config-credit-save');//GCUAMEA
        

    }); 
    Route::group(['prefix' => 'suscripciones'], function(){
        Route::get('/','SuscribeController@listSuscribe')->name('admin-suscribe-list');        
        Route::get('/ajax-suscribe','SuscribeController@ajaxSuscribe')->name('admin-suscribe-list-ajax');//AJAX
        Route::get('/suscripcion/{id}/eliminar','SuscribeController@deleteSuscribe')->name('admin-suscribe-delete');//AJAX    
        Route::post('/suscripcion/nuevo','SuscribeController@addSuscribe')->name('website-suscribe-add');
        Route::get('/exportar/suscriptores','SuscribeController@exportSuscribers')->name('admin-export-suscribers');
    });

    Route::group(['prefix' => 'carritos-olvidados'], function(){
        Route::get('/','ShoppingCartController@listShoppingCart')->name('admin-carts-list'); 
        Route::get('/ajax-carts','ShoppingCartController@ajaxCarts')->name('admin-ajax-carts');
        Route::get('/carritos/exportar','ShoppingCartController@exportCarts')->name('admin-carts-exportClients');//export          
    });
});
