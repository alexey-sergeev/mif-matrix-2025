<?php

//
// Ядро ОПОП
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_opop_tree_raw extends mif_mr_opop_core {
        
    
    protected $parents_arr = array();


    
    function __construct()
    {
        parent::__construct();

        // echo $_REQUEST['opop'];

        global $tree;
        $tree = $this->get_tree_raw(); 

        // p($tree);
    }
    

    // 
    // Tree
    // 
    
    public function get_tree()
    {
        $t = $this->get_tree_raw(); 
        return apply_filters( 'mif_mr_core_opop_get_tree', $t );
    }
    


    // 
    // Tree RAW
    // 
    
    public function get_tree_raw()
    {
        // global $post;
        // global $tree;
        // p($post);
        $post = get_post( $this->current_opop_id ); 
        
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
        $this->get_parents_arr( $this->current_opop_id );
        
        
        // p( $this->parents_arr );
        $this->parents_arr = array_reverse( $this->parents_arr );
        // p( $this->parents_arr );
        
        
        foreach ( $this->parents_arr as $item ) {
            
            // p($item);
            // p($this->get_param_and_meta( $item ));
            // p($this->get_courses( $item ));
            // $t = array_replace_recursive( $t, $this->get_param_and_meta( $item ) ); 
            // $t = array_replace_recursive( $t, $this->get_companion( 'courses', $item ) ); 
            // $t = array_replace_recursive( $t, $this->get_companion( 'matrix', $item ) ); 
            // $t = array_replace_recursive( $t, $this->get_companion( 'curriculum', $item ) ); 
            
            $t = $this->arr_replace( $t, $this->get_param_and_meta( $item ) ); 
            $t = $this->arr_replace( $t, $this->get_companion( 'courses', $item ) ); 
            $t = $this->arr_replace( $t, $this->get_companion( 'matrix', $item ) ); 
            $t = $this->arr_replace( $t, $this->get_companion( 'curriculum', $item ) ); 
            $t = array_replace_recursive( $t, $this->get_companion( 'competencies', $item ) ); 
            // 
            
            // p($this->get_companion( 'competencies', $item ));

        }




        return apply_filters( 'mif_mr_core_opop_get_tree_raw', $t );
    }
    
    
    
    
    
    private function arr_replace( $arr, $arr2 )
    {
        foreach ( $arr2 as $key2 => $item2 ) {
            foreach ( (array) $item2 as $key3 => $item3 )
                if ( isset( $item3['data'] ) && isset( $item3['from_id'] ) ) {

                    $arr[$key2][$key3]['from_id'] = $item3['from_id'];
                    $arr[$key2][$key3]['data'] = $item3['data'];

                    unset( $arr2[$key2][$key3]['from_id'] );
                    unset( $arr2[$key2][$key3]['data'] );

                }

        }

        $arr = array_replace_recursive( $arr, $arr2 ); 

        return $arr;
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
    
    private function get_companion( $part = 'courses', $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = get_the_ID();
        // p($opop_id);
        $c = new mif_mr_part_companion();

        switch ( $part ) {
                
                case 'courses':
                    // p('@ '.$opop_id);
                    $m = new modules( $c->get_companion_content( 'courses', $opop_id ) );
                    $data = $m->get_arr();
                    // $data = $m->get_courses();
                    // $data = $m->get_tree();
                break;
                    
                case 'matrix':
                    $m = new matrix( $c->get_companion_content( 'matrix', $opop_id ) );
                    $data = $m->get_arr();
                break;
                        
                case 'curriculum':
                    $m = new curriculum( $c->get_companion_content( 'curriculum', $opop_id ) );
                    $data = $m->get_arr();
                break;
                
                case 'competencies':
                    // $m = new curriculum( $c->get_companion_content( 'curriculum', $opop_id ) );
                    // $data = $m->get_arr();
                    $m = new mif_mr_competencies();
                    $data = $m->get_all_arr( $opop_id );
                break;
                


                default:
                    $data = 'none';
                break;
            
            }
       
        // $t['content'][$part] = array(
        $t['content'][$part] = array(
                            'from_id' => $opop_id,
                            'data' => $data 
                        );

        return apply_filters( 'mif_mr_core_opop_get_companion', $t, $part, $opop_id );
    }
    

    




    // private function param_map_index()
    // {
    //     $arr = array();

    //     // foreach ( $this->param_map as $item ) 
    //     //     foreach ( (array) $item['key'] as $key => $item2 ) 
    //     //         if ( $key != 0 ) $arr[ $item['key'][0] ][] = $item2;
        
    //     foreach ( $this->param_map as $item )
    //         foreach ( (array) $item['key'] as $item2 ) 
    //             $arr[$item2] = $item['key'][0];
        
    //     return $arr;    
    
    // }


   
}

?>