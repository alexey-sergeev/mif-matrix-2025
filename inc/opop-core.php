<?php

//
// Ядро ОПОП
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_opop_core {

    
    private $param_map = array();
    protected $parents_arr = array();
    
    
    
    function __construct()
    {
        global $messages;
        // global $tree;
        
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
                                        

        // p($tree);
        // p($tree['courses']);
                                    
    }
    
    
    
    
    // 
    // 
    // 
    
    protected function get_param_and_meta( $opop_id = NULL )
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
    
    // private function get_companion( $part = 'courses', $opop_id = NULL )
    // {
    //     if ( $opop_id === NULL ) $opop_id = get_the_ID();
        
    //     $c = new mif_mr_companion();

    //     switch ( $part ) {
                
    //             case 'courses':
    //                 $m = new modules( $c->get_companion_content( 'courses', $opop_id ) );
    //                 // $data = $m->get_arr();
    //                 $data = $m->get_courses();
    //             break;
                    
    //             case 'matrix':
    //                 $m = new matrix( $c->get_companion_content( 'matrix', $opop_id ) );
    //                 $data = $m->get_arr();
    //             break;
                        
    //             case 'curriculum':
    //                 $m = new curriculum( $c->get_companion_content( 'curriculum', $opop_id ) );
    //                 $data = $m->get_arr();
    //             break;
                
    //             default:
    //                 $data = 'none';
    //             break;
            
    //         }
       
    //     $t[$part] = array(
    //                         'from_id' => $opop_id,
    //                         'data' => $data 
    //                     );

    //     return apply_filters( 'mif_mr_core_opop_get_companion', $t, $part, $opop_id );
    // }
    

    
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