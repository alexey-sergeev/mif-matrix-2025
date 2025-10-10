<?php

//
// Ядро ОПОП
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_opop_core {

    // // Количество элементов на одной странице каталога
    
    // private $opops_per_page = 9;
    
    protected $param_map = array();
        
        
        
        
    function __construct()
    {
        global $messages;
        $messages = array();
        
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

        $this->get_save_to_opop();
        $this->get_tree(); 
                                    
    }
    
    
    // 
    // Tree
    // 
    
    private function get_tree()
    {
        global $post;
        
        // p($post);
        if ( $post->post_type != 'opop' ) return;
        
        global $tree;
        
        $tree['main']['title'] = $post->post_title;
        $tree['main']['id'] = $post->ID;
        
        $tree = array_merge( $tree, $this->get_param_and_meta() );
        
        // p( $post );
        // p( WP_Post::get_instance( 176 ) );
        // p( $tree );
        
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

    


    // 
    // 
    // 
    
    private function get_param_and_meta()
    {
        // global $post;

        $post = WP_Post::get_instance( get_the_ID() ); 

        $t = array();
        $main_key = 'param';
        
        $arr = explode( "\n", $post->post_content );
        $arr = array_map( 'strim', $arr );
        
        $pm_index = $this->param_map_index();
        $n = array();
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
                
                if ( $main_key == 'param' && ! isset( $n[$key] ) ) $n[$key] = 0;
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
                    
                    preg_match_all( '/\S+/', $value, $m2 );
                    // preg_match_all( '/(#?)\S+/', $value, $m2 );
                    
                    $arr2 = array();
                    // $n = 0;
                    foreach ( $m2[0] as $item2 ) {

                        if ( ! preg_match( '/^\(.*/', $item2 ) ) 
                        $n[$key]++;
                        
                        $t[$main_key][$key]['data'][$n[$key]] = ( isset( $t[$main_key][$key]['data'][$n[$key]] ) ) ?
                            $t[$main_key][$key]['data'][$n[$key]] . ' ' . $item2 :
                            $grid . $item2;
                            
                        // p($item2);
                    }
                    
                    // p($m2[0]);

                } else {

                    $t[$main_key][$key]['data'][] = $grid . $value;
                    
                }
                
            } 
            
            if ( isset( $value ) ) unset( $value );
            
            $t[$main_key][$key]['from_id'] = $post->ID;
           
        }

        return apply_filters( 'mif_mr_core_opop_get_param_and_meta', $t );
    }




    //
    //  
    //
    
    private function get_save_to_opop()
    {
        if ( ! isset( $_REQUEST['save'] )) return false;
        
        global $post;
        
        $t = $this->get_param_and_meta();
        // global $mif_mr_opop;

        // p($tree);
        // p($_REQUEST);
        // $mif_mr_opop->get_tree_to_text();
        
        $arr = array();

        foreach ( $t as $main_key => $item ) {
            
            if ( ! in_array( $main_key, array( 'param', 'meta' ) ) ) continue;
            if ( isset( $item['from_id'] ) && $item['from_id'] != $post->ID ) continue;

            foreach ( $item as $key => $item2 )
                if ( isset( $item2['data'] ) ) $arr[$main_key][$key] = implode( "\n", (array) $item2['data'] );

        }
        
        // p($arr);
        
        foreach ( $_REQUEST as $main_key => $item ) {
            
            if ( ! in_array( $main_key, array( 'param', 'meta' ) ) ) continue;

            foreach ( $item as $key => $item2 ) $arr[$main_key][$key] = sanitize_textarea_field( $this->remove_at( $item2 ) );
            
        }
        
        // p($arr);

        $out = '';

        foreach ( $arr as $main_key => $item ) {
            
            $out .= '@@' . $main_key . "\n";
            $out .= "\n";

            foreach ( $item as $key => $item2 ) {

                // if ( ! isset( $item2['data'] ) ) continue;
                // p($item2['data']);

                $out .= '@' . $key . "\n";
                $out .= "\n";
                $out .= $item2;
                $out .= "\n\n";

                // $out .= $key . ' ' . $item2['data'] . "\n";

            }


        }

        $res = wp_update_post( array(

                                    'ID' => $post->ID,
                                    'post_content' => $out,

                                ) );


        global $messages;

        $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 101', 'danger' );

        // p($out);

        return $res;
        // return apply_filters( 'mif_mr_core_get_save_to_opop', $out );
    }




    //
    //  
    //
    
    private function remove_at( $s )
    {
        return preg_replace( '/@/', '', $s );
    }





    // //
    // // 
    // //

    // public function get_tree_to_text()
    // {
    //     global $tree;

    //     // $arr = array();
    //     $out = '';

    //     foreach ( $tree as $main_key => $item ) {
            
    //         if ( ! in_array( $main_key, array( 'param', 'meta' ) ) ) continue;
    //         // p($main_key);
    //         // p($item);
            
    //         $out .= '@@' . $main_key . "\n";
    //         $out .= "\n";

    //         foreach ( (array) $item as $key => $item2 ) {

    //             if ( $item2['from_id'] != $tree['main']['id'] ) continue;
    //             if ( ! isset( $item2['data'] ) ) continue;
    //             // p($item2['data']);

    //             $out .= '@' . $key . "\n";
    //             $out .= "\n";
    //             $out .= implode( "\n", (array) $item2['data'] );
    //             $out .= "\n\n";

    //             // $out .= $key . ' ' . $item2['data'] . "\n";

    //         }


    //     }
        

    //     p($out);



    //     return apply_filters( 'mif_mr_opop_core_get_tree_to_text', $out );
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
    // Получить URL ОПОП
    //

    public function get_opop_url()
    {
        global $tree;
        return get_permalink( $tree['main']['id'] );
    }

    

    

}

?>