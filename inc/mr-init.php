<?php

//
// Инит-файл плагина Matrix
// 
//



defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/mr-functions.php';
include_once dirname( __FILE__ ) . '/mr-types.php';
include_once dirname( __FILE__ ) . '/catalog-screen.php';
include_once dirname( __FILE__ ) . '/opop-screen.php';

include_once dirname( __FILE__ ) . '/part-core.php';
include_once dirname( __FILE__ ) . '/part-companion.php';
include_once dirname( __FILE__ ) . '/part-table.php';
// include_once dirname( __FILE__ ) . '/part-templates.php';
include_once dirname( __FILE__ ) . '/part-param.php';
include_once dirname( __FILE__ ) . '/part-main.php';
include_once dirname( __FILE__ ) . '/part-courses-list.php';
include_once dirname( __FILE__ ) . '/part-courses.php';
include_once dirname( __FILE__ ) . '/part-matrix.php';
include_once dirname( __FILE__ ) . '/part-curriculum.php';
include_once dirname( __FILE__ ) . '/part-attributes.php';

include_once dirname( __FILE__ ) . '/set-core.php';
include_once dirname( __FILE__ ) . '/set-competencies.php';
include_once dirname( __FILE__ ) . '/set-courses.php';
include_once dirname( __FILE__ ) . '/set-references.php';
include_once dirname( __FILE__ ) . '/set-templates.php';

include_once dirname( __FILE__ ) . '/companion-lib-core.php';
include_once dirname( __FILE__ ) . '/companion-lib-competencies.php';
include_once dirname( __FILE__ ) . '/companion-lib-competencies-screen.php';
include_once dirname( __FILE__ ) . '/companion-lib-courses.php';
include_once dirname( __FILE__ ) . '/companion-lib-courses-list.php';
include_once dirname( __FILE__ ) . '/companion-lib-courses-screen.php';
include_once dirname( __FILE__ ) . '/companion-lib-references.php';
include_once dirname( __FILE__ ) . '/companion-lib-references-screen.php';
include_once dirname( __FILE__ ) . '/companion-lib-templates.php';
include_once dirname( __FILE__ ) . '/companion-lib-templates-screen.php';

include_once dirname( __FILE__ ) . '/tools-core.php';
include_once dirname( __FILE__ ) . '/tools-curriculum.php';
include_once dirname( __FILE__ ) . '/tools-info.php';
include_once dirname( __FILE__ ) . '/tools-courses.php';
// include_once dirname( __FILE__ ) . '/tools-file.php';
// include_once dirname( __FILE__ ) . '/tools-templates.php';

include_once dirname( __FILE__ ) . '/lib-download.php';
include_once dirname( __FILE__ ) . '/lib-upload.php';
include_once dirname( __FILE__ ) . '/lib-xlsx.php';
include_once dirname( __FILE__ ) . '/lib-docx.php';
include_once dirname( __FILE__ ) . '/lib-processor.php';

include_once dirname( __FILE__ ) . '/make-xlsx-tpl.php';
include_once dirname( __FILE__ ) . '/make-docx-program.php';

include_once dirname( __FILE__ ) . '/classes/modules-class.php';
include_once dirname( __FILE__ ) . '/classes/matrix-class.php';
include_once dirname( __FILE__ ) . '/classes/curriculum-class.php';
include_once dirname( __FILE__ ) . '/classes/attributes-class.php';
// include_once dirname( __FILE__ ) . '/classes/assessments-class.php';
include_once dirname( __FILE__ ) . '/classes/evaluations-class.php';
include_once dirname( __FILE__ ) . '/classes/authors-class.php';
include_once dirname( __FILE__ ) . '/classes/biblio-class.php';
include_once dirname( __FILE__ ) . '/classes/content-class.php';
include_once dirname( __FILE__ ) . '/classes/it-class.php';
include_once dirname( __FILE__ ) . '/classes/mto-class.php';
include_once dirname( __FILE__ ) . '/classes/parts-class.php';
include_once dirname( __FILE__ ) . '/classes/plx-class.php';
// include_once dirname( __FILE__ ) . '/classes/.php';






class mif_mr_init extends mif_mr_types { 
// class mif_mr_init { 
    

// Названия домашней старницы, профиля и др.

    protected $post_name_catalog = 'home';
    // protected $post_name_help = 'help';
    
    
    
