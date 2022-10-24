<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()  
    {   
        // Schema::defaultStringLength(191);


        if (Schema::hasTable('config'))
        {   
            $seo_keywords = Config::select('value')->where('name','KEYWORDS')->first();
            $seo_description = Config::select('value')->where('name','DESCRIPTION')->first();
            $seo_description = Config::select('value')->where('name','DESCRIPTION')->first();
            $contact_phone = Config::select('value')->where('name','PHONE1')->first();
            $contact_phone2 = Config::select('value')->where('name','PHONE2')->first();
            $contact_email = Config::select('value')->where('name','EMAIL_CONTACT')->first();
            $social_facebook = Config::select('value')->where('name','FACEBOOK')->first();
            $social_instagram = Config::select('value')->where('name','INSTAGRAM')->first();
            $social_youtube = Config::select('value')->where('name','YOUTUBE')->first();
            $social_twitter = Config::select('value')->where('name','TWITTER')->first();
            $social_google = Config::select('value')->where('name','GOOGLEPLUS')->first();
            $social_pinterest = Config::select('value')->where('name','PINTEREST')->first();
    
            $keywords = '';
            $description = '';
    
            $view_contact_phone = '';
            $view_contact_phone2 = '';
            $view_contact_email = '';
    
            $view_social_facebook = '';
            $view_social_instagram= '';
            $view_social_youtube = '';
            $view_social_twitter = '';
            $view_social_google = '';
            $view_social_pinterest = '';
            $has_social_links = 0;
    
            if($seo_keywords){
                $keywords = $seo_keywords->value;
            }
    
            if($seo_description){
                $description = $seo_description->value;
            }
    
            view()->share('main_keywords', $keywords);
            view()->share('main_description', $description);
            
            /* Contact */
            if($contact_phone){
                $view_contact_phone = $contact_phone->value;
            }
    
            if($contact_phone2){
                $view_contact_phone2 = $contact_phone2->value;
            }
    
            if($contact_email){
                $view_contact_email = $contact_email->value;
            }
    
            view()->share('global_contact_phone', $view_contact_phone);
            view()->share('global_contact_phone_2', $view_contact_phone2);
            view()->share('global_contact_email', $view_contact_email);
    
            /* Social */
    
            if($social_facebook){
                $view_social_facebook = $social_facebook->value;
                $has_social_links = 1;
            }
    
            if($social_instagram){
                $view_social_instagram = $social_instagram->value;
                $has_social_links = 1;
            }
    
            if($social_youtube){
                $view_social_youtube = $social_youtube->value;
                $has_social_links = 1;
            }
    
            if($social_twitter){
                $view_social_twitter = $social_twitter->value;
                $has_social_links = 1;
            }
    
            if($social_google){
                $view_social_google = $social_google->value;
                $has_social_links = 1;
            }
    
            if($social_pinterest){
                $view_social_pinterest = $social_pinterest->value;
                $has_social_links = 1;
            }
    
            
    
            $social_links = array(
                "facebook" => $view_social_facebook,
                "instagram" => $view_social_instagram,
                "youtube" => $view_social_youtube,
                "twitter" => $view_social_twitter,
                "google-plus" => $view_social_google,
                "pinterest" => $view_social_pinterest
            );

            
            view()->share('global_social_links', $social_links);
            view()->share('has_social_links', $has_social_links);
            
        }


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
