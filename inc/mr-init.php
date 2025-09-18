<?php

//
// Инит-файл плагина Matrix
// 
//


defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/mr-core.php';
include_once dirname( __FILE__ ) . '/catalog-screen.php';
// include_once dirname( __FILE__ ) . '/shortcode-opops-list.php';

// include_once dirname( __FILE__ ) . '/qm-workroom.php';
// include_once dirname( __FILE__ ) . '/qm-results.php';
// include_once dirname( __FILE__ ) . '/qm-profile.php';

// include_once dirname( __FILE__ ) . '/qm-global.php';
// include_once dirname( __FILE__ ) . '/qm-parser.php';

include_once dirname( __FILE__ ) . '/mr-download.php';
include_once dirname( __FILE__ ) . '/xlsx-core.php';
include_once dirname( __FILE__ ) . '/docx-core.php';

// include_once dirname( __FILE__ ) . '/quiz-core.php';
// include_once dirname( __FILE__ ) . '/quiz-screen.php';

// include_once dirname( __FILE__ ) . '/members-core.php';
// include_once dirname( __FILE__ ) . '/members-screen.php';

// include_once dirname( __FILE__ ) . '/invites-core.php';
// include_once dirname( __FILE__ ) . '/invites-screen.php';

// include_once dirname( __FILE__ ) . '/xml-core.php';
// include_once dirname( __FILE__ ) . '/process-process.php';

// include_once dirname( __FILE__ ) . '/process-snapshots.php';
// include_once dirname( __FILE__ ) . '/process-results.php';




class mif_mr_init extends mif_mr_core { 
// class mif_mr_init { 
    
    
    // Названия домашней старницы, профиля и др.

    protected $post_name_catalog = 'home';
    // protected $post_name_help = 'help';



    function __construct()
    {
        parent::__construct();
  
        add_filter( 'the_content', array( $this, 'add_content' ) );
  
        add_action( 'wp_ajax_catalog', array( $this, 'ajax_catalog_submit' ) );
        add_action( 'wp_ajax_nopriv_catalog', array( $this, 'ajax_catalog_submit' ) );
        
        // global $mif_mr_catalog_shortcode;
        // $mif_mr_catalog_shortcode = new mif_mr_catalog_shortcode();
        new mif_mr_catalog_shortcode();

    }
    
  
  
    //
    // Вывод теста на страницах
    //
  
  
    public function add_content( $content = '' )
    {
        if ( is_admin() ) return '';
        if ( wp_is_json_request() ) return '';

        global $post;
            
        if ( $post->post_name == $this->post_name_catalog ) {

            global $mif_mr_catalog;
            $mif_mr_catalog = new mif_mr_catalog();
            
            $mif_mr_catalog->the_catalog();

        } 

        return $content;
    }




    // 
    // Точка входа для AJAX-запросов (каталог главной страницы)
    // 


    public function ajax_catalog_submit()
    {
        // f($_REQUEST);
        check_ajax_referer( 'mif-mr' );

        if ( isset( $_REQUEST['mode'] ) && $_REQUEST['mode'] == 'stat' ) {

            // echo $this->get_catalog_stat();

        } else {

            $mif_mr_catalog = new mif_mr_catalog();
            echo  $mif_mr_catalog->get_catalog();

        }

        wp_die();
    }



}

?>