    function __construct()
    {
        parent::__construct();
        
        $this->rewrite_rule();
        // $this->post_types_init();
      
        
        // add_action('init', array( $this, 'rewrite_rule' ) );
        add_action( 'wp_head', array( $this, 'ajaxurl' ) );
        add_filter( 'the_content', array( $this, 'content' ) );
        
        add_action( 'wp_ajax_catalog', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_nopriv_catalog', array( $this, 'ajax' ) );

        add_action( 'wp_ajax_main', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_nopriv_main', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_courses', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_nopriv_courses', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_matrix', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_nopriv_matrix', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_curriculum', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_nopriv_curriculum', array( $this, 'ajax' ) );
        
        add_action( 'wp_ajax_lib-competencies', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_lib-courses', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_lib-references', array( $this, 'ajax' ) );
        add_action( 'wp_ajax_lib-templates', array( $this, 'ajax' ) );
        
        add_action( 'wp_ajax_tools', array( $this, 'ajax' ) );
        // add_action( 'wp_ajax_nopriv_tools', array( $this, 'ajax' ) );
        
        
        // add_action( 'wp_ajax_edit', array( $this, 'ajax' ) );
        // add_action( 'wp_ajax_cancel', array( $this, 'ajax' ) );
        // add_action( 'wp_ajax_save', array( $this, 'ajax' ) );
        // add_action( 'wp_ajax_remove', array( $this, 'ajax' ) );
        // add_action( 'wp_ajax_create', array( $this, 'ajax' ) );

        // add_action( 'wp_ajax_nopriv_edit', array( $this, 'ajax' ) );
      
        // global $mif_mr_catalog_shortcode;
        // $mif_mr_catalog_shortcode = new mif_mr_catalog_shortcode();
        new mif_mr_catalog_shortcode();
        
    }
    
    
    
    //
    // Вывод теста на страницах
    //
    
    
    public function content( $content = '' )
    {
        if ( is_admin() ) return '';
        if ( wp_is_json_request() ) return '';

        global $post;
        
        // p($post);
        
        if ( $post->post_name == $this->post_name_catalog ) {
            
            global $mif_mr_catalog;
            $mif_mr_catalog = new mif_mr_catalog();
            
            $mif_mr_catalog->the_catalog();

            return '';
            
        } 
        
        if ( $post->post_type == 'opop' ) {
            
            // p('@');
            global $mif_mr_opop;
            $mif_mr_opop = new mif_mr_opop();
            
            $mif_mr_opop->the_opop();

            return '';

        } 

        return $content;
    }




    // 
    // Точка входа для AJAX-запросов 
    // 

    public function ajax()
    // public function ajax_catalog_submit()
    {   
        // echo H// f($_REQUEST);
        // p($_REQUEST);
        
        check_ajax_referer( 'mif-mr' );
        
        // p($_REQUEST);

        if ( isset( $_REQUEST['action'] ) ) {
            
            if ( $_REQUEST['action'] == 'catalog' ) {

                $m = new mif_mr_catalog();
                echo $m->get_catalog();

            } else {
                
                global $mif_mr_opop;
                $mif_mr_opop = new mif_mr_opop();
                
                // global $tree;
                // p( $tree['main'] );

                
                if ( $_REQUEST['action'] == 'main' ) {
                    
                    $m = new mif_mr_main();
                    echo $m->get_main();
                
                } 
    
                if ( $_REQUEST['action'] == 'courses' ) {
                    
                    $m = new mif_mr_courses();
                    echo $m->get_list_courses();
                
                } 
    
                if ( $_REQUEST['action'] == 'matrix' ) {
                    
                    $m = new mif_mr_matrix();
                    echo $m->get_matrix();
                
                } 
    
                if ( $_REQUEST['action'] == 'curriculum' ) {
                    
                    $m = new mif_mr_curriculum();
                    echo $m->get_curriculum();
                
                } 

                if ( $_REQUEST['action'] == 'tools' ) {
                    
                    if ( $_REQUEST['do'] == 'remove' ) {
                        
                        $m = new mif_mr_tools_core();
                        echo $m->remove( (int) $_REQUEST['attid'] );
                        
                    }     
                
                    if ( $_REQUEST['do'] == 'save' ) {
                        
                        $m = new mif_mr_tools_curriculum();
                        echo $m->save_comp( array(
                                                'opop_title' => sanitize_text_field( $_REQUEST['opop_title'] ),
                                                'explanation' => sanitize_text_field( $_REQUEST['explanation'] ),
                                                'opop_id' => (int) $_REQUEST['opop'],
                                                'att_id' => (int) $_REQUEST['attid'],
                                                'key' => sanitize_key( $_REQUEST['key'] ),
                                                'yes' => sanitize_key( $_REQUEST['yes'] ),
                                                'data' => sanitize_textarea_field( $_REQUEST['data'] ),
                                                ) );
                        
                    }     
                
                    if ( $_REQUEST['do'] == 'analysis' ) {
                        
                        $m = new mif_mr_tools_curriculum();
                        echo $m->analysis( array(
                                                'opop_title' => sanitize_text_field( $_REQUEST['opop_title'] ),
                                                'opop_id' => (int) $_REQUEST['opop'],
                                                'att_id' => (int) $_REQUEST['attid'],
                                                'key' => sanitize_key( $_REQUEST['key'] ),
                                                'method' => sanitize_key( $_REQUEST['method'] ),
                                                // 'yes' => sanitize_key( $_REQUEST['yes'] ),
                                                // 'data' => sanitize_textarea_field( $_REQUEST['data'] ),
                                                // 'data' => $_REQUEST['data'],
                                                ) );
                        
                    }     
                
                    if ( $_REQUEST['do'] == 'reload' ) {
                        // p($_REQUEST);
                        $m = new mif_mr_tools_core();
                        echo $m->reload_box( (int) $_REQUEST['attid'] );
                        


                        // $m = new mif_mr_tools_courses();
                        // echo $m->analysis( array(
                        //                         'att_id' => (int) $_REQUEST['attid'],
                        //                         'course_id' => (int) $_REQUEST['courseid'],
                        //                         ) );
                        
                    }     
                
                    if ( $_REQUEST['do'] == 'info' ) {
                        // p($_REQUEST);
                        $m = new mif_mr_tools_courses();
                        echo $m->analysis( array(
                                                'att_id' => (int) $_REQUEST['attid'],
                                                'course_id' => (int) $_REQUEST['courseid'],
                                                ) );
                        
                    }     

                    if ( $_REQUEST['do'] == 'export' ) {
                        // p($_REQUEST);
                        $m = new mif_mr_tools_courses();
                        echo $m->export( array(
                                                // 'opop_title' => sanitize_text_field( $_REQUEST['opop_title'] ),
                                                // 'explanation' => sanitize_text_field( $_REQUEST['explanation'] ),
                                                'opop_id' => (int) $_REQUEST['opop'],
                                                'att_id' => (int) $_REQUEST['attid'],
                                                // 'key' => sanitize_key( $_REQUEST['key'] ),
                                                // 'yes' => sanitize_key( $_REQUEST['yes'] ),
                                                // 'data' => sanitize_textarea_field( $_REQUEST['data'] ),
                                                ) );
                        
                    }     


                } 


                if ( $_REQUEST['action'] == 'lib-competencies' ) {
                    
                
                    if ( in_array( $_REQUEST['do'], array( 'edit', 'cancel' ) ) ) {
                        
                        $m = new mif_mr_competencies_screen();
                        echo $m->show_comp_sub( (int) $_REQUEST['sub'], (int) $_REQUEST['comp'], (int) $_REQUEST['opop'] );
                    
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'save' ) ) ) {
                        
                        $m = new mif_mr_competencies_screen();
                        echo $m->show_comp( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'] );
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'remove' ) ) ) {
                        
                        $m = new mif_mr_competencies_screen();
                        $m->remove( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'], 'lib-competencies' );
                        echo $mif_mr_opop->show_messages();
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'create' ) ) ) {
                       
                        $m = new mif_mr_competencies_screen();
                        echo $m->show_lib_comp( (int) $_REQUEST['opop'] );
    
                    } 
                    
                } 


                if ( $_REQUEST['action'] == 'lib-courses' ) {
                    
                
                    if ( in_array( $_REQUEST['do'], array( 'edit', 'cancel' ) ) ) {
                        // p($_REQUEST);
                        $m = new mif_mr_lib_courses_screen();
                        echo $m->get_course_part( array(
                                                'course_id' => (int) $_REQUEST['comp'],
                                                'sub_id' => sanitize_key( $_REQUEST['sub'] ),
                                                'part' => sanitize_key( $_REQUEST['part'] ),
                                                'name' => sanitize_text_field( $_REQUEST['name'] ),
                                                'coll' => true,
                                                ) );
                    
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'save' ) ) ) {
                        // p($_REQUEST);
                        $m = new mif_mr_lib_courses_screen();
                        echo $m->get_course( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'] );
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'remove' ) ) ) {
                        // p($_REQUEST);
                        $m = new mif_mr_lib_courses_screen();
                        $m->remove( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'], 'lib-courses' );
                        echo $mif_mr_opop->show_messages();
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'create' ) ) ) {
                        
                        $m = new mif_mr_lib_courses_screen();
                        echo $m->get_lib_courses( (int) $_REQUEST['opop'] );
    
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'list-upd' ) ) ) {

                        $m = new mif_mr_lib_courses_screen();
                        echo $m->get_list_courses( array( 
                                                        'criteria' => sanitize_key( $_REQUEST['criteria'] ), 
                                                        's' => sanitize_text_field( $_REQUEST['s'] ),
                                                        'num' => (int) $_REQUEST['num'],
                                                    ) );
    
                    } 
                    
                } 
    
                
                if ( $_REQUEST['action'] == 'lib-references' ) {
                    
                    if ( in_array( $_REQUEST['do'], array( 'remove' ) ) ) {
                        // p($_REQUEST);
                        $m = new mif_mr_lib_references_screen();
                        $m->remove( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'], 'lib-references' );
                        echo $mif_mr_opop->show_messages();
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'create' ) ) ) {
                        
                        $m = new mif_mr_lib_references_screen();
                        echo $m->get_lib_references( (int) $_REQUEST['opop'] );
    
                    } 

                    
                } 
    
                
                
                if ( $_REQUEST['action'] == 'lib-templates' ) {
                    
                    if ( in_array( $_REQUEST['do'], array( 'remove' ) ) ) {
                        // p($_REQUEST);
                        $m = new mif_mr_lib_rtemplates_screen();
                        $m->remove( (int) $_REQUEST['comp'], (int) $_REQUEST['opop'], 'lib-templates' );
                        echo $mif_mr_opop->show_messages();
                        
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'create' ) ) ) {
                        
                        $m = new mif_mr_lib_templates_screen();
                        echo $m->get_lib_templates( (int) $_REQUEST['opop'] );
    
                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'remove-file' ) ) ) {
                        
                        // p($_REQUEST);

                        $m = new mif_mr_upload();
                        $m->remove( (int) $_REQUEST['fileid'] ); 
                        
                        $m = new mif_mr_lib_templates_screen();
                        echo $m->show_templates( (int) $_REQUEST['opop'] );

                    } 
                    
                    if ( in_array( $_REQUEST['do'], array( 'upload' ) ) ) {

                        // p($_REQUEST);
                        // p($_FILES);

                        // $m = new mif_mr_upload();
                        // $m->save( array( 'ext' => array( 'docx' ) ) ); 

                        $m = new mif_mr_lib_templates_screen();
                        echo $m->show_templates( (int) $_REQUEST['opop'] );

                        // echo '@@@';
    
                    } 
                    
                } 
    
                
                
                
    
            }
            

        }

        wp_die();
    }



    function ajaxurl()
    {   
        // $data = array(
        //     'url' => admin_url( 'admin-ajax.php' ),
        // );
        ?>
        <script>
            ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
        </script>
        <?php
    }


    // 
    // Добавляет новое правило перезаписи 
    // 

    private function rewrite_rule()
    {
        add_rewrite_tag('%part%', '([^&]+)');
        // add_rewrite_tag('%course%', '([^&]+)');
        add_rewrite_tag('%id%', '([^&]+)');
        add_rewrite_rule('^opop/([^/]*)/([^/]*)/?$','index.php?opop=$matches[1]&part=$matches[2]','top');
        // add_rewrite_rule('^opop/([^/]*)/([^/]*)/([^/]*)/?$','index.php?opop=$matches[1]&part=$matches[2]&course=$matches[3]','top');
        add_rewrite_rule('^opop/([^/]*)/([^/]*)/([^/]*)/?$','index.php?opop=$matches[1]&part=$matches[2]&id=$matches[3]','top');
    }



}

?>