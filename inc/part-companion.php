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

        $out .= '<textarea name="content" class="edit textarea mt-4" autofocus>';
        $out .= $this->get_companion_content( $type );
        $out .= '</textarea>';

        return apply_filters( 'mif_mr_core_companion_edit', $out );
    }
        


    //
    // ID связанной записи 
    //

    public function get_companion_id( $type = 'courses', $opop_id = NULL )
    {
        // if ( $opop_id === NULL ) $opop_id = get_the_ID();
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();;
        
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
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();;
                
        if ( $this->get_companion_id( $type, $opop_id ) !== NULL ) {
            
            $post = get_post( $this->get_companion_id( $type, $opop_id ) );
            $content = $post->post_content;

        }

        return apply_filters( 'mif_mr_core_get_companion_content', $content, $type );
    }




    // 
    //  
    //
    
    public function save( $type = 'courses' )
    {
        global $mif_mr_opop;
        
        if ( ! isset( $_REQUEST['save'] )) return false;
        
        if ( $this->get_companion_id( $type, $mif_mr_opop->get_opop_id() ) === NULL) {
            
            $res = wp_insert_post( array(
                'post_title'    => $mif_mr_opop->get_opop_title() . ' (' . $mif_mr_opop->get_opop_id() . ')',
                'post_type'     => $type,
                'post_status'   => 'publish',
                'post_parent'   => $mif_mr_opop->get_opop_id(),
                'post_content'  => sanitize_textarea_field( $_REQUEST['content'] ),
                ) );
                
        } else {
            
            $res = wp_update_post( array(
                'ID' => $this->get_companion_id( $type, $mif_mr_opop->get_opop_id() ),
                'post_content' => sanitize_textarea_field( $_REQUEST['content'] ),
                ) );
                
        }
        
        global $messages;
        
        $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 102 (' . $type . ')', 'danger' );
        
        if ( $res ) {
            
            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
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