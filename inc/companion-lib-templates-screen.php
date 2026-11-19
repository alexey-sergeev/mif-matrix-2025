<?php

//
//  Справочники
//  
//


defined( 'ABSPATH' ) || exit;


class mif_mr_lib_templates_screen extends mif_mr_lib_templates {
    
    
    function __construct()
    {
        parent::__construct();
   
        $this->save_all();
        add_filter( 'lib-upload-save-title', array( $this, 'set_save_title' ), 10, 3 );
        
    }
    
    
    // 
    // Показывает 
    // 
    
    public function the_show()
    {
        global $mr;

        if ( ! $mr->user_can( 2 ) ) {
            
            echo $mr->access_denied();
            return false;
        
        }

        if ( $template = locate_template( 'mr-lib-templates.php' ) ) {
           
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-lib-templates.php', false );

        }
        
    }
    
    
    
    //
    // Показать 
    //
    
    public function show_templates( $opop_id = NULL )
    {
        global $mr;
        if ( ! $mr->user_can(2) ) return;
        
        global $wp_query;
        global $tree;
        
        if ( isset( $_REQUEST['attid'] ) ) $templates_id = (int) $_REQUEST['attid'];
        if ( isset( $wp_query->query_vars['id'] ) ) $templates_id = $wp_query->query_vars['id'];

        if ( empty( $templates_id ) ) return;
        if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();

        $out = '';
        
        // Разбор формы

        $out .= $this->upload( $templates_id );

        $out .= '<div class="content-ajax">';
        // $out .= '<div class="row">';
        
        if ( isset( $tree['content']['lib-templates']['data'][$templates_id] ) ) {
            
            $arr = $tree['content']['lib-templates']['data'][$templates_id];
            // p($arr);
            $out .= '<h4 class="mb-4 mt-5 bg-body fiksa">' . $arr['name'] . '</h4>';
            // $out .= '<div class="container mt-3">';
            
            if ( isset( $_REQUEST['edit'] ) ) {

                $out .= $this->get_edit( $templates_id );

            } else {

                if ( $arr['status'] != 'missing' ) {
                    
                    
                    if ( $arr['status'] == 'empty' ) {
                
                        $out .= '<div class="mt-6 mb-6">';
                        
                        $out .= mif_mr_upload::form_upload( array( 
                                            'text' => 'Загрузка docx-шаблонов',
                                            'url' => 'lib-templates/' . $templates_id,
                                            'type' => 'templates',
                                            'attid' => $templates_id,
                                            'multiple' => true  
                                        ) );
                        
                        $out .= '</div>';
                    
                    }

                    $out .= '<div class="container mt-3">';
                    $out .= $this->get_templates_data( $arr );
                    $out .= '</div>';
                    
                    $out .= '<div class="container mt-3">';
                    $out .= $this->get_not_attached_data( $arr );
                    $out .= '</div>';
                    


                } else {
                    
                    $out .= 'Нету шаблонов. <a href="?edit">Создать</a>?';
                
                }

                
            }
            
        }
        

        // Hidden
        
        $out .= '<input type="hidden" name="opop" value="' . $opop_id . '">';
        $out .= '<input type="hidden" name="comp" value="' . $templates_id . '">';
        $out .= '<input type="hidden" name="action" value="lib-templates">';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';

        $out .= '</div>';

        return apply_filters( 'mif_mr_show_templates', $out );
    }    
       
    
        
    // 
    // 
    // 
    
    public static function get_templates_data( $arr = array(), $f = true )
    {
        $out = '';
    
        foreach ( $arr['data'] as $item ) {

            $name = ( isset( $item['name'] ) ) ? $item['name'] : '';
            $slug = ( isset( $item['att'][0] ) ) ? $item['att'][0] : '';
            $show = ( isset( $item['att'][1] ) ) ? $item['att'][1] : '';
            $param = ( isset( $item['att'][2] ) ) ? $item['att'][2] : '';

            $link_lib = mif_mr_opop_core::get_opop_url() . 'lib-templates/' . $arr['comp_id'];

            if ( ! empty( $item['file']['id'] ) ) {
                
                $slug2 = '<a href="' . $link_lib . '?download=d-tpl&slug=' . $slug .
                    '"><span class="mr-btn mr-gray rounded p-0 pl-1 pr-1 m-0 mr-1"><i class="fa-regular fa-file-lines" aria-hidden="true"></i></span>' . 
                    $slug . '</a>';

                $remove_file = '<a href="#" class="m-2 remove-file" data-slug="' . $slug . 
                                    '" data-attid="' . $arr['comp_id'] . 
                                    '" data-fileid="' . $item['file']['id'] . 
                                    '"><i class="fa-regular fa-trash-can fa-lg"></i></a>';
                
            } else {
                
                $slug2 = '<span class="mr-btn mr-red rounded p-0 pl-3 pr-3 m-0">' . $slug . '</span> — не найдено.' .
                ' <a href="#" class="reload" data-slug="' . $slug . '">Загрузить</a>'; 
                
                $remove_file = '<span class="m-2 text-light"><i class="fa-regular fa-trash-can fa-lg"></i></span>';

            }

            $out .= '<div class="row">';
            
            $out .= '<div class="col-9 bg-light p-2 mt-4 border border-light fw-semibold">' . $item['name'] . '</div>';
            $out .= '<div class="col-3 bg-light p-2 mt-4 border border-light fw-semibold text-end text-black-50">';  

            if ( $f && ! empty( $slug ) ) $out .= '<a href="#" class="m-2 reload" data-slug="' . $slug . '"><i class="fa-solid fa-repeat fa-lg"></i></a>';
            if ( $f ) $out .= $remove_file;
            
            $out .= '</div>';

            if ( ! empty( $slug ) ) $out .= '<div class="col-12 mt-3">' . $slug2 . '</div>';
            if ( ! empty( $show ) ) $out .= '<div class="col-12 mt-3">Где это показано: <span class="fw-semibold">' . $show . '</span></div>';
            if ( ! empty( $param ) ) $out .= '<div class="col-12 mt-3">Параметры: <span class="fw-semibold">' . $param . '</span></div>';
            
            // if ( $f && ! empty( $slug ) ) $out .= $this->reload_box( $arr['comp_id'], $slug );
            if ( $f && ! empty( $slug ) ) $out .= mif_mr_lib_templates_screen::reload_box( $arr['comp_id'], $slug );

            $out .= '</div>';
        }
        

        
        return apply_filters( 'mif_mr_get_templates_data', $out, $arr );
    }

        
    
