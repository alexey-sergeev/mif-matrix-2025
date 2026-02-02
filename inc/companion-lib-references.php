<?php

//
//  Список компетенций
//  Компетенции
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
        
        $arr2 = explode( "\n", $post->post_content );
        $arr2 = array_map( 'strim', $arr2 );
        
        $p = new parser();
        $arr3 = array();

        foreach ( $arr2 as $item ) {
            
            if ( empty( $item ) ) continue;
            
            $data = $p->parse_name( $item );
            $arr3[] = array( 
                'name' => $data['name'],
                'att' => $data['att'][0],
            );

        }

        $arr['comp_id'] = $references_id;
        $arr['from_id'] = $post->post_parent;
        $arr['name'] = $post->post_title;
        $arr['data'] = $arr3;
        
        return apply_filters( 'mif_mr_get_references_arr', $arr, $references_id );
    }        
    
  
}


?>