<?php
/**
Plugin Name: SeoSamba for WordPress Webmasters
Plugin URI: https://www.seosamba.com/wordpress-sitemap-seo-plugin-tool.html
Description: This plugin is a gateway to the "SeoSamba" platform. SeoSamba provides both free and premium SEO and marketing automation tools for websites owners.
Version: 1.0.7
Author: SeoSamba
Author URI: https://www.seosamba.com/
License: GPL v3
 */
?>
<?php
/*  Copyright 2017 seosamba (email: michel@seosamba.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'SEOSFWM_ROOT_FOLDER_URL', plugin_dir_url( __FILE__ ) );

require_once( rtrim( __DIR__, '/\\' ) . '/modules/admin.php' );

require_once( rtrim( __DIR__, '/\\' ) . '/includes/sitemap.php' );
require_once( rtrim( __DIR__, '/\\' ) . '/includes/widcard.php' );

class SeosambaWebmasters {

    const PLUGIN_NAME                 = 'SeoSamba for WordPress Webmasters';

    const COMPANY_NAME                = 'SeoSamba';

    const MOJO_URL = 'https://mojo.seosamba.com/';

    const EXPERT_PLUGIN_LINK = 'plugin/api/run/paymentForm/websiteId/0/pluginNameHash/dcec9b13c1de515016a3bc0f92cfa345';

    const SEOSAMBA_PLATFORM_HOST      = 'localhost';//'mojo.seosamba.com';

    const DASHBOARD_LINK              = 'seosamba-wordpress-webmaster';

    const ACCESS_KEY_FIELD            = 'wp_access_key';

    const SMFEED_CHANGEFREEQ          = 'daily';

    const PAGE_TYPE_POST              = 'post';

    const PAGE_TYPE_PAGE              = 'page';

    protected $_categoryBase          = 'category';

    const SEO_BOTTOM_ANALYTICS_SCRIPT = 'seo_bottom_analytics_script';

    protected $_params                = array();

    protected $_website_id_card       = array();

    protected $_index_object          = null;

    protected $_analytics_code        = null;

    protected $_form_utm_tags_widget_code = null;

    const WIDCARD_PREFIX = 'SeosfwmeWic';

    public function __construct() {

        $categoryBase = get_option('category_base');

        if(!empty($categoryBase)) {
            $this->_categoryBase = $categoryBase;
        }

        $this->_params = array_merge( $_POST, $_GET );
    }

    protected function _get_category_url( $category_id ) {
        return str_replace( site_url() . '/', '', get_category_link( $category_id ) );
    }

    public static function get_access_key() {
       return get_option(self::ACCESS_KEY_FIELD);
    }

    /**
     * Save/Update Website ID Card values
     */
    function mojo_website_id_card() {
        global $wpdb;

        $widcard = $this->_params;
        unset( $widcard['wp_access_key'] );

        foreach($widcard as $k => $v) {
            // Backward compatibility with toaster wic MSA field
            if($k === 'wicMSA') {
                $k = 'MSA';
            }

            $splitName = explode('_', $k);

            for($i = 0; $i < count( $splitName ); $i++) {
                $splitName[$i] = ucfirst( $splitName[$i] );
            }

            $fieldName = self::WIDCARD_PREFIX . implode( '', $splitName );
            $value = $v;

            if( is_array($v) ) {
                $value = json_encode($v);
            }

            $wpdb->query("insert into " . $wpdb->prefix."options (`option_name`, `option_value`, `autoload`)
                VALUES('" . $fieldName . "','" . $value . "', 'no') ON DUPLICATE KEY UPDATE option_value = '" . $value . "' ");
        }

        wp_send_json( array('done' => 1) );
    }

    /**
     * Save/Update Google Webmaster Tools verification token
     */
    function mojo_site_verification_code() {
        global $wpdb;

        try {
            $gwt_code = '';
            $gwt_key  = '';
            
            if( isset($this->_params['gwmtVerificationCodeClient']) ) {
                $gwt_code = filter_var( $this->_params['gwmtVerificationCodeClient'], FILTER_SANITIZE_STRING );
                $gwt_key  = 'gwmtVerificationCodeClient';
            }
            
            if( isset($this->_params['gwmtVerificationCodeAgency']) ) {
                $gwt_code = filter_var( $this->_params['gwmtVerificationCodeAgency'], FILTER_SANITIZE_STRING );
                $gwt_key  = 'gwmtVerificationCodeAgency';
            }
            
            $query = "INSERT INTO " . $wpdb->prefix."options (`option_name`, `option_value`, `autoload`)
                VALUES('" . $gwt_key . "','%s', 'no') 
                ON DUPLICATE KEY UPDATE option_value = '%s'";
            $query = $wpdb->prepare( $query, $gwt_code, $gwt_code );
            $wpdb->query( $query );

            $data = array(
                'done'    => true,
                'message' => 'Google Webmaster Tools website verification code has been added'
            );
        }
        catch( Exception $e ) {
            $data = array(
                'done'    => false,
                'message' => $e->getMessage()
            );
        }
        wp_send_json( $data );
    }

    /**
     * Insert Google Webmaster Tools verification code to page
     */
    public function gwt_code() {
        global $wpdb;
        $query = $wpdb->prepare( "SELECT option_name as name, option_value as value FROM "
            . $wpdb->prefix . "options WHERE option_name LIKE '%s'", 'gwmtVerificationCode%' );
        $codes = $wpdb->get_results( $query, ARRAY_A );

        if ( !empty( $codes ) ) {
            foreach ( $codes as $code ) {
                $codeValue = '<!-- ' . self::PLUGIN_NAME . ' -->' . "\r\n";
                $codeValue .= '<meta name="google-site-verification" content="' . $code['value'] . '" >' . "\r\n";
                echo $codeValue;
            }
        }
    }

    public function mojo_analytics_code() {
        $this->_analytics_code = $this->_params['seoBottomScripts'];
        update_option( self::SEO_BOTTOM_ANALYTICS_SCRIPT, $this->_analytics_code, 'no' );
        wp_send_json( array(
            'done'    => true,
            'message' => 'Analytics tracking code has been updated'
        ) );
    }

    public function insert_forms_utm_tags_code() {
        if( !is_admin() ) {
            $this->_form_utm_tags_widget_code = "<script>document.addEventListener('DOMContentLoaded', function(){               
               var sambaUtmParamsString = new URLSearchParams(window.location.search),
                   cname = 'sambaFormUtmTagsList';
               
               if (sambaUtmParamsString) {
                    var sambaUtmParamsList = {}; 
                    if(['utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(function(param){
                       if (sambaUtmParamsString.get(param)) { 
                            sambaUtmParamsList[param] = sambaUtmParamsString.get(param);
                       }
                    }));
                    
                    if (Object.keys(sambaUtmParamsList).length > 0) {
                        var preparedSambaForSaveUtmList = JSON.stringify(sambaUtmParamsList),
                            currentDate = new Date(),
                            cvalue = preparedSambaForSaveUtmList;
                            
                        currentDate.setTime(currentDate.getTime() + (60*60*1000));
                        var expires = 'expires='+ currentDate.toUTCString();
                        
                        document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
                    }
               }
               
               var name = cname + '=',
                   ca = document.cookie.split(';'),
                   utmTagsFound = '';
                   
               for (var i = 0; i < ca.length; i++) {
                  var c = ca[i];
                  while (c.charAt(0) == ' ') {
                      c = c.substring(1);
                  }
                  
                  if (c.indexOf(name) == 0) {
                      utmTagsFound = c.substring(name.length, c.length);
                  }
                }
                
                if (utmTagsFound) {
                    var utmFormTagListElement = document.getElementById('utmTagList');
                    if (utmFormTagListElement) {
                         utmFormTagListElement.value = utmTagsFound;
                    }
                }
                
            });</script>";
            if (!empty($this->_form_utm_tags_widget_code)) {
                ob_start(array($this, '_insert_forms_utm_tags_code'));
            }
        }
    }

    public function insert_analytics_code() {
        if( !is_admin() ) {
            $this->_analytics_code = get_option(self::SEO_BOTTOM_ANALYTICS_SCRIPT);
            if (!empty($this->_analytics_code)) {
                ob_start(array($this, '_insert_analytics'));
            }
        }
    }

    public function check_if_plugin_active() {
        wp_send_json(array(
            'done' => true,
            'plugin_active' => 'active'
        ));
    }

    private function _insert_analytics( $output ) {
        if( preg_match( '/<\/body>/i', $output ) ) {
            $body_tag_length = strlen('</body>');
            $splitLayout = preg_split('/<\/body>/i', $output, NULL, PREG_SPLIT_OFFSET_CAPTURE);
            $firstLayoutPart = substr($output, 0, $splitLayout[1][1] - $body_tag_length);
            $secondLayoutPart = substr($output, $splitLayout[1][1] - $body_tag_length);
            return $firstLayoutPart . "\r\n" . $this->_analytics_code . "\r\n" . $secondLayoutPart;
        }
        return $output;
    }

    private function _insert_forms_utm_tags_code( $output ) {
        if( preg_match( '/<\/body>/i', $output ) ) {
            $body_tag_length = strlen('</body>');
            $splitLayout = preg_split('/<\/body>/i', $output, NULL, PREG_SPLIT_OFFSET_CAPTURE);
            $firstLayoutPart = substr($output, 0, $splitLayout[1][1] - $body_tag_length);
            $secondLayoutPart = substr($output, $splitLayout[1][1] - $body_tag_length);
            return $firstLayoutPart . "\r\n" . $this->_form_utm_tags_widget_code . "\r\n" . $secondLayoutPart;
        }
        return $output;
    }

    private function _is_access_allowed() {
        $access_key = $this->_params[self::ACCESS_KEY_FIELD];
        $active_key = self::get_access_key();
        return (!empty($active_key) && $access_key === $active_key);
    }

    public function register_routes() {
        $namespace = 'seosambawebmasters/v1';

        register_rest_route($namespace, '/mojo_website_id_card/', array(
            'methods'             => 'POST',
            'callback'            => array($this, 'mojo_website_id_card'),
            'args'                => array(),
            'permission_callback' => function () {
                return $this->_is_access_allowed();
            }
        ));

        register_rest_route($namespace, '/mojo_site_verification_code/', array(
            'methods'             => 'POST',
            'callback'            => array($this, 'mojo_site_verification_code'),
            'args'                => array(),
            'permission_callback' => function () {
                return $this->_is_access_allowed();
            }
        ));

        register_rest_route( $namespace, '/mojo_analytics_code/', array(
            'methods'             => 'POST',
            'callback'            => array( $this, 'mojo_analytics_code' ),
            'args'                => array(),
            'permission_callback' => function () {
                return $this->_is_access_allowed();
            }
        ));

        register_rest_route( $namespace, '/mojo_check_if_plugin_active/', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'check_if_plugin_active' ),
            'args'                => array(),
            'permission_callback' => function () {
                return $this->_is_access_allowed();
            }
        ));
    }

    public function add_dashboard_link( $links ) {
        $updateLink = '<a href="' . esc_url( admin_url( "admin.php?page=" . self::DASHBOARD_LINK ) ).'">Dashboard</a>';
        array_unshift($links, $updateLink);
        return $links;
    }
}

/**
 * Init SeosambaWebmasters class and setup hooks
 */
$seosamba_webmasters = new SeosambaWebmasters();
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $seosamba_webmasters, 'add_dashboard_link' ), 10, 2 );
add_action( 'rest_api_init', array( $seosamba_webmasters, 'register_routes') );

$sitemap = new SeosfwmSitemap();
add_action( 'init', array( $sitemap, 'mojo_sitemap' ), 2 );

add_action( 'wp_head', array( $seosamba_webmasters, 'gwt_code' ), 1 );
add_action( 'wp_loaded', array( $seosamba_webmasters, 'insert_analytics_code' ) );
add_action('wp_loaded', array($seosamba_webmasters, 'insert_forms_utm_tags_code'));

$seosamba_webmasters_admin = new SeosfwmAdmin();
add_action( 'admin_menu', array( $seosamba_webmasters_admin, 'register_seosamba_menu_page') );

$widcard = new SeosfwmWidcard();
add_shortcode( 'widcard', array( $widcard, 'get_widcard_option') );