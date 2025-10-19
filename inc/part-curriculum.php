<?php

//
// Учебный план
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_curriculum extends mif_mr_companion {
    
    // private $explanation = array();


    function __construct()
    {

        parent::__construct();
        
        $this->save( 'curriculum' );

    }
    

    

    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-part-curriculum.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-part-curriculum.php', false );

        }
    }
    



    
    
    
    // 
    // Показывает учебный план
    // 
    
    public function get_curriculum()
    {
        if ( isset( $_REQUEST['edit'] ) ) return $this->companion_edit( 'curriculum' );

        $m = new curriculum( $this->get_companion_content( 'curriculum' ) );
        $html = $m->get_html();

        $out = '';
        $out .= '<div class="content-ajax col-12 p-0">';
        
        $out .= $html;

        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_curriculum', $out );
    }
    
    
  

    // // 
    // //  
    // //
    
    // private function save()
    // {
    //     if ( ! isset( $_REQUEST['save'] )) return false;
        
    //     global $post;
    //     global $tree;
    //     global $mif_mr_opop;



    //     // p('@@');
    //     // p($_REQUEST);


    //     $posts = get_posts( array(
    //         'post_type'     => 'curriculum',
    //         'post_status'   => 'publish',
    //         'post_parent'   => $post->ID,
    //     ) );

    //     if ( empty( $posts ) ) {
            
    //         $res = wp_insert_post( array(
    //             'post_title'    => $post->post_title . ' (' . $post->ID . ')',
    //             'post_type'     => 'curriculum',
    //             'post_status'   => 'publish',
    //             'post_parent'   => $post->ID,
    //             'post_content'  => sanitize_textarea_field( $_REQUEST['content'] ),
    //             ) );
                
    //     } else {
                
    //         if ( isset( $posts[0]->ID ) ) {

    //             $res = wp_update_post( array(
            
    //                                         'ID' => $posts[0]->ID,
    //                                         'post_content' => sanitize_textarea_field( $_REQUEST['content'] ),
            
    //                                     ) );


    //         } else {

    //             $res = false;

    //         }

    //     }
            
            
    //         // p('@@@');

    // //         'post_title'    => $title,
    // //         'post_content'  => $content,
    // //         'post_type'     => $args['post_type'],
    // //         'post_status'   => $args['post_status'],
    // //         'post_author'   => $author,
    // //         'post_parent'   => $post->ID,
    // //         'comment_status' => 'closed',
    // //         'ping_status'   => 'closed', 



    //     global $messages;

    //     $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 104', 'danger' );

    //     if ( $res ) {

    //         $tree = array();
    //         $tree = $mif_mr_opop->get_tree();

    //     }

    //     return $res;
    // }





}

?>