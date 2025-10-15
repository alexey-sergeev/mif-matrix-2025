<?php

//
// Ядро ОПОП
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_opop_core {

    // // Количество элементов на одной странице каталога
    
    // private $opops_per_page = 9;
    
    private $param_map = array();
    private $parents_arr = array();
    
    
    
    
    function __construct()
    {
        global $messages;
        global $tree;
        
        $messages = array();
        // $this->save();
        
        $this->param_map = apply_filters( 'mif-mr-param', array(
            
            // array(
                //     'key' => array( 'description', 'd' ),
                                        //     'description' => __( 'Description', 'mif-mr' )
                                        // ),
                                        array(
                                            'key' => array( 'admins', 'a' ),
                                            'description' => __( 'Admins', 'mif-mr' )
                                        ),
                                        array(
                                            'key' => array( 'users', 'u' ),
                                            'description' => __( 'Users', 'mif-mr' )
                                        ),
                                        array(
                                            'key' => array( 'parents', 'p' ),
                                            'description' => __( 'Parents', 'mif-mr' )
                                        ),
                                        array(
                                            'key' => array( 'references', 'r' ),
                                            'description' => __( 'Reference book', 'mif-mr' )
                                        ),
                                        array(
                                            'key' => array( 'specifications', 'specs', 's' ),
                                            'description' => __( 'Specification (meta)', 'mif-mr' )
                                        ),
                                        
                                        ) );
                                        
        $tree = $this->get_tree(); 

        // p($tree);
        // p($tree['courses']);
                                    
    }
    
    
    // 
    // Tree
    // 
    
    // private function get_tree()
    public function get_tree()
    {
        global $post;
        // global $tree;
        // p($post);
        if ( $post->post_type != 'opop' ) return;
        
        // global $t;
        
        $t = array();
        
        // $tree['main']['title'] = $post->post_title;
        // $tree['main']['id'] = $post->ID;
        
        // $t = array_merge( $tree, $this->get_param_and_meta() );
        // $t = $this->get_param_and_meta();
        // 
        // p( $post );
        // p( WP_Post::get_instance( 176 ) );
        // p( $tree );
        $this->get_parents_arr( $post->ID );
        
        
        // p( $this->parents_arr );
        $this->parents_arr = array_reverse( $this->parents_arr );
        // p( $this->parents_arr );
        
        
        foreach ( $this->parents_arr as $item ) {
            
            // p($item);
            // p($this->get_param_and_meta( $item ));
            // p($this->get_courses( $item ));
            $t = array_replace_recursive( $t, $this->get_param_and_meta( $item ) ); 
            $t = array_replace_recursive( $t, $this->get_companion( 'courses' ) ); 
            $t = array_replace_recursive( $t, $this->get_companion( 'matrix' ) ); 
            $t = array_replace_recursive( $t, $this->get_companion( 'curriculum' ) ); 
            // 
            
        }




        return apply_filters( 'mif_mr_core_opop_get_tree', $t );
    }
    
    
    
    
    
    private function get_parents_arr( $opop_id )
    {
        $t = $this->get_param_and_meta( $opop_id );
        
        // p($t);
        // p($t['param']['parents']['data']);
        // p($t['main']['id']); 
        // $this->parents_arr[] = $t['main']['title'];
        if ( ! isset( $t['main']['id'] ) ) return;
        
        $this->parents_arr[] = $t['main']['id'];
        
        if ( ! isset( $t['param']['parents']['data'] ) ) return;
        
        foreach ( $t['param']['parents']['data'] as $item ) {
            
            // p($item);
            $this->get_parents_arr( $item );
            
            
        }
        
    }
    

    
    
    
    // 
    // 
    // 
    
    // public function get_param_and_meta( $opop_id = NULL )
    private function get_param_and_meta( $opop_id = NULL )
    {
        // global $post;
        // p($opop_id);        
        if ( $opop_id === NULL ) $opop_id = get_the_ID();

        $t = wp_cache_get( 'get_param_and_meta', $opop_id );
        
        if ( false === $t ) {

            $post = WP_Post::get_instance( $opop_id ); 
            
            if ( empty( $post ) ) return;
            if ( $post->post_type != 'opop' ) return;

            $t = array();
            $main_key = 'param';
            
            $t['main']['title'] = $post->post_title;
            $t['main']['id'] = $post->ID;

            $arr = explode( "\n", $post->post_content );
            $arr = array_map( 'strim', $arr );
            
            $pm_index = $this->param_map_index();
            $n = array();
            
            // p($arr);
            // p($pm_index);
            
            foreach ( $arr as $item ) {
                
                if ( empty( $item ) ) continue;
                
                if ( preg_match( '/^@@(?<key>\w+)/', $item, $m ) ) {
                    
                    $main_key = $m['key'];
                    continue;
                    
                }
                
                $grid = '';
                
                if ( preg_match( '/(^#+\s*)(?<item>.*)/', $item, $m ) ) {
                    
                    $grid = '# ';
                    $item = $m['item'];
                    
                }
                
                if ( preg_match( '/^@(?<key>\w+)(?<value>.*)/', $item, $m ) ) {
                    
                    // p($item);
                    // p($m);
                    $key = ( isset( $pm_index[$m['key']] ) ) ? $pm_index[$m['key']] : $m['key'];
                    $value = trim( $m['value'] );
                    
                    if ( $main_key == 'param' && ! isset( $n[$key] ) ) $n[$key] = -1;
                    // p($n);
                }
                // p('@');
                // p($item);
                // p($value);
                $value = ( isset( $value )) ? $value : $item;
                
                if ( ! empty( $value ) ) {
                    
                    if ( $main_key == 'param' ) {
                        
                        // p('@');
                        // p($value);
                        // p($value);
                        
                        // preg_match_all( '/\S+/', $value, $m2 );
                        // preg_match_all( '/\d+|\(.*\)/', $value, $m2 );
                        preg_match_all( '/\w+|\(.*\)/', $value, $m2 );
                        // preg_match_all( '/(\+)||(\(.*\))/', $value, $m2 );
                        // preg_match_all( '/(\(.*\))/U', $value, $m2 );

                        // p($m2);
                        $arr2 = array();
                        // $n = 0;
                        foreach ( $m2[0] as $item2 ) {
                            
                            if ( ! preg_match( '/^\(.*/', $item2 ) ) $n[$key]++;
                            
                            $t[$main_key][$key]['data'][$n[$key]] = ( isset( $t[$main_key][$key]['data'][$n[$key]] ) ) ?
                                                                    $t[$main_key][$key]['data'][$n[$key]] . ' ' . $item2 :
                                                                    $grid . $item2;
                            
                            // p($item2);
                        }
                        
                        // p($m2[0]);
                        
                    } else {
                        
                        $t[$main_key][$key]['data'][] = $grid . $value;
                        
                    }
                    
                    $t[$main_key][$key]['from_id'] = $post->ID;
                
                } 
                
                if ( isset( $value ) ) unset( $value );
                
                
            }
            

            wp_cache_set( 'get_param_and_meta', $t, $opop_id );

        }

        return apply_filters( 'mif_mr_core_opop_get_param_and_meta', $t, $opop_id );
    }
    
    
    
   
    // // 
    // // 
    // // 
    
    // private function get_courses( $opop_id = NULL )
    // {
    //     if ( $opop_id === NULL ) $opop_id = get_the_ID();
        
    //     $c = new mif_mr_companion();
    //     $m = new modules( $c->get_companion_content( 'courses', $opop_id ) );
       
    //     $t['courses'] = array(
    //                         'from_id' => $opop_id,
    //                         'data' => $m->get_arr() 
    //                     );

    //     return apply_filters( 'mif_mr_core_opop_get_courses', $t, $opop_id );
    // }
    



    
    // 
    // 
    // 
    
    private function get_companion( $part = 'courses', $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = get_the_ID();
        
        $c = new mif_mr_companion();

        switch ( $part ) {
                
                case 'courses':
                    $m = new modules( $c->get_companion_content( 'courses', $opop_id ) );
                    // $data = $m->get_arr();
                    $data = $m->get_courses();
                break;
                    
                case 'matrix':
                    $m = new matrix( $c->get_companion_content( 'matrix', $opop_id ) );
                    $data = $m->get_arr();
                break;
                        
                case 'curriculum':
                    $m = new curriculum( $c->get_companion_content( 'curriculum', $opop_id ) );
                    $data = $m->get_arr();
                break;
                
                default:
                    $data = 'none';
                break;
            
            }
       
        $t[$part] = array(
                            'from_id' => $opop_id,
                            'data' => $data 
                        );

        return apply_filters( 'mif_mr_core_opop_get_companion', $t, $part, $opop_id );
    }
    

    




    private function param_map_index()
    {
        $arr = array();

        // foreach ( $this->param_map as $item ) 
        //     foreach ( (array) $item['key'] as $key => $item2 ) 
        //         if ( $key != 0 ) $arr[ $item['key'][0] ][] = $item2;
        
        foreach ( $this->param_map as $item )
            foreach ( (array) $item['key'] as $item2 ) 
                $arr[$item2] = $item['key'][0];
        
        return $arr;    
    
    }


    // //
    // //  
    // //
    
    // private function save()
    // {
    //     if ( ! isset( $_REQUEST['save'] )) return false;
        
    //     global $post;
        
    //     $t = $this->get_param_and_meta();
    //     // global $mif_mr_opop;

    //     // p($tree);
    //     // p($_REQUEST);
    //     // $mif_mr_opop->get_tree_to_text();
        
    //     $arr = array();

    //     foreach ( $t as $main_key => $item ) {
            
    //         if ( ! in_array( $main_key, array( 'param', 'meta' ) ) ) continue;
    //         if ( isset( $item['from_id'] ) && $item['from_id'] != $post->ID ) continue;

    //         foreach ( $item as $key => $item2 )
    //             if ( isset( $item2['data'] ) ) $arr[$main_key][$key] = implode( "\n", (array) $item2['data'] );

    //     }
        
    //     // p($arr);
        
    //     foreach ( $_REQUEST as $main_key => $item ) {
            
    //         if ( ! in_array( $main_key, array( 'param', 'meta' ) ) ) continue;

    //         foreach ( $item as $key => $item2 ) $arr[$main_key][$key] = sanitize_textarea_field( $this->remove_at( $item2 ) );
            
    //     }
        
    //     // p($arr);

    //     $out = '';

    //     foreach ( $arr as $main_key => $item ) {
            
    //         $out .= '@@' . $main_key . "\n";
    //         $out .= "\n";

    //         foreach ( $item as $key => $item2 ) {

    //             // if ( ! isset( $item2['data'] ) ) continue;
    //             // p($item2['data']);

    //             $out .= '@' . $key . "\n";
    //             $out .= "\n";
    //             $out .= $item2;
    //             $out .= "\n\n";

    //             // $out .= $key . ' ' . $item2['data'] . "\n";

    //         }


    //     }

    //     $res = wp_update_post( array(

    //                                 'ID' => $post->ID,
    //                                 'post_content' => $out,

    //                             ) );


    //     global $messages;

    //     $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 101', 'danger' );

    //     wp_cache_delete( 'get_param_and_meta', $post->ID );

    //     // p($out);

    //     return $res;
    //     // return apply_filters( 'mif_mr_core_get_save_to_opop', $out );
    // }







    

    // //
    // //  
    // //
    
    // private function add( $args = array() )
    // {

    //     // if ( empty( $args['post_type'] ) ) return false;
    //     // if ( empty( $args['post_content'] ) ) return false;

    //     global $post;
        
    //     // if ( empty( $args['quiz'] ) ) $args['quiz'] = $post->ID;
    //     if ( empty( $args['post_status'] ) ) $args['post_status'] = 'publish';

    //     // В параметрах можно указать 'user' => false, тогда пользователь упоминаться не будет

    //     // if ( ! isset( $args['user'] ) ) $args['user'] = get_current_user_id();
        
    //     // Узнать имя и автора записи для будущей связанной записи
        
    //     // $quiz_post = get_post( $args['quiz'] );
    //     // $prefix = ( ! empty( $args['user'] ) ) ? $this->get_user_token( $args['user'] ) . ' — ' : '';
    //     $title = ( isset( $args['post_title'] ) ) ? $args['post_title'] : $post->post_title . ' ('. $post->ID . ')';
    //     $content = ( isset( $args['post_content'] ) ) ? $args['post_content'] : '';
    //     $author = $post->post_author;
        
    //     // Сохраняем в виде новой связанной записи
        
    //     $companion_args = array(
    //         'post_title'    => $title,
    //         'post_content'  => $content,
    //         'post_type'     => $args['post_type'],
    //         'post_status'   => $args['post_status'],
    //         'post_author'   => $author,
    //         'post_parent'   => $post->ID,
    //         'comment_status' => 'closed',
    //         'ping_status'   => 'closed', 
    //         // 'meta_input'    => array( 'owner' => $this->get_user_token( $args['user'] ) ),
    //     );

    //     // if ( ! empty ( $args['user'] ) ) $companion_args['meta_input'] = array( 'owner' => $this->get_user_token( $args['user'] ) );
    //     // if ( ! empty ( $args['post_name'] ) ) $companion_args['post_name'] = $args['post_name'];

    //     // p( esc_html( $companion_args['post_content'] ) );
    //     // remove_filter( 'content_save_pre', 'wp_filter_post_kses' ); 

    //     $companion_id = wp_insert_post( $companion_args );
    //     // $companion_id = wp_insert_post( wp_slash( $companion_args ) );
    //     // $companion_id = wp_insert_post( $companion_args );

    //     return $companion_id;
    // }





    // //
    // //  
    // //
    
    // private function remove_at( $s )
    // {
    //     return preg_replace( '/@/', '', $s );
    // }



    //
    // Получить id ОПОП
    //

    public function get_opop_id()
    {
        global $tree;
        return $tree['main']['id'];
    }


    //
    // Получить название ОПОП
    //

    public function get_opop_title()
    {
        global $tree;
        return $tree['main']['title'];
    }


    //
    // Получить URL ОПОП
    //

    public function get_opop_url()
    {
        global $tree;
        return get_permalink( $tree['main']['id'] );
    }

    

    

}

?>