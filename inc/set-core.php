<?php

//
// Список дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_set_core extends mif_mr_part_companion {
    
    function __construct()
    {

        parent::__construct();
        
    }
    
    

    
    //
    // Возвращает массив из текста (post)
    //
    // новая:старая:id
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
            
            
            $item .= '::';
            $arr2 = explode( ':', $item );
            // p($arr2);
            
            if ( empty( $arr2[0] ) ) continue;
            
            if ( is_string( $arr2[0] ) && empty( $arr2[1] ) && empty( $arr2[2] ) ) {
                
                // $id = '123';
                // $arr[] = array( $arr2[0], $arr2[0], $id );
                $arr[] = array( $arr2[0], $arr2[0], NULL );
                // p('1');
                continue;                
                
            }
            
            if ( is_string( $arr2[0] ) && is_numeric( $arr2[1] ) && empty( $arr2[2] ) ) {
                
                $arr[] = array( $arr2[0], $arr2[0], $arr2[1] );
                // p('2');
                continue;                
                
            }
            
            if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && empty( $arr2[2] ) ) {
                
                // $id = '123';
                // $arr[] = array( $arr2[0], $arr2[1], $id );
                $arr[] = array( $arr2[0], $arr2[1], NULL );
                // p('3');
                continue;                
                
            }
            
            if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && is_numeric( $arr2[2] ) ) {
               
                $arr[] = array( $arr2[0], $arr2[1], $arr2[2] );
                // p('4');
                continue;                
            
            }
            
            // p('err');

        }
        
        // p($arr);
        
        return apply_filters( 'mif_mr_set_core_arr_comp', $arr );
    }
    
    
}

?>