<?php

//
// Настройки компетенций
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_set_comp extends mif_mr_set_core {
    
    function __construct()
    {

        parent::__construct();
        
        $this->save( 'set-competencies' );

    }
    
    
    
    // 
    // Показывает часть 
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-set-competencies.php' ) ) {
            
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-set-competencies.php', false );
            
        }
    }
    
    
    //
    // Показать cписок компетенций
    //
    
    public function show_set_comp()
    {
        global $tree;
        
        $this->save( 'set-competencies', $this->compose_set_comp() );
        // p($_REQUEST);

        $out = '';
        
        if ( isset( $_REQUEST['edit'] ) ) {
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            
            if ( $_REQUEST['edit'] == 'visual' ) {
                
                $out .= $this->edit_visual();
                
            } else {
                
                $out .= $this->companion_edit( 'set-competencies' );
                
            }
            
            $out .= '</div>';
            $out .= '</div>';
            
        } else {
            
            $out .= '<div class="row fiksa">';
            $out .= '<div class="col p-0">';
            $out .= '<h4 class="mb-4 mt-0 pt-3 pb-5 bg-body">Компетенции в ОПОП:</h4>';
            $out .= '</div>';
            $out .= '</div>';
            
            // $out .= mif_mr_comp::get_show_all();
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            $out .= mif_mr_companion_core::get_show_all();
            $out .= '</div>';
            $out .= '</div>';

            $data = $tree['content']['competencies']['data'];
            $old = '';
            $n = 0;
            $index = array();
            // p($data);
            foreach ( $data as $key => $item ) {
                
                // p($item);

                if ( $old == $item['category'] ) $n--;
                $index[$n][] = $key;
                $old = $item['category'];
                $n++;
                
            }
            
            foreach ( $index as $item ) {
                
                if ( empty( $item ) ) continue;
                
                $out .= '<span>';
                $out .= mif_mr_comp::get_sub_head( array( 'name' => $data[$item[0]]['category'] ) );
                foreach ( $item as $item2 ) $out .= mif_mr_comp::get_item_body( $data[$item2] );
                $out .= '</span>';
                
            }
            
        }
        
        
        
        return apply_filters( 'mif_mr_show_set_comp', $out );
    }
    
    
    
    
    //
    //
    //
    
    public function compose_set_comp()
    {
        $out = '';        
        
        // p( $_REQUEST );
        
        if ( isset( $_REQUEST['select'] ) ) {

            foreach ( (array) $_REQUEST['select'] as $key => $item ) {

                $new_name = ( isset( $_REQUEST['new_name'][$key] ) ) ? sanitize_textarea_field( $_REQUEST['new_name'][$key] ) : '';
                $name = ( isset( $_REQUEST['name'][$key] ) ) ? sanitize_textarea_field( $_REQUEST['name'][$key] ) : '';
                $comp_id = ( isset( $_REQUEST['comp_id'][$key] ) ) ? sanitize_textarea_field( $_REQUEST['comp_id'][$key] ) : '';

                $out .= $new_name . ':' . $name . ':' . $comp_id . "\n";

            }

        }

        // p($out);

        return apply_filters( 'mif_mr_compose_set_comp', $out );
    }
    
    //
    //
    //

    public function edit_visual()
    {
        global $tree;
        $arr = $tree['content']['lib-competencies']['data'];
        $arr2 = array();
        $n = 0;
        // p($arr);
        
        foreach ( $arr as $item ) {
            
            // p($item['name']); // post 
            // p($item['comp_id']);  
            
            foreach ( $item['data'] as $item2 ) {
                
                foreach ( $item2['data'] as $item3 ) {
                    
                    // p($item3['name']);
                    // p($item3['descr']);
                    
                    $arr2[] = array(
                        'comp_id' => $item['comp_id'],
                        'lib_name' => $item['name'],
                        'name' => $item3['name'],
                        'descr' => $item3['descr'],
                        'new_name' => '',
                        // 'old_name' => '',
                        'sort' => 65535,
                        // 'sort' => -1,
                        'n' => $n++,
                    );
                    // p($item3);
                    
                }
            }
        }
        
        // p($item);
        // p($arr2);
        $arr = $tree['content']['competencies']['data'];
        // $index = array();
        // foreach ( $arr as $item ) $index[] = array( $item['old_name'], $item['comp_id'] );
        $sort = 0;
        // p($arr);

        foreach ( $arr as $key => $item ) {
            // p($item);
            foreach ( $arr2 as $key2 => $item2 ) {
                
                if ( $item['old_name'] == $item2['name'] && $item['comp_id'] == $item2['comp_id'] ) {
                    
                    $arr2[$key2]['new_name'] = $item['name'];
                    // $arr2[$key2]['old_name'] = $item['old_name'];
                    $arr2[$key2]['sort'] = $sort++;
                    
                } 
                
                // p('$index');
                
            }
            
        }
        // p($arr2);
        
        // uasort( $arr2, function ( $a, $b ) { return ( $a['sort'] > $b['sort'] ) ? 1 : 0; });

        $index = array();
        foreach ( $arr2 as $item2  ) $index[] = $item2['sort'];
        sort( $index );
        
        $arr3 = array();
        foreach ( $index as $i ) 
            foreach ( $arr2 as $key2 => $item2 ) 
                if ( $item2['sort'] == $i ) {

                    $arr3[] = $item2;
                    unset( $arr2[$key2] );
                    break;

                }

        // p($arr2);
        // p($arr3);
        
        $out = '';
        
        $out .= '<table>';
        
        $out .= '<thead><tr>';
        
        $out .= '<th>';
        $out .= '</th>';
        
        $out .= '<th>';
        $out .= 'Новое имя';
        $out .= '</th>';
        
        $out .= '<th colspan="2">';
        $out .= 'Компетенция';
        $out .= '</th>';
        
        $out .= '<th>';
        $out .= 'Библиотека';
        $out .= '</th>';
        
        $out .= '<th>';
        $out .= 'ID';
        $out .= '</th>';
        
        $out .= '<th>';
        $out .= '</th>';
        
        $out .= '</tr></thead>';
        
        $out .= '<tbody>';
        foreach ( $arr3 as $item ) $out .= $this->edit_visual_comp( $item );
        $out .= '</tbody>';
        
        $out .= '</table>';
        
        return apply_filters( 'mif_mr_edit_visual', $out );
    }
    
    
    
    
    //
    //
    //
    
    public function edit_visual_comp( $item )
    {
        $out = '';
        
        // p($item);
        $out .= '<tr>';
     
        $out .= '<td>';
        $checked = ( empty( $item['new_name'] ) ) ? '' : ' checked';
        $out .= '<input name="select[' . $item['n'] . ']" type="checkbox"' . $checked . ' class="sel form-check-input mt-1">';
        $out .= '</td>';
     
        $out .= '<td>';
        // $out .= '<input type="text" class="form-control" style="width: 5em;">';
        $out .= '<input name="new_name[' . $item['n'] . ']" type="text" value="' . $item['new_name'] . '" class="new_name form-control mt-1">';
        $out .= '</td>';
        
        $out .= '<td class="fw-bolder">';
        $out .= '<div class="pl-4 pr-2" style="min-height: 3.2em">' . $item['name'] . '</div>';
        $out .= '<input name="name[' . $item['n'] . ']" type="hidden" value="' . $item['name'] . '" class="name">';
        $out .= '</td>';
        
        $out .= '<td>';
        $out .= $this->mb_substr( $item['descr'], 100 );
        // $out .= '<input name="descr[]" type="hidden" value="' . $item['descr'] . '" class="descr">';
        $out .= '</td>';
        
        $out .= '<td>';
        $out .= $this->mb_substr( $item['lib_name'], 30 );
        // $out .= $item['lib_name'];
        $out .= '</td>';
        
        $out .= '<td>';
        $out .= '<div class="bg-secondary text-light rounded pl-2 pr-2 mt-1">' . $item['comp_id'] . '</div>';
        $out .= '<input name="comp_id[' . $item['n'] . ']" type="hidden" value="' . $item['comp_id'] . '" class="comp_id">';
        $out .= '</td>';
        
        $out .= '<td class="text-center" style="width: 4em">';
        // $out .= '<td>';
        $none = ( $item['sort'] == 65535 ) ? ' d-none' : '';
        $out .= '<i class="fa-solid fa-arrow-up up' . $none . '"></i>';
        $out .= '<i class="fa-solid fa-arrow-down down' . $none . '"></i>';
        // $out .= '<input name="sort[' . $item['n'] . ']" type="text" value="' . $item['sort'] . '" class="sort">';
        $out .= '<input name="sort[' . $item['n'] . ']" type="hidden" value="' . $item['sort'] . '" class="sort">';
        $out .= '</td>';

        $out .= '</tr>';

        return apply_filters( 'mif_mr_edit_visual_comp', $out, $item );
    }



    
    // //
    // // Возвращает массив из текста (post)
    // //
    // // новая:старая:id
    // //
    // // новая == старая          => УК-1             => УК-1:УК-1:NULL
    // // новая == старая, id      => УК-1:123         => УК-1:УК-1:123
    // // новая != старая          => УК-2:УК-1        => УК-2:УК-1:NULL
    // // новая != старая, id      => УК-2:УК-1:123    => УК-2:УК-1:123
    // // 

    // public function get_arr( $opop_id = NULL )
    // {
    //     if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
    //     // $this->get_index_comp( $opop_id );


    //     $arr = array();
        
    //     $text = $this->get_companion_content( 'set-competencies', $opop_id );
        
    //     // Разбить текст на массив строк
    //     $data = preg_split( '/\\r\\n?|\\n/', $text );
    //     $data = array_map( 'strim', $data );
        
    //     foreach ( $data as $item ) {
            
            
    //         $item .= '::';
    //         $arr2 = explode( ':', $item );
    //         // p($arr2);
            
    //         if ( empty( $arr2[0] ) ) continue;
            
    //         if ( is_string( $arr2[0] ) && empty( $arr2[1] ) && empty( $arr2[2] ) ) {
                
    //             // $id = '123';
    //             // $arr[] = array( $arr2[0], $arr2[0], $id );
    //             $arr[] = array( $arr2[0], $arr2[0], NULL );
    //             // p('1');
    //             continue;                
                
    //         }
            
    //         if ( is_string( $arr2[0] ) && is_numeric( $arr2[1] ) && empty( $arr2[2] ) ) {
                
    //             $arr[] = array( $arr2[0], $arr2[0], $arr2[1] );
    //             // p('2');
    //             continue;                
                
    //         }
            
    //         if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && empty( $arr2[2] ) ) {
                
    //             // $id = '123';
    //             // $arr[] = array( $arr2[0], $arr2[1], $id );
    //             $arr[] = array( $arr2[0], $arr2[1], NULL );
    //             // p('3');
    //             continue;                
                
    //         }
            
    //         if ( is_string( $arr2[0] ) && is_string( $arr2[1] ) && is_numeric( $arr2[2] ) ) {
               
    //             $arr[] = array( $arr2[0], $arr2[1], $arr2[2] );
    //             // p('4');
    //             continue;                
            
    //         }
            
    //         // p('err');

    //     }
        
    //     // p($arr);
        
    //     return apply_filters( 'mif_mr_comp_set_arr', $arr );
    // }
    
    
}

?>