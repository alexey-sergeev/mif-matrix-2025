<?php

//
//  Список компетенций
//  Компетенции
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_lib_courses extends mif_mr_companion_core {
    
    
    function __construct()
    {
        parent::__construct();
        
        $this->index_part =  apply_filters( 'mif-mr-index_part', array( 
                                                                    'content',
                                                                    'evaluations',
                                                                    'biblio',
                                                                    'it',
                                                                    'mto',
                                                                    'guidelines',
                                                                    'authors',
                                                                ) );

    }
    
    
    
    
    //
    // 
    //
    
    public function get_all_arr( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $arr = array();
        $list = $this->get_list_companions( 'lib-courses', $opop_id );
    
        foreach ( $list as $item ) {

            $arr2 = $this->get_arr( $item['id'] );
            $arr[$arr2['comp_id']] = $arr2;

        }

        return apply_filters( 'mif_mr_get_all_arr_courses', $arr );

    }



    //
    // Возвращает массив из текста (post)
    //
    
    public function get_arr( $course_id )
    {
        $arr = array();
        
        $post = get_post( $course_id );
        
        $arr2 = explode( "\n", $post->post_content );
        $arr2 = array_map( 'strim', $arr2 );
        
        $data = array();
        $n = 0;
        
        foreach ( $arr2 as $item ) {
            
            if ( preg_match( '/^==/', $item ) ) $n++; 
            if ( ! isset( $data[$n] ) ) $data[$n] = '';
            $data[$n] .= $item . "\n";
            
        }
        
        // p($data);
             
        $arr3 = array();

        foreach ( $data as $item ) {
            
            if ( preg_match( '/^==\s*(?<key>\w+)/', $item, $m ) ) {

                switch ( $m['key'] ) {
                            
                    case 'content':

                        $c = new content( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                    
                    break;
                    
                    case 'evaluations':
                        
                        $c = new evaluations( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        
                    break;
                    
                    case 'biblio':
                        
                        $c = new biblio( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        
                    break;
                        
                    case 'it':

                        $c = new it( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        
                        break;
                        
                    case 'mto':

                        $c = new mto( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );
                        
                    break;
                    
                    case 'authors':
                            
                        $c = new authors( $item );
                        $arr3 = array_merge( $c->get_arr(), $arr3 );

                    break;
                            
                    // default:
                    // break;
                
                }
    
            }; 
            
        }    
       
        $arr4 = array();
        foreach ( $this->index_part as $item ) $arr4[$item] = ( isset( $arr3[$item] ) ) ? $arr3[$item] : NULL;

        $arr['comp_id'] = $course_id;
        $arr['from_id'] = $post->post_parent;
        $arr['name'] = $post->post_title;
        $arr['data'] = $arr4;
        
        return apply_filters( 'mif_mr_get_courses_arr', $arr, $course_id );
    }        
    
    
    
    
    //
    // Возвращает текст дисциплины из дерева
    //

    public function get_sub_arr( $course_id )
    {
        global $tree;    
        
        $data = array();
        if ( isset( $tree['content']['lib-courses']['data'][$course_id] ) ) $data = $tree['content']['lib-courses']['data'][$course_id];
        
        foreach ( $data['data'] as $key => $item ) {
                
            $s = '';
            
            switch ( $key ) {
                
                case 'content':
                    
                    $s .= $item['target'] . "\n\n";
                    
                    foreach ( $item['parts'] as $item2 ) {
                        
                        $s .= '= ' . $item2['name'];
                        $s .= ' (' . $item2['cmp'] . ')';
                        $h = $item2['hours'];
                        $s .= ' (' . $h['lec'] . ', ' . $h['lab'] . ', ' . $h['prac'] . ', ' . $h['srs'] . ')';
                        $s .= "\n\n";


                        $s .= $item2['content'] . "\n\n";
                        
                        $arr2 = array();
                        for ( $i=0;  $i < 30;  $i++ ) $arr2[] = '-';
                        foreach ( (array) $item2['outcomes']['z'] as $key3 => $item3 ) $arr2[$key3 * 3] = '- ' . $item3;
                        foreach ( (array) $item2['outcomes']['u'] as $key3 => $item3 ) $arr2[$key3 * 3 + 1] = '- ' . $item3;
                        foreach ( (array) $item2['outcomes']['v'] as $key3 => $item3 ) $arr2[$key3 * 3 + 2] = '- ' . $item3;
                        while ( $arr2[ array_key_last( $arr2 ) ] == '-' ) unset( $arr2[ array_key_last( $arr2 ) ] ); 
                        
                        $s .= implode( "\n", $arr2 );
                        $s .= "\n\n";

                    }
                    
                break;
                
                case 'evaluations':
                
                    foreach ( $item as $item2 ) {
                        
                        foreach ( $item2['data'] as $item3 ) {
                            
                            $s .= $item3['name'];
                            if ( ! empty( $item3['att'] ) ) $s .= ' (' . implode( ") (", $item3['att'] ) . ')';
                            $s .= "\n";
                            
                        }

                        $s .= "\n";
    
                    }


                break;
                
                default:
                
                    foreach ( $item as $item2 ) {
                        
                        $s .= implode( "\n", $item2 );
                        $s .= "\n\n";

                    }
                
                break;
                
                
            }

            $arr[$key] = $s;
            
        }

        // p($arr);
        
        // $index = array( 
        //     'content',
        //     'evaluations',
        //     'biblio',
        //     'it',
        //     'mto',
        //     'guidelines',
        //     'authors',
        // );
        
        $arr2 = array();
        
        foreach ( $this->index_part as $item ) {
            
            if ( isset( $arr[$item] ) ) {
                
                $arr2[$item] = $arr[$item];
                unset( $arr[$item] );
                
            }
            
        }
        
        $arr2 = array_merge( $arr2, $arr );
        // p($arr2);
        
        return apply_filters( 'mif_mr_companion_course_get_sub_arr', $arr2, $course_id );
    }
    

    protected $index_part = array();
    
}


?>