    //
    //
    //

    public function get_not_attached_data( $arr = array() )
    {
        if ( empty( $arr['not_attached'] ) ) return;
        
        $out = '';

        $out .= '<div class="row">';
        $out .= '<div class="col-12 bg-light p-2 mt-4 border border-light fw-semibold">Не прикрепленные шаблоны</div>';
        
        foreach ( $arr['not_attached'] as $item ) {
            
            $slug = $item['slug'];
            
            $link_lib = mif_mr_opop_core::get_opop_url() . 'lib-templates/' . $arr['comp_id'];
            
            $out .= '<div class="col-9 mt-3"><a href="' . mif_mr_opop_core::get_opop_url() . 'lib-templates/' . $arr['comp_id'] . 
                        '?download=file&id=' . $item['id'] . 
                        '"><span class="mr-btn mr-gray rounded p-0 pl-1 pr-1 m-0 mr-1"><i class="fa-regular fa-file-lines" aria-hidden="true"></i></span>' . 
                        $slug . '</a></div>';
            
            $out .= '<div class="col-3 p-2 mt-4 fw-semibold text-end text-black-50">';  
            $out .= '<a href="#" class="m-2 remove-file" data-slug="' . $slug . 
                                '" data-attid="' . $arr['comp_id'] . 
                                '" data-fileid="' . $item['id'] . 
                                '"><i class="fa-regular fa-trash-can fa-lg"></i></a>';
            $out .= '</div>';
            
        }

        $out .= '</div>';
        
        return apply_filters( 'mif_mr_get_templates_data', $out, $arr );
    }



    //
    // 
    //

    private static function reload_box( $attid, $slug )
    {
        // !!!!!!!

        $out = '';

        $out .= '<div class="reload-box" style="display:none;">';
        $out .= '<div class="p-0 pb-0 pt-3 mt-3 mb-3">';
        
        $out .= mif_mr_upload::form_upload( array(
                                'do' => 'reload',
                                'cancel' => true,
                                'attid' => $attid,
                                'slug' => $slug,
                                'url' => 'lib-templates' . '/' . $attid, // !!!
                                'no_max_filesize' => true,
                                // 'multiple' => true,  
                                'submit' => 'Загрузить' 
                            ) );
            
        $out .= '</div>';
        $out .= '</div>';

        return $out;
    }
    

    
    //
    // Показать cписок 
    //

    public function get_lib_templates( $opop_id = NULL )
    {
        global $mr;
        if ( ! $mr->user_can(2) ) return;

        global $tree;
        global $wp_query;
        
        if ( ! empty( $wp_query->query_vars['id'] ) ) return;
        
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $this->create( $opop_id, 'lib-templates' );
        
        $arr = array();
        if ( isset( $tree['content']['lib-templates']['data'] ) ) $arr = $tree['content']['lib-templates']['data'];
    
        // $f = true;
        $f = $mr->user_can(3);
        
        $out = '';
        
        $out .= '<div class="content-ajax">';
        
        $out .= '<div class="comp container bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';
        
        $out .= $this->get_lib_head( array( 'title' => 'Библиотека шаблонов' ) );
        

        
        foreach ( $arr as $item ) {
            
            $out .= $this->get_lib_body( array( 
                                                'comp_id' => $item['comp_id'],    
                                                'name' => $item['name'],    
                                                'from_id' => $item['from_id'],    
                                                'type' => 'lib-templates',    
                                            ) );
            
        }

        if ( empty( $arr ) ) $out .= mif_mr_opop_core::get_none();
        
        // if ( $f ) 
        $out .= $this->get_lib_create( array(
                                                'action' => 'lib-templates',
                                                'button' => 'Создать комплект шаблонов',
                                                'title' => 'Название комплекта шаблонов',
                                                'hint_a' => 'Например: Шаблоны ВГСПУ',
                                                'date' => 'Данные',
                                                'hint_b' => '<a href="' . '123' . '">Помощь</a>',
                                                'f' => $f,
                                            ) );
    
        $out .= '</div>';
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_show_lib_references', $out, $opop_id );
    }    
 
    

    function set_save_title( $title, $tmp_name, $name )
    {
        return preg_replace( '/\.[^.]+$/', '', wp_basename( $name ) );
    }


    
}


?>