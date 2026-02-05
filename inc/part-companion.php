<?php

//
//  Связанная запись (список дисциплин, матрица компетенций, учебный план или др.)
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_part_companion extends mif_mr_part_core {
    

    function __construct()
    {

        parent::__construct();
        
    }
    
        


    //
    // Форма редактирования связанной записи (список дисциплин, матрица компетенций, учебный план или др.) 
    //

    public function companion_edit( $type = 'courses' )
    {
        $out = '';

        // $out .= '<div class="row">';
        // $out .= '<div class="col">';
        
        $out .= mif_mr_functions::get_callout( '<a href="' . '123' . '">Помощь</a>', 'warning' );
        
        // $out .= '</div>';
        // $out .= '</div>';

        $out .= '<textarea name="content" class="edit textarea mt-5" autofocus>';
        $out .= $this->get_companion_content( $type );
        $out .= '</textarea>';

        return apply_filters( 'mif_mr_core_companion_edit', $out );
    }
        

    
    // 
    // link_edit_advanced
    // 
    
    public function get_link_edit_advanced( $type )
    {
        global $mr;

        $companion_id = $this->get_companion_id( $type );
        // $f = ( empty( $companion_id ) ) ? false : true;
        
        $out = $mr->get_link_edit_advanced( $companion_id );

        // if ( $mr->user_can(3) ) {
            
        //     $out .= '<div class="row mt-1">';
        //     $out .= '<div class="col-12 p-0 mb-3">';
        //     if ( $f ) $out .= '<a href="' . get_edit_post_link( $companion_id ) . '" target="_blank">';
        //     $out .= 'Расширенный редактор';
        //     if ( $f ) $out .= '</a>';
        //     $out .= '</div>';
        //     $out .= '</div>';

        // }

        return apply_filters( 'mif_mr_part_core_link_edit_advanced', $out );
    }
    


    //
    // ID связанной записи 
    //

    public function get_companion_id( $type = 'courses', $opop_id = NULL )
    {
        // if ( $opop_id === NULL ) $opop_id = get_the_ID();
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $posts = get_posts( array(
            'post_type'     => $type,
            'post_status'   => 'publish',
            'post_parent'   => $opop_id,
            ) );
            
        $companion_id = ( isset( $posts[0]->ID ) ) ? $posts[0]->ID : NULL;
        
        return apply_filters( 'mif_mr_core_get_companion_id', $companion_id, $type, $opop_id );
    }
    
    
    
    //
    // Содержание связанной записи
    //
    
    public function get_companion_content( $type = 'courses', $opop_id = NULL )
    {
        $content = '';
        
        // if ( $opop_id === NULL ) $opop_id = get_the_ID();
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
                
        if ( $this->get_companion_id( $type, $opop_id ) !== NULL ) {
            
            $post = get_post( $this->get_companion_id( $type, $opop_id ) );
            $content = $post->post_content;

        }

        return apply_filters( 'mif_mr_core_get_companion_content', $content, $type );
    }




    // 
    //  
    //
    
    public function save( $type = 'courses', $content = NULL )
    {
        global $mif_mr_opop;
        
        $opop_id = $mif_mr_opop->get_opop_id();
        $opop_title = $mif_mr_opop->get_opop_title();

        if ( ! isset( $_REQUEST['save'] )) return false;
        // if ( $_REQUEST['save'] == 'visual' ) return false;

        if ( $content == NULL && ! isset( $_REQUEST['content'] ) ) return;
        if ( $content == NULL ) $content = sanitize_textarea_field( $_REQUEST['content'] );

        $res = $this->companion_insert( $att = array(
                    'type' => $type,
                    'opop_id' => $opop_id, 
                    'opop_title' => $opop_title,
                    'content' => $content,
                    ) );
    
        // if ( $this->get_companion_id( $type, $opop_id ) === NULL) {
            
        //     $res = wp_insert_post( array(
        //         'post_title'    => $opop_title . ' (' . $opop_id . ')',
        //         'post_type'     => $type,
        //         'post_status'   => 'publish',
        //         'post_parent'   => $opop_id,
        //         'post_content'  => $content,
        //         ) );
                
        // } else {
            
        //     $res = wp_update_post( array(
        //         'ID' => $this->get_companion_id( $type, $opop_id ),
        //         'post_content' => $content,
        //         ) );
                
        // }

        global $messages;
        $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 102 (' . $type . ')', 'danger' );
        
        if ( $res ) {

            global $tree;
            
            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
        }
        
        return $res;
    }
  

    
    
    
    public function companion_insert( $att = array() )
    {
        $type = $att['type'];
        $opop_id = $att['opop_id'];
        $opop_title = $att['opop_title'];
        $content = $att['content'];
        
        // p($type);
        // p($opop_id);
        // p($opop_title);
        // p($content);
    
        // remove_filter( 'content_save_pre', 'wp_filter_post_kses' ); 

        if ( $this->get_companion_id( $type, $opop_id ) === NULL) {
            // p('@');
            $res = wp_insert_post( array(
                'post_title'    => $opop_title . ' (' . $opop_id . ')',
                'post_type'     => $type,
                'post_status'   => 'publish',
                'post_parent'   => $opop_id,
                'post_content'  => $content,
                ) );
                
        } else {
            // p('@@');
            // remove_filter( 'content_save_pre', 'wp_filter_post_kses' ); 

            $res = wp_update_post( array(
                'ID' => $this->get_companion_id( $type, $opop_id ),
                'post_content' => $content,
                ) );
                
        }
        
        return $res;
    }
    
    
    
    
    // public function get_courses_tree( $arr )
    // {
    //     $m = new modules();
    //     $arr = $m->get_courses_tree( $arr );
    //     return $arr;
    // }
    
}


?>