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
    // Дисциплина::course_id
    //
    // 

    public function get_arr_courses( $opop_id = NULL )
    {

        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $arr = array();
        
        $text = $this->get_companion_content( 'set-courses', $opop_id );
        
        // Разбить текст на массив строк

        $data = preg_split( '/\\r\\n?|\\n/', $text );
        $data = array_map( 'strim', $data );
        
        foreach ( $data as $item ) {
            
            $arr2 = explode( '::', $item );
            $arr2 = array_map( 'trim', $arr2 );

            if ( empty( $arr2[0] ) ) continue;
            if ( empty( $arr2[1] ) ) continue;

            if ( is_string( $arr2[0] ) && is_numeric( $arr2[1] ) ) {
                
                $arr[$arr2[0]] = $arr2[1];
                
            }

        }
        

        // if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        // $arr = array();

        // $text = $this->get_companion_content( 'set-courses', $opop_id );
        
        // // Разбить текст на массив строк

        // $data = preg_split( '/\\r\\n?|\\n/', $text );
        // $data = array_map( 'strim', $data );
        
        // foreach ( $data as $item ) {
            
        //     $arr2 = explode( '::', $item );
        //     $arr2 = array_map( 'trim', $arr2 );
        //     $arr2[] = '';
        //     $arr2[] = '';

        //     if ( empty( $arr2[0] ) ) continue;
            
        //     if ( is_string( $arr2[0] ) && empty( $arr2[1] ) && empty( $arr2[2] ) ) {
                
        //         $arr[] = array( $arr2[0], $arr2[0], NULL );
        //         continue;                
                
        //     }
            
        //     if ( is_string( $arr2[0] ) && is_numeric( $arr2[1] ) && empty( $arr2[2] ) ) {
                
        //         $arr[] = array( $arr2[0], $arr2[0], $arr2[1] );
        //         continue;                
                
        //     }
            
        //     if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && empty( $arr2[2] ) ) {
                
        //         $arr[] = array( $arr2[0], $arr2[1], NULL );
        //         continue;                
                
        //     }
            
        //     if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && is_numeric( $arr2[2] ) ) {
               
        //         $arr[] = array( $arr2[0], $arr2[1], $arr2[2] );
        //         continue;                
            
        //     }

        // }
        
        return apply_filters( 'mif_mr_set_core_arr_courses', $arr );
    }






    // //
    // // Возвращает массив из текста (post)
    // //
    // // новая::старая::course_id
    // //
    // // новая == старая          => Дисциплина                          => Дисциплина::Дисциплина
    // // новая == старая, id      => Дисциплина::123                     => Дисциплина::Дисциплина::123
    // // новая != старая          => Дисциплина 1::Дисциплина 2          => Дисциплина 1::Дисциплина 2
    // // новая != старая, id      => Дисциплина 1::Дисциплина 2::123     => Дисциплина 1::Дисциплина 2::123
    // // 

    // public function get_arr_courses( $opop_id = NULL )
    // {
    //     if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
    //     $arr = array();

    //     $text = $this->get_companion_content( 'set-courses', $opop_id );
        
    //     // Разбить текст на массив строк

    //     $data = preg_split( '/\\r\\n?|\\n/', $text );
    //     $data = array_map( 'strim', $data );
        
    //     foreach ( $data as $item ) {
            
    //         $arr2 = explode( '::', $item );
    //         $arr2 = array_map( 'trim', $arr2 );
    //         $arr2[] = '';
    //         $arr2[] = '';

    //         if ( empty( $arr2[0] ) ) continue;
            
    //         if ( is_string( $arr2[0] ) && empty( $arr2[1] ) && empty( $arr2[2] ) ) {
                
    //             $arr[] = array( $arr2[0], $arr2[0], NULL );
    //             continue;                
                
    //         }
            
    //         if ( is_string( $arr2[0] ) && is_numeric( $arr2[1] ) && empty( $arr2[2] ) ) {
                
    //             $arr[] = array( $arr2[0], $arr2[0], $arr2[1] );
    //             continue;                
                
    //         }
            
    //         if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && empty( $arr2[2] ) ) {
                
    //             $arr[] = array( $arr2[0], $arr2[1], NULL );
    //             continue;                
                
    //         }
            
    //         if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && is_numeric( $arr2[2] ) ) {
               
    //             $arr[] = array( $arr2[0], $arr2[1], $arr2[2] );
    //             continue;                
            
    //         }

    //     }
        
    //     return apply_filters( 'mif_mr_set_core_arr_courses', $arr );
    // }
    
    




    //
    // 
    //
    
    public static function set_courses_to_tree( $t = array() )
    {
        // global $mif_mr_opop;
        // $arr = array();


        // foreach ( $t['content']['set-courses']['data'] as $key => $item ) {
            
        //     if ( is_numeric( $item ) ) {
           
        // //         if ( isset( $t['content']['lib-references']['data'][$item] ) )
        //             $arr[$key] = $t['content']['lib-references']['data'][$item];
            
        //     } 
        
        // }


        $arr_raw = $t['content']['courses']['data'];
        $arr_lib = $t['content']['lib-courses']['data'];
        $arr_set = $t['content']['set-courses']['data'];

        // p($arr_set);
        // p($arr_lib);


        // $arr_param = $t['param']['parents']['data'];
        
        $index = array();

        foreach ( $arr_raw as $key => $item ) 
            if ( isset( $item['courses'] ) )
                foreach ( $item['courses'] as $key2 => $item2 ) $index[$key2] = array( 'module' => $key );
                // foreach ( $item['courses'] as $key2 => $item2 ) $index[$key2] = array( 'key' => $key, 'name' => $key2 );
        
        ksort( $index );

        $arr = array();
        foreach ( $arr_lib as $key => $item ) $arr[$item['name']][] = array( 'comp_id' => $item['comp_id'], 'from_id' => $item['from_id']  );

        // p($arr);
        // p($mif_mr_opop->current_opop_id);
        // p($index);


     

        // Ручной метод
        
        $arr_set_clean = array();
        
        foreach ( $arr_set as $key => $item ) {

            if ( empty( $arr_lib[$item] ) ) continue;
            
            $arr_set_clean[$key]['name'] = $arr_lib[$item]['name']; 
            $arr_set_clean[$key]['comp_id'] = $arr_lib[$item]['comp_id']; 
            $arr_set_clean[$key]['from_id'] = $arr_lib[$item]['from_id'];
            
        }

        foreach ( $arr_set_clean as $key => $item ) {

            if ( ! isset( $index[$key] ) ) continue;
            if ( isset( $index[$key]['course_id'] ) ) continue;
            $index[$key]['name_old'] = $item['name'];
            $index[$key]['course_id'] = $item['comp_id'];
            $index[$key]['from_id'] = $item['from_id'];
            $index[$key]['method'] = 'manual';

        }

        // Локальные

        foreach ( $arr as $key => $item ) {

            if ( ! isset( $index[$key] ) ) continue;
            if ( isset( $index[$key]['course_id'] ) ) continue;
            
            foreach ( $item as $i ) 
                if ( $i['from_id'] == get_the_ID() ) {
                    
                    $index[$key]['course_id'] = $i['comp_id'];
                    $index[$key]['from_id'] = $i['from_id'];
                    $index[$key]['method'] = 'local';
                    
                    break;
                }

        }

        // Из библиотеки

        foreach ( $arr as $key => $item ) {

            if ( ! isset( $index[$key] ) ) continue;
            if ( isset( $index[$key]['course_id'] ) ) continue;

            foreach ( $item as $i ) 
                if ( $i['from_id'] != get_the_ID() ) {

                    $index[$key]['course_id'] = $i['comp_id'];
                    $index[$key]['from_id'] = $i['from_id'];
                    $index[$key]['method'] = 'lib';

                    break;
                }

        }

        // count

        foreach ( $arr as $key => $item ) if ( isset( $index[$key] ) ) $index[$key]['count'] = count($item );




        // p($arr_set);
        

        // $index2 = array();
        // $index3 = array();

        // foreach ( $arr_lib as $key => $item ) {

        //     $index2[$item['name']][] = $key;
        //     $index3[$item['comp_id']] = $item['from_id'];
    
        //     if ( isset( $index[$item['name']] ) ) {
                
        //         $index[$item['name']]['course_id_all'][] = $item['comp_id']; 
        //         $index[$item['name']]['course_id'] = $item['comp_id']; 
        //         $index[$item['name']]['from_id'] = $item['from_id']; 
        //         $index[$item['name']]['auto'] = true; 
           
        //     }

        // }

        // foreach ( $arr_set as $item ) {
            
        //     if ( ! isset( $index[$item[0]] ) ) continue;
        //     if ( ! isset( $index2[$item[1]] ) ) continue;

        //     $index[$item[0]]['name_old'] = $item[1]; 
        //     $index[$item[0]]['course_id_all'] = $index2[$item[1]]; 
        //     $index[$item[0]]['course_id'] = ( isset( $item[2] ) ) ? $item[2] : $index2[$item[1]][0]; 
        //     $index[$item[0]]['from_id'] = ( isset( $index3[$index[$item[0]]['course_id']] ) ) ? $index3[$index[$item[0]]['course_id']] : NULL; 
        //     $index[$item[0]]['auto'] = false; 

        // }

        return apply_filters( 'mif_mr_comp_set_comp_to_tree', $index, $t );
    }




    // //
    // // 
    // //
    
    // public static function set_courses_to_tree( $t = array() )
    // {
    //     $arr = array();

    //     $arr_raw = $t['content']['courses']['data'];
    //     $arr_lib = $t['content']['lib-courses']['data'];
    //     $arr_set = $t['content']['set-courses']['data'];
        
    //     $index = array();

    //     foreach ( $arr_raw as $key => $item ) 
    //         if ( isset( $item['courses'] ) )
    //             foreach ( $item['courses'] as $key2 => $item2 ) $index[$key2] = array( 'key' => $key, 'name' => $key2 );
        
    //     $index2 = array();
    //     $index3 = array();

    //     foreach ( $arr_lib as $key => $item ) {

    //         $index2[$item['name']][] = $key;
    //         $index3[$item['comp_id']] = $item['from_id'];
    
    //         if ( isset( $index[$item['name']] ) ) {
                
    //             $index[$item['name']]['course_id_all'][] = $item['comp_id']; 
    //             $index[$item['name']]['course_id'] = $item['comp_id']; 
    //             $index[$item['name']]['from_id'] = $item['from_id']; 
    //             $index[$item['name']]['auto'] = true; 
           
    //         }

    //     }

    //     foreach ( $arr_set as $item ) {
            
    //         if ( ! isset( $index[$item[0]] ) ) continue;
    //         if ( ! isset( $index2[$item[1]] ) ) continue;

    //         $index[$item[0]]['name_old'] = $item[1]; 
    //         $index[$item[0]]['course_id_all'] = $index2[$item[1]]; 
    //         $index[$item[0]]['course_id'] = ( isset( $item[2] ) ) ? $item[2] : $index2[$item[1]][0]; 
    //         $index[$item[0]]['from_id'] = ( isset( $index3[$index[$item[0]]['course_id']] ) ) ? $index3[$index[$item[0]]['course_id']] : NULL; 
    //         $index[$item[0]]['auto'] = false; 

    //     }

    //     return apply_filters( 'mif_mr_comp_set_comp_to_tree', $index, $t );
    // }




   
    //
    // Возвращает массив из текста (post)
    //
    // kaf::reference_id
    // staff::reference_id
    // 

    public function get_arr_references( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $arr = array();
        
        $text = $this->get_companion_content( 'set-references', $opop_id );
        
        // Разбить текст на массив строк

        $data = preg_split( '/\\r\\n?|\\n/', $text );
        $data = array_map( 'strim', $data );
        
        foreach ( $data as $item ) {
            
            $arr2 = explode( '::', $item );
            $arr2 = array_map( 'trim', $arr2 );

            if ( empty( $arr2[0] ) ) continue;
            if ( empty( $arr2[1] ) ) continue;

            if ( is_string( $arr2[0] ) && is_numeric( $arr2[1] ) ) {
                
                $arr[$arr2[0]] = $arr2[1];
                
            }

        }
        
        return apply_filters( 'mif_mr_set_references_arr_comp', $arr );
    }
    
    


    //
    // 
    //
    
    public static function set_references_to_tree( $t = array() )
    {
        $arr = array();
        
        foreach ( $t['content']['set-references']['data'] as $key => $item ) {
            
            if ( is_numeric( $item ) ) {
           
                if ( isset( $t['content']['lib-references']['data'][$item] ) )
                    $arr[$key] = $t['content']['lib-references']['data'][$item];
            
            } 
        
        }

        return apply_filters( 'mif_mr_comp_set_references_to_tree', $arr, $t );
    }





}

?>