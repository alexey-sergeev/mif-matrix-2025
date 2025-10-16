<?php

//
// Список дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_courses extends mif_mr_companion {
    
    // private $explanation = array();


    function __construct()
    {

        parent::__construct();
        
        $this->save( 'courses' );

    }
    

    

    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-part-courses.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-part-courses.php', false );

        }
    }
    



    
    
    
    // 
    // Показывает дисциплины
    // 
    
    public function get_courses()
    {
        if ( isset( $_REQUEST['edit'] ) ) return $this->companion_edit( 'courses' );

        global $tree;
        
        // $m = new modules( $this->get_companion_content( 'courses' ) );
        // $html = $m->get_html();
        $m = new modules();
        
        $arr = $tree['content']['courses']['data'];
        if ( isset( $_REQUEST['courses'] ) ) $arr = $m->get_courses_tree( $arr );

        $html = $m->get_html( $arr );

        // p($m->get_arr() );


        $out = '';
        $out .= '<div class="col-12 p-0">';
        
        // $out .= $this->get_companion_content( 'courses' );
        // $out .= '$html';
        $out .= $html;
               
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_courses', $out );
    }
    
    
  

    // // 
    // //  
    // //
    
    // private function save()
    // {
    //     if ( ! isset( $_REQUEST['save'] )) return false;
        
    //     // global $post;
    //     global $tree;
    //     global $mif_mr_opop;



    //     // p('@@');
    //     // p($_REQUEST);


    //     // $posts = get_posts( array(
    //     //     'post_type'     => 'courses',
    //     //     'post_status'   => 'publish',
    //     //     'post_parent'   => $post->ID,
    //     // ) );

    //     // if ( empty($posts) ) {
    //     if ( $this->get_companion_id('courses') === NULL) {
            
    //         $res = wp_insert_post( array(
    //             'post_title'    => $mif_mr_opop->get_opop_title() . ' (' . $mif_mr_opop->get_opop_id() . ')',
    //             'post_type'     => 'courses',
    //             'post_status'   => 'publish',
    //             'post_parent'   => $mif_mr_opop->get_opop_id(),
    //             'post_content'  => sanitize_textarea_field( $_REQUEST['content'] ),
    //             ) );
                
    //     } else {
                
    //         // if ( isset( $posts[0]->ID ) ) {

    //             $res = wp_update_post( array(
    //                                         // 'ID' => $posts[0]->ID,
    //                                         'ID' => $this->get_companion_id('courses'),
    //                                         'post_content' => sanitize_textarea_field( $_REQUEST['content'] ),
    //                                     ) );


    //         // } else {

    //         //     $res = false;

    //         // }

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

    //     $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 102', 'danger' );

    //     if ( $res ) {

    //         $tree = array();
    //         $tree = $mif_mr_opop->get_tree();

    //     }

    //     return $res;
    // }





}

?>