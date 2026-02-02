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

        // $this->sub_default = apply_filters( 'mif-mr-sub_default', 'default' );


    }
  
        
   
    //
    // Возвращает текст компетенции из дерева
    //

    public function get_sub_arr( $comp_id )
    {
        global $tree;
        
        $arr = array();
        if ( isset( $tree['content']['lib-references']['data'][$comp_id] ) ) $arr = $tree['content']['lib-references']['data'][$comp_id];
        
        $out = array();
        
        foreach ( $arr['data'] as $item ) {
            
            $s = '';
            // $s .= '= ' . $item['name'] . "\n\n";
            
            // if ( empty( $item['data'] ) ) continue;
            
            // foreach ( $item['data'] as $item2 ) {
                
            //     $s .= $item2['name'] . '. ';
            //     $s .= $item2['descr'] . "\n\n";
                
            //     if ( empty( $item2['indicators'] ) ) continue;
                
            //     foreach ( $item2['indicators'] as $item3 ) {
                    
            //         $s .= implode( "\n", $item3 );
            //         $s .= "\n\n";
                    
            //     }
                
            //     $s .= "\n";
                
            // }
            
            $out[$item['sub_id']] = $s;

        }

        // p($arr);

        return apply_filters( 'mif_mr_companion_get_sub_arr', $out, $comp_id );
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

        return apply_filters( 'mif_mr_get_all_arr', $arr );

    }



    //
    // Возвращает массив из текста (post)
    //
    
    public function get_arr( $id )
    {
        $arr = array();
        $arr_raw = array();
       
        $post = get_post( $id );

        // p($post->post_title);
        // p($post->post_content);
        
        // $data = '== ' . $post->post_title . "\n";
        // $data .= $this->get_begin_data( $post );

        // $data .= "= " . $this->sub_default . "\n";
        // // $data .= "= default\n";
        // $data .= $post->post_content;
        
        $p = new parser();
        $arr_raw = $p->get_arr( $data, array( 'section' => $id, 'att_parts' => false, 'default' => true ) );

        $arr_raw = current( current( $arr_raw ) );
        // p($arr_raw);

        $arr['comp_id'] = $id;
        $arr['from_id'] = $post->post_parent;
        $arr['name'] = $arr_raw['name'];
        // $arr['competencies'] = '';

        // p($arr);

        if ( isset( $arr_raw['parts'] ) ) {
            
            $arr2 = array();
            
            foreach ( (array) $arr_raw['parts'] as $key => $item ) {
                
                if ( empty( $item['data']) ) continue;
            
                // p($key);
                // p($item);
                
                $arr2[$key]['sub_id'] = $item['sub_id'];
                $arr2[$key]['name'] = $item['name'];
                
                $n = 0;
                $arr3 = array();
                
                foreach ( $item['data'] as $key2 => $item2 ) {
                    
                    // if ( preg_match( '/(^.+-\d+.\s+)(.*)/', $item2, $m ) ) {
                        if ( preg_match( '/(^.+-\d+)(.\s+)(.*)/', $item2, $m ) ) {
                        // if ( preg_match( '/(^\W+-\d+)(.\s+)(.*)/', $item2, $m ) ) {
                        // p($item);

                        $arr3[] = array(
                                        'name' => $m[1],
                                        'descr' => $m[3],
                                        'category' => $item['name'],
                                    );
                        
                        $n = 0;
                        continue;

                    } 
                    
                    $arr3[array_key_last($arr3)]['indicators'][$n++] = array_map( 'trim', explode( "\n", $item2 ) );
                    
                }
                
                
                $arr2[$key]['data'] = $arr3;
                
                // p($arr3);
                
                // // if ( ! empty( $arr2 ) ) $arr[$key][$key2]['parts'][$key3]['data'] = $arr2;
                // // if ( empty( $arr[$key][$key2]['parts'][$key3]['data'] ) ) 
                // // unset( $arr[$key][$key2]['parts'][$key3] );
                
            }
            
            $arr4 = array();
            foreach ( $arr2 as $item3 ) $arr4[$item3['sub_id']] = $item3;
            // $arr['competencies'] = $arr4;
            $arr['data'] = $arr4;

        }

        // p($arr);

        return apply_filters( 'mif_mr_get_competencies_arr', $arr, $id );
    }
        



    // private function get_begin_data( $post )
    // {
    //     $data = '';
    //     if ( ! preg_match( '/^=/', $post->post_content ) ) $data .= "= " . $this->sub_default . "\n";
    //     $data .= $post->post_content;
    //     return $data;
    // }


    
    // protected $sub_default = '';
   
}


?>