<?php

//
// Список дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

// class mif_mr_set_core extends mif_mr_part_companion {
class mif_mr_set_core extends mif_mr_table {
    
    function __construct()
    {

        parent::__construct();
        
    }
    
    

    
    //
    // Возвращает массив из текста (post)
    //
    // новая:старая:comp_id
    //
    // новая == старая          => УК-1             => УК-1:УК-1:NULL
    // новая == старая, id      => УК-1:123         => УК-1:УК-1:123
    // новая != старая          => УК-2:УК-1        => УК-2:УК-1:NULL
    // новая != старая, id      => УК-2:УК-1:123    => УК-2:УК-1:123
    // 

    public function get_arr_competencies( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        // $this->get_index_comp( $opop_id );


        $arr = array();
        
        $text = $this->get_companion_content( 'set-competencies', $opop_id );
        
        // Разбить текст на массив строк
        $data = preg_split( '/\\r\\n?|\\n/', $text );
        $data = array_map( 'strim', $data );
        
        foreach ( $data as $item ) {
            
            // $item .= '::';
            $arr2 = explode( ':', $item );
            $arr2 = array_map( 'trim', $arr2 );

            if ( empty( $arr2[0] ) ) continue;
            
            $arr2 = array_diff( $arr2, array( '' ) );
            $arr2 = array_values( $arr2 );
            $arr2[] = '';
            $arr2[] = '';
            
            // p($arr2);

            if ( is_string( $arr2[0] ) && empty( $arr2[1] ) && empty( $arr2[2] ) ) {
                
                $arr[] = array( $arr2[0], $arr2[0], NULL );
                continue;                
                
            }
            
            if ( is_string( $arr2[0] ) && is_numeric( $arr2[1] ) && empty( $arr2[2] ) ) {
                
                $arr[] = array( $arr2[0], $arr2[0], $arr2[1] );
                continue;                
                
            }
            
            if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && empty( $arr2[2] ) ) {
                
                $arr[] = array( $arr2[0], $arr2[1], NULL );
                continue;                
                
            }
            
            if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && is_numeric( $arr2[2] ) ) {
               
                $arr[] = array( $arr2[0], $arr2[1], $arr2[2] );
                continue;                
            
            }
            
