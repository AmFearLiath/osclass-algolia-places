<?php
/*
Plugin Name: Algolia Searches
Plugin URI: http://amfearliath.tk
Description: Make use of Algolia Places for locations
Version: 1.0.0
Author: Liath
Author URI: http://amfearliath.tk
Short Name: algolia-places
Plugin update URI: algolia-places


DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
Version 2, December 2004

Copyright (C) 2004 Sam Hocevar
14 rue de Plaisance, 75014 Paris, France
Everyone is permitted to copy and distribute verbatim or modified
copies of this license document, and changing it is allowed as long
as the name is changed.

DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

0. You just DO WHAT THE FUCK YOU WANT TO.
*/
require_once('classes/class.algolia_places.php');
  
function algolia_install() {
    algolia::newInstance()->_install();
}

function algolia_uninstall() {
    algolia::newInstance()->_uninstall();
}

function algolia_configuration() {
    osc_admin_render_plugin(osc_plugin_path(dirname(__FILE__)) . '/admin/config.php');
}

function algolia_save() {
    $data = Params::getParamsAsArray();
    algolia::newInstance()->_saveData($data);    
}

function algolia_header() {
    osc_enqueue_style('algolia-style', osc_plugin_url('algolia_places/assets/css/places.css').'places.css');    
}

function algolia_admin_page_header() {   
    echo '<h1>'.__('Algolia Places', 'banner').'</h1>';    
}

function algolia_admin_header() {
    $params = Params::getParamsAsArray();
    if (isset($params['file'])) {
        $plugin = explode("/", $params['file']);
        if ($plugin[0] == 'algolia_places') {
            osc_enqueue_style('algolia-admin-style', osc_plugin_url('algolia_places/assets/css/admin.css').'admin.css');
            osc_add_hook('admin_page_header','algolia_admin_page_header');
            osc_remove_hook('admin_page_header', 'customPageHeader');
        }
    }    
}

function algolia_footer() {
    $params = Params::getParamsAsArray();

    if (empty($params['page']) || $params['page'] == "search") {        
        echo '
        <script src="https://cdn.jsdelivr.net/places.js/1/places.min.js"></script>
        <script src="'.osc_plugin_url('algolia_places/assets/js/places.js') . 'places.js"></script>';

        if (algolia::newInstance()->_get('countries') == '1') {
            $countries = array();
            $for = explode(",", algolia::newInstance()->_countries());
            foreach($for as $v) { 
                array_push($countries, strtolower($v)); 
            }
            $countries = implode(",", $countries);
        } else {
            $countries = false;    
        }
        
        if (algolia::newInstance()->_get('languages') == '1') {        
            $locale = algolia::newInstance()->_lang(osc_current_user_locale());
        } else {
            $locale = algolia::newInstance()->_get('standardLanguage');    
        }
        
        $appid = algolia::newInstance()->_get('appid');
        $appkey = algolia::newInstance()->_get('appkey');

        echo '
        <script>
        $(document).ready(function(){    
            $("#sCountry").on("change", function(event){        
                var selCountry = $(this).val().toLowerCase();
                var parent = $("#sCity").parent("span");                    
                $("#sCity").val("");
                
                loadPlaces("'.$locale.'", (selCountry.length >=1 ? selCountry : "'.($countries ? $countries : 'none').'")'.(!empty($appid) && !empty($appkey) ? ', "'.$appid.','.$appkey.'"' : '').');      
            });        
            loadPlaces("'.$locale.'", "'.($countries ? $countries : 'none').'"'.(!empty($appid) && !empty($appkey) ? ', "'.$appid.','.$appkey.'"' : '').');    
        });
        </script>
        ';
    }        
}
    
osc_register_plugin(osc_plugin_path(__FILE__), 'algolia_install') ;

osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'algolia_uninstall') ;
osc_add_hook(osc_plugin_path(__FILE__) . '_configure', 'algolia_configuration');

if (algolia::newInstance()->_get() == '1') {
    osc_add_hook('header', 'algolia_header');
    osc_add_hook('footer', 'algolia_footer');
}

osc_add_hook('admin_header', 'algolia_admin_header');
osc_add_hook('admin_menu', 'algolia_admin_menu');

            
function algolia_admin_menu() {
    echo '
    <h3><a href="#">' . __('Algolia Places', 'algolia_places') . '</a></h3>
    <ul>
        <li><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'admin/config.php') . '">&raquo; ' . __('Settings', 'algolia_places') . '</a></li>
    </ul>';    
}                       
?>
