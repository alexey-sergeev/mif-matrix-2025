<?php

//
// Экранные методы ОПОП
// 
//


defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/opop-core.php';
include_once dirname( __FILE__ ) . '/opop-tree-raw.php';
include_once dirname( __FILE__ ) . '/opop-tree-clean.php';
include_once dirname( __FILE__ ) . '/opop-templates.php';

class mif_mr_opop extends mif_mr_opop_tree_clean {
    

    function __construct()
    {

        parent::__construct();
        
    }
    
    


    // 
    // Показывает ОПОП
    // 

    public function the_opop()
    {
        if ( $template = locate_template( 'mr-opop.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-opop.php', false );

        }
    }




    //
    // Главное меню
    //

    public function get_menu_item( $text, $url, $level_access )
    {
        global $mr;

        $out = '';

        if ( ! $mr->user_can( $level_access ) ) return;
        
        if ( ! empty( $text ) ) 
            $out .= '<div><a href=' . $this->get_opop_url() . $url . '>' . $text . '</a></div>';
        else 
            $out .= '&nbsp;';

        return apply_filters( 'mif_mr_opop_get_menu_item', $out );
    }



    
    //
    // Часть
    //

    public function get_part()
    {
        // global $mr;
        global $wp_query;
        
        global $mif_mr_part;
        // $mif_mr_part = new mif_mr_part_core();
        $mif_mr_part = new mif_mr_part_companion();

        $out = '';
        // p($wp_query);
        $part = NULL; 
        if ( isset( $wp_query->query_vars['part'] ) ) $part = $wp_query->query_vars['part'];
        
        switch ( $part ) {
            
            case 'param':
                global $mif_mr_param;
                $mif_mr_param = new mif_mr_param();
                $mif_mr_param->the_show();
                // p('param');
            break;
            
            case 'courses':
                global $mif_mr_courses;
                $mif_mr_courses = new mif_mr_courses();
                $mif_mr_courses->the_show();
            break;
            
            case 'matrix':
                global $mif_mr_matrix;
                $mif_mr_matrix = new mif_mr_matrix();
                $mif_mr_matrix->the_show();
            break;
            
            case 'curriculum':
                global $mif_mr_curriculum;
                $mif_mr_curriculum = new mif_mr_curriculum();
                $mif_mr_curriculum->the_show();
            break;
            
            case 'attributes':
                global $mif_mr_attributes;
                $mif_mr_attributes = new mif_mr_attributes();
                $mif_mr_attributes->the_show();
            break;
            
            case 'lib-competencies':

                global $wp_query;
                
                if ( isset( $wp_query->query_vars['id'] ) ) {

                    global $mif_mr_comp;
                    // $mif_mr_comp = new mif_mr_comp();
                    $mif_mr_comp = new mif_mr_competencies_screen();
                    $mif_mr_comp->the_show();
                    
                } else {
                    
                    // global $mif_mr_lib_comp;
                    // $mif_mr_lib_comp = new mif_mr_lib_comp();
                    global $mif_mr_comp;
                    // $mif_mr_comp = new mif_mr_comp();
                    $mif_mr_comp = new mif_mr_competencies_screen();
                    
                    global $mif_mr_set_comp;
                    $mif_mr_set_comp = new mif_mr_set_comp();
                    $mif_mr_set_comp->the_show();
                    
                }
                
                break;
                
            case 'lib-courses':
                
                global $wp_query;
                    
                if ( isset( $wp_query->query_vars['id'] ) ) {
                        
                    global $mif_mr_lib_courses;
                    $mif_mr_lib_courses = new mif_mr_lib_courses_screen();
                    
                    $mif_mr_lib_courses->the_show();
                    
                } else {
                    
                    global $mif_mr_lib_courses;
                    $mif_mr_lib_courses = new mif_mr_lib_courses_screen();
                    
                    global $mif_mr_set_courses;
                    $mif_mr_set_courses = new mif_mr_set_courses();
                    $mif_mr_set_courses->the_show();

                }

            break;
                      
            case 'lib-references':
                
                global $wp_query;
                    
                if ( isset( $wp_query->query_vars['id'] ) ) {
                        
                    global $mif_mr_lib_references;
                    $mif_mr_lib_references = new mif_mr_lib_references_screen();
                    
                    $mif_mr_lib_references->the_show();
                    
                } else {
                    
                    global $mif_mr_lib_references;
                    $mif_mr_lib_references = new mif_mr_lib_references_screen();
                    
                    global $mif_mr_set_references;
                    $mif_mr_set_references = new mif_mr_set_references();
                    $mif_mr_set_references->the_show();

                }

            break;
                      
            case 'lib-templates':
                
                global $wp_query;
                    
                if ( isset( $wp_query->query_vars['id'] ) ) {
                        
                    global $mif_mr_lib_templates;
                    $mif_mr_lib_templates = new mif_mr_lib_templates_screen();
                    
                    $mif_mr_lib_templates->the_show();
                    
                } else {
                    
                    global $mif_mr_lib_templates;
                    $mif_mr_lib_templates = new mif_mr_lib_templates_screen();
                    
                    global $mif_mr_set_templates;
                    $mif_mr_set_templates = new mif_mr_set_templates();
                    $mif_mr_set_templates->the_show();

                }

            break;
            
            case 'tools':

                global $mif_mr_tools;
                $mif_mr_tools = new mif_mr_tools_core();
                $mif_mr_tools->the_show();

            break;
            
            case 'tools-curriculum':

                global $mif_mr_tools_curriculum;
                $mif_mr_tools_curriculum = new mif_mr_tools_curriculum();
                $mif_mr_tools_curriculum->the_show();

            break;
            
            case 'tools-courses':

                global $mif_mr_tools_courses;
                $mif_mr_tools_courses = new mif_mr_tools_courses();
                $mif_mr_tools_courses->the_show();

            break;
            
            
            case 'tools-templates':

                global $mif_mr_tools_templates;
                $mif_mr_tools_templates = new mif_mr_tools_templates();
                $mif_mr_tools_templates->the_show();

            break;
            
            case 'stat':
                global $tree;

                p('====== 1 ======');
                foreach ( $tree as $k => $i ) p($k);

                p('====== 2 ======');
                foreach ( $tree['content'] as $k => $i ) p($k);

                p($tree);

            break;
            
            default:
                // p('home');
                
                global $mif_mr_main;
                $mif_mr_main = new mif_mr_main();
                $mif_mr_main->the_show();
                
            break;
        
        }

        return apply_filters( 'mif_mr_opop_get_part', $out );
    }



    
    //
    // 
    //