            // p('err');

        }
        
        // p($arr);
        
        return apply_filters( 'mif_mr_set_core_arr_comp', $arr );
    }
    
    


    //
    // 
    //
    
    public static function set_comp_to_tree( $t = array() )
    {
        $arr = array();
        
        foreach ( $t['content']['set-competencies']['data'] as $item ) {

            if ( is_numeric( $item[2] ) ) {

                if ( isset( $t['content']['lib-competencies']['data'][$item[2]] ) )
                    foreach ( $t['content']['lib-competencies']['data'][$item[2]]['data'] as $item2 ) 
                        foreach ( $item2['data'] as $item3 ) 
                            if ( $item3['name'] == $item[1] ) { 
                                $item3['old_name'] = $item3['name'];
                                $item3['comp_id'] = $item[2];
                                $item3['name'] = $item[0];
                                $arr[$item[0]] = $item3;
                            }
            } else {
                
                foreach ( $t['content']['lib-competencies']['data'] as $item2 ) 
                    foreach ( $item2['data'] as $item3 ) 
                        foreach ( $item3['data'] as $item4 ) 
                            if ( $item4['name'] == $item[1] ) {
                                $item4['old_name'] = $item4['name'];
                                $item4['comp_id'] = $item2['comp_id'];
                                $item4['name'] = $item[0];
                                $arr[$item[0]] = $item4;
                            } 

            }

        }

        // p($arr);

        return apply_filters( 'mif_mr_comp_set_comp_to_tree', $arr, $t );
    }






    //
    // Возвращает массив из текста (post)
    //
    // новая::старая::course_id
    //
    // новая == старая          => Дисциплина                          => Дисциплина::Дисциплина
    // новая == старая, id      => Дисциплина::123                     => Дисциплина::Дисциплина::123
    // новая != старая          => Дисциплина 1::Дисциплина 2          => Дисциплина 1::Дисциплина 2
    // новая != старая, id      => Дисциплина 1::Дисциплина 2::123     => Дисциплина 1::Дисциплина 2::123
    // 

    public function get_arr_courses( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        // $this->get_index_comp( $opop_id );

        $arr = array();

        $text = $this->get_companion_content( 'set-courses', $opop_id );
        
        // p($text);

        // Разбить текст на массив строк
        $data = preg_split( '/\\r\\n?|\\n/', $text );
        $data = array_map( 'strim', $data );
        
        foreach ( $data as $item ) {
            
            $arr2 = explode( '::', $item );
            $arr2 = array_map( 'trim', $arr2 );
            $arr2[] = '';
            $arr2[] = '';

            if ( empty( $arr2[0] ) ) continue;
            
            // p($arr2);
            
            if ( is_string( $arr2[0] ) && empty( $arr2[1] ) && empty( $arr2[2] ) ) {
                
                $arr[] = array( $arr2[0], $arr2[0], NULL );
                continue;                
                
            }
            
            if ( is_string( $arr2[0] ) && is_numeric( $arr2[1] ) && empty( $arr2[2] ) ) {
                
                $arr[] = array( $arr2[0], $arr2[0], $arr2[1] );
                continue;                
                
            }
            
            if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && empty( $arr2[2] ) ) {
                
                $arr[] = array( $arr2[0], $arr2[1], NULL );
                continue;                
                
            }
            
            if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && is_numeric( $arr2[2] ) ) {
               
                $arr[] = array( $arr2[0], $arr2[1], $arr2[2] );
                continue;                
            
            }
            
            // p('err');

        }
        
        // p($arr);
        
        return apply_filters( 'mif_mr_set_core_arr_courses', $arr );
    }
    
    




    //
    // 
    //
    
    public static function set_courses_to_tree( $t = array() )
    {
        $arr = array();

        $arr_raw = $t['content']['courses']['data'];
        $arr_lib = $t['content']['lib-courses']['data'];
        $arr_set = $t['content']['set-courses']['data'];

        // p($arr_raw);
        // p($arr_lib);
        // p($arr_set);
        
        $index = array();

        foreach ( $arr_raw as $key => $item ) 
            foreach ( $item['courses'] as $key2 => $item2 ) $index[$key2] = array( 'key' => $key );
        
        // p($index);
        
        $index2 = array();

        foreach ( $arr_lib as $key => $item ) {

            // p($item);
            $index2[$item['name']][] = $key;

            if ( isset( $index[$item['name']] ) ) {
                $index[$item['name']]['course_id_all'][] = $item['comp_id']; 
                $index[$item['name']]['course_id'] = $item['comp_id']; 
                $index[$item['name']]['auto'] = true; 
                // $index[$item['name']]['from_id'] = $item['parent']; 
                // continue;
            }

        }

        // p($index2);
        // p($arr_set);
        
        foreach ( $arr_set as $item ) {
            
            if ( ! isset( $index[$item[0]] ) ) continue;
            if ( ! isset( $index2[$item[1]] ) ) continue;

            $index[$item[0]]['name_old'] = $item[1]; 
            $index[$item[0]]['course_id_all'] = $index2[$item[1]]; 
            $index[$item[0]]['course_id'] = ( isset( $item[2] ) ) ? $item[2] : $index2[$item[1]][0]; 
            $index[$item[0]]['auto'] = false; 


            // if ( isset( $index2[$item[2]] ) ) $index[$item[0]]['course_id'] = $item[2]; 


            // p($arr_lib[$item[1]]);
            // p($item);

        }

        // p($index);


        // p($arr);

        return apply_filters( 'mif_mr_comp_set_comp_to_tree', $index, $t );
    }



}

?>