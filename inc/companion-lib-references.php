<?php

//
// Библиотека справочников
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_lib_references extends mif_mr_companion_core {
    
    
    function __construct()
    {
        parent::__construct();
        
    }
    
    
    
    
    //
    // 
    //
    
    public function get_all_arr( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $arr = array();
        $list = $this->get_list_companions( 'lib-references', $opop_id );
    
        foreach ( $list as $item ) {

            $arr2 = $this->get_arr( $item['id'] );
            $arr[$arr2['comp_id']] = $arr2;

        }

        return apply_filters( 'mif_mr_get_all_arr_references', $arr );

    }



    //
    // Возвращает массив из текста (post)
    //
    
    public function get_arr( $references_id )
    {
        $arr = array();
        
        $post = get_post( $references_id );

        // $data = $post->post_content;
        $data = explode( "\n", $post->post_content );
        $data = array_map( 'strim', $data );  

        $p = new parser();

        $key = "default";
        $n = 0;

        $d = array();

        foreach ( $data as $item ) {

            if ( empty( $item ) ) continue;
            
            if ( preg_match( '/^==/', $item ) ) {
                
                $name_data = $p->parse_name( $item );
                $key = $name_data['name'];
                continue;
            
            }
            
            if ( preg_match( '/^=/', $item ) ) {
                
                $name_data = $p->parse_name( $item );
                $n++;
               
                $key2 = $name_data['att'][0];
                
                $slug = ( ! empty( $name_data['name'] ) ) ? $name_data['name'] : $key2;
                $whose = ( ! empty( $name_data['att'][1] ) ) ? $name_data['att'][1] : '';

                continue;
            
            }
            
            // if ( preg_match( '/^=(?<key>\w+)/', $item, $m ) ) {
            //     $key2 = $m['key'];
            //     continue;
            // }

            if ( $n === 0 ) {

                $d[$n]['data'][] = $this->get_data( $item );
                // $d[$key][$key2]['data'][] = $item;
                
            } else {

                $d[$n]['att'] = array( 
                                        'slug' => $slug,
                                        // 'descr' => $descr,
                                        'whose' => $whose,
                                    );

                $d[$n]['data'][0]['key'] = $key2;
                
                if ( ! isset( $d[$n]['data'][0]['value'] ) ) $d[$n]['data'][0]['value'] = '';
                $d[$n]['data'][0]['value'] .= $item . "\n";



            }


        }


        // p($data);
        // p($d);
      
        // $data = "== default\n" . $data;
        
        // $p = new parser();
        // $d = $p->get_arr( $data );
        
        // $arr2 = array();
        
        // foreach ( $d as $item ) {
            
        //     $slug = $item['default']['name'];
            
        //     if ( isset( $item['default']['data'] ) ) $arr2[] = array(
        //                                                                 'data' => $this->get_data( implode( "\n", $item['default']['data'] ) ),
        //                                                             );

        //     if ( isset( $item['default']['parts'] ) )
        //         foreach ( $item['default']['parts'] as $item2 ) {
               
        //             $slug2 = ( ! empty( $item2['att'][0] ) ) ? $item2['att'][0] : 'default';
        //             $arr2[] = array(
        //                                 'att' => ( isset( $item2['att'] ) ) ? $item2['att'] : array(),
        //                                 'data' => array( 
        //                                     array(
        //                                             'key' => ( ! empty( $item2['name'] ) ) ? $item2['name'] : NULL, 
        //                                             'value' => implode( "\n", $item2['data'] ),
        //                                         ),
        //                                 )
        //                             );
                    
        //         }

        // }

        $arr['comp_id'] = $references_id;
        $arr['from_id'] = $post->post_parent;
        $arr['name'] = $post->post_title;
        $arr['slug'] = $key;
        // $arr['slug'] = $slug;
        // $arr['data'] = $arr2;
        $arr['data'] = $d;

        // p($arr);

        return apply_filters( 'mif_mr_get_references_arr', $arr, $references_id );
    }        
    


    private function get_data( $item )
    {
        $arr = array();

        $p = new parser();
        
        $data = $p->parse_name( $item );
        $data_att = ( isset( $data['att'][0] ) ) ? $data['att'][0] : NULL;

        $arr = array( 
            'key' => $data['name'],
            'value' => $data_att,
        );

        return $arr;
    }


    // private function get_data( $d )
    // {
    //     $arr = array();

    //     $arr2 = explode( "\n", $d );
    //     $arr2 = array_map( 'strim', $arr2 );
        
    //     $p = new parser();

    //     foreach ( $arr2 as $item ) {
                
    //         if ( empty( $item ) ) continue;
            
    //         $data = $p->parse_name( $item );
            
    //         $data_att = ( isset( $data['att'][0] ) ) ? $data['att'][0] : NULL;

    //         $arr[] = array( 
    //             'key' => $data['name'],
    //             'value' => $data_att,
    //         );

    //     }

    //     return $arr;
    // }



    // //
    // // Возвращает массив из текста (post)
    // //
    
    // public function get_arr( $references_id )
    // {
    //     $arr = array();
        
    //     $post = get_post( $references_id );
        
    //     $arr2 = explode( "\n", $post->post_content );
    //     $arr2 = array_map( 'strim', $arr2 );
        
    //     $p = new parser();
    //     $arr3 = array();

    //     $is_numeric = false;
    //     $is_string = false;

    //     foreach ( $arr2 as $item ) {
                
    //         if ( empty( $item ) ) continue;
            
    //         $data = $p->parse_name( $item );
            
    //         $data_att = ( isset( $data['att'][0] ) ) ? $data['att'][0] : NULL;

    //         $arr3[] = array( 
    //             'name' => $data['name'],
    //             // 'att' => $data['att'][0],
    //             'att' => $data_att,
    //         );

    //         // p($data['att'][0]);
    //         // if ( is_numeric( $data['att'][0] ) ) $is_numeric = true;
    //         // if ( ! is_numeric( $data['att'][0] ) ) $is_string = true;
    //         if ( is_numeric( $data_att ) ) $is_numeric = true;
    //         if ( ! is_numeric( $data_att ) ) $is_string = true;


    //     }

    //     // $index = array();
    //     // foreach ( $arr3 as $i ) $index[$i['name']] = $i['att'];
        
    //     // $index_r = array();
    //     // foreach ( $arr3 as $i ) $index_r[$i['att']] = $i['name'];



    //     $arr['comp_id'] = $references_id;
    //     $arr['from_id'] = $post->post_parent;
    //     $arr['name'] = $post->post_title;
    //     $arr['is_numeric'] = $is_numeric;
    //     $arr['is_string'] = $is_string;
    //     $arr['data'] = $arr3;
    //     // $arr['index'] = $index;
    //     // $arr['index_r'] = $index_r;
        
    //     // p($arr);

    //     return apply_filters( 'mif_mr_get_references_arr', $arr, $references_id );
    // }        
    
  
}


?>