    public function show_messages()
    {
        global $messages;

        $out = '';

        foreach ( (array) $messages as $item ) {

            $class = 'primary';
            if ( isset( $item[1] ) ) $class = $item[1];

            $out .= mif_mr_functions::get_callout( $item[0], $class );


            // $out .= '<div class="alert alert-' . $class . '" role="alert">';
            // $out .= $item[0];
            // $out .= '</div>';
            
        }

        $messages = array();
        
        return apply_filters( 'mif_mr_opop_show_messages', $out );
    }
    
    
    
    //
    // 
    //
    
    public function show_explanation( $key = NULL )
    {
        global $explanation;

        if ( ! isset( $_REQUEST['edit'] ) ) return;
        
        $out = '';
        
        if ( isset( $key ) && isset( $explanation[$key] ) ) {
            
            $out .= '<div class="col pt-2 pb-2 mt-3">';
            $out .= $explanation[$key];
            $out .= '</div>';
            
        }
        
        return apply_filters( 'mif_mr_opop_explanation', $out );
    }
    
    
    
    //
    // 
    //
    
    public function show_panel( $type = 'courses' )
    {
        if ( isset( $_REQUEST['edit'] ) ) return;

        $out = '';

        $out .= '<div class="col-auto p-0 mb-3">';
        $out .= '<form>';
        
        $out .= '<ul class="nav nav-tabs pb-0 nav-tabs-0">';
        $out .= '<li class="nav-item"><label class="nav-link mb-0 active"><input type="radio" class="d-none" name="key" value="modules" checked="" />Модули</label></li>';
        $out .= '<li class="nav-item"><label class="nav-link mb-0"><input type="radio" class="d-none" name="key" value="courses" />Дисциплины</label></li>';
        $out .= '</ul>';
            
        $out .= '<input type="hidden" name="action" value="' . $type . '" />';
        $out .= '<input type="hidden" name="opop" value="' . $this->get_opop_id() . '" />';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';     
        
        $out .= '</form>';
        $out .= '</div>';
        
        $out .= '<div class="col d-none d-sm-block mt-5 pr-0 text-end">';
        $out .= '<a href="#" class="text-secondary" id="fullsize">';
        $out .= '<i class="fa-solid fa-expand fa-2x"></i><i class="d-none fa-solid fa-compress fa-2x"></i>';
        $out .= '</a>';
        $out .= '</div>';
    
        return apply_filters( 'mif_mr_opop_panel', $out, $type );
    }


    //
    // 
    //
    
    public function show_back( $type = 'courses' )
    {
        global $wp_query;

        $out = '';

        if ( isset( $_REQUEST['back'] ) ) $type = sanitize_key( $_REQUEST['back'] );

        if ( isset( $wp_query->query_vars['id'] ) || in_array( $type, array( 'tools' ) ) ) {

            // $out .= '<div class="pb-4"><a href="' . get_permalink( $this->get_opop_id() ) . $type . '/">← Вернуться к странице раздела</a></div>';
            $out .= '<div class="pb-4"><a href="' . mif_mr_opop_core::get_opop_url() . $type . '/">← Вернуться к странице раздела</a></div>';
        
        } 
        
        return apply_filters( 'mif_mr_opop_show_back', $out, $type );
    }

}

?>