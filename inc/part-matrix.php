<?php

//
// Матрица компетенций
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_matrix extends mif_mr_companion {
    
    // private $explanation = array();


    function __construct()
    {

        parent::__construct();
        
        add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        add_filter( 'mif-mr-tbody-class-tr', array( $this, 'filter_tbody_class_tr'), 10, 2 );
        add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );
        
        add_filter( 'mif-mr-thead-row', array( $this, 'filter_thead_row'), 10, 2 );
        // add_filter( 'mif-mr-thead-colspan', array( $this, 'filter_tbody_colspan'), 10 );

        $this->save( 'matrix' );

    }
    

    

    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-part-matrix.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-part-matrix.php', false );

        }
    }
    



    
    
    
    // 
    // Показывает матрицу компетенций
    // 
    
    public function get_matrix()
    {
        global $tree;
        if ( isset( $_REQUEST['edit'] ) ) return $this->companion_edit( 'matrix' );
        
        
        $arr = $tree['content']['matrix']['data'];
        $arr2 = $tree['content']['courses']['data'];
        
        if ( isset( $_REQUEST['key'] ) && $_REQUEST['key'] == 'courses' ) {
            
            $m2 = new modules();
            $arr2 = $m2->get_courses_tree( $arr2 );
            
        }
        
        $html = '';
        
        $h = new mif_mr_html();
        $html .= $h->get_html( $arr2 );
        
        // $m = new matrix();
        // $html = $m->get_html( $arr );
        // $html .= $m->get_html_arr( $arr, $arr2 );
        // $html .= $m->get_html( $arr, $arr2 );
        
        $out = '';
        $out .= '<div class="content-ajax col-12 p-0">';
        
        $out .= $html;
        
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_matrix', $out );
    }
    
    
    


    public function filter_tbody_class_tr( $class, $key2 )
    {
        global $tree;
        $matrix_arr = $tree['content']['matrix']['data'];
        $cmp = $this->get_cmp( $matrix_arr );

        $arr = array();
        if ( ! empty($class) ) $arr[] = $class;

        // // p($courses_arr[$key]['courses']);
        // foreach ( $courses_arr[$key]['courses'] as $key2 => $item ) {

        //     p($key2);
        //     // p($matrix_arr);

        // }
        // p('@');
        foreach ( $cmp as $c ) {

            $arr2 = ( isset( $matrix_arr[$key2] ) ) ? $matrix_arr[$key2] : array();
            if ( in_array( $c, $arr2 ) ) $arr[] = $c;

        }

        // p($arr);
        return implode( ' ', $arr );
    }




    public function filter_tbody_colspan( $n )
    {
        global $tree;
        $matrix_arr = $tree['content']['matrix']['data'];
        $cmp = $this->get_cmp( $matrix_arr );

        return $n + count($cmp);
    }




    public function filter_tbody_col( $arr, $key, $key2, $courses_arr )
    {
        // p($key);
        // p($key2);
        global $tree;
        $matrix_arr = $tree['content']['matrix']['data'];
        $cmp = $this->get_cmp( $matrix_arr );

        foreach ( $cmp as $c ) {

            $arr2 = ( isset( $matrix_arr[$key2] ) ) ? $matrix_arr[$key2] : array();
            $text = ( in_array( $c, $arr2 ) ) ? '1': '';
            $class = ( ! empty( $text ) ) ? 'cmp on': 'cmp';
            $title = ( ! empty( $text ) ) ? $c : '';

            $arr[] = mif_mr_html::add_to_col( $text, array('elem' => 'td', 'class' => $class, 'title' => $title) );

        }


        return $arr;
    }



    public function filter_thead_row( $arr, $courses_arr )
    {
        $arr = array();
        // p($key);
        // p($key2);
        global $tree;
        $matrix_arr = $tree['content']['matrix']['data'];
        $cmp = $this->get_cmp( $matrix_arr );

        foreach ( $cmp as $item ) {

            $data = explode( '-', $item );
            $index[$data[0]][] = $data[1];
            $data_cmp[$data[0]][] = $item;

        }

        $arr2 = array();
        $arr2[] = mif_mr_html::add_to_col( '', array( 'elem' => 'th', 'rowspan' => '2' ) ); 
        $arr2[] = mif_mr_html::add_to_col( '', array( 'elem' => 'th', 'rowspan' => '2' ) ); 
        
        foreach ( $index as $key => $numerics ) {
            $c = count( $numerics );
            $arr2[] = mif_mr_html::add_to_col( $key, array( 'elem' => 'th', 'colspan' => $c ) ); 
        }
        // $row2 .= "<th class=\"selectable\" =\"" . $data_cmp[$key][$key2] . "\">$item</th>\n";
        
        $arr[] = mif_mr_html::add_to_row( $arr2, array( 'elem' => 'tr' ) );

        $arr2 = array();

        foreach ( $index as $key => $numerics )
            foreach ( $numerics as $key2 => $item ) 
                $arr2[] = mif_mr_html::add_to_col( $item, array( 'elem' => 'th', 'class' => 'selectable', 'data-cmp' => $data_cmp[$key][$key2] ) ); 
        
        $arr[] = mif_mr_html::add_to_row( $arr2, array( 'elem' => 'tr' ) );
    
        return $arr;
    }
    
    
    // 
    // Функция возвращает все возможные компетенции
    // 

    private function get_cmp( $matrix_arr )
    {
        $arr = array();
        // $arr[] = $this->acceptable_cmp;
        foreach ( $matrix_arr as $item ) $arr = array_merge( $arr, $item );
        
        $c = new cmp();
        $arr2 = $c->get_cmp( $arr, 'arr' );
    
        return $arr2;
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
    //         'post_type'     => 'matrix',
    //         'post_status'   => 'publish',
    //         'post_parent'   => $post->ID,
    //     ) );

    //     if ( empty($posts) ) {
            
    //         $res = wp_insert_post( array(
    //             'post_title'    => $post->post_title . ' (' . $post->ID . ')',
    //             'post_type'     => 'matrix',
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

    //     $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 103', 'danger' );

    //     if ( $res ) {

    //         $tree = array();
    //         $tree = $mif_mr_opop->get_tree();

    //     }

    //     return $res;
    // }





}

?>