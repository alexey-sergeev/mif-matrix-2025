<?php

//
// Список дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

// class mif_mr_set_comp extends mif_mr_table {
class mif_mr_set_comp extends mif_mr_part_companion {
    
    function __construct()
    {

        parent::__construct();
        
        
        // $this->save( 'set-comp' );
        
    }
    
    
    
    // 
    // Показывает часть 
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-part-set-comp.php' ) ) {
            
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-part-set-comp.php', false );
            
        }
    }
    
    
    //
    // Показать cписок компетенций
    //
    
    // public function get_set_comp( $opop_id = NULL )
    public function show_set_comp()
    {
        // if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        $this->save( 'set-comp' );
        
        if ( isset( $_REQUEST['edit'] ) ) return $this->companion_edit( 'set-comp' );
        
        $out = '';
        
        $out .= '<h4>Компетенции в ОПОП:</h4>';
        
        // $data = $this->get_companion_content( 'set-comp' );
        
        // $out .= $data;
        global $tree;
        
        foreach ( $tree['content']['competencies']['data'] as $key => $item ) {
            
            // p( $key );
            // p( $item );


            $out .= '<div class="row">';
            
            $out .= '<div class="col col-2 col-md-1 fw-bolder">';
            $out .= $key;
            $out .= '</div>';
            
            $out .= '<div class="col mb-3">';
            $out .= $item['descr'];
            $out .= '</div>';
            
            $out .= '</div>';
            
            
        }
        
        
        // $out .= '</div>';
        
        // $this->set_comp_to_tree( $tree);
        
        return apply_filters( 'mif_mr_show_set_comp', $out );
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

    public function get_arr( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        // $this->get_index_comp( $opop_id );


        $arr = array();
        
        $text = $this->get_companion_content( 'set-comp', $opop_id );
        
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
        
        return apply_filters( 'mif_mr_comp_set_arr', $arr );
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
                            if ( $item3['name'] == $item[1] ) $arr[$item[0]] = $item3;
                
            } else {
                
                foreach ( $t['content']['lib-competencies']['data'] as $item2 ) 
                    foreach ( $item2['data'] as $item3 ) 
                        foreach ( $item3['data'] as $item4 ) 
                            if ( $item4['name'] == $item[1] ) $arr[$item[0]] = $item4;

            }

        }

        // p($arr);

        return apply_filters( 'mif_mr_comp_set_comp_to_tree', $arr, $t );
    }



    // public function get_index_comp( $opop_id = NULL )
    // {
    //     // global $tree;
    //     // p($tree);

    //     // $m = new mif_mr_comp();
    //     // $data = $m->get_all_arr( $opop_id );

    //     // $arr = array();

    //     // foreach ( $data as $item )
    //     // foreach ( $item['data'] as $item2 )
    //     // foreach ( $item2['data'] as $item3 )  $arr[$item3['name']] = $item['comp_id'];

    //     // p($arr);
        
    //     // p($data);

    // }



}

?>