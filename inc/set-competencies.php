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
        $this->save( 'set-competencies', $this->compose_set_comp() );

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
        global $mr;
        if ( ! $mr->user_can(2) ) return;

        global $tree;
        // p($tree['content']['competencies']['data']);
        
        // $this->save( 'set-competencies', $this->compose_set_comp() );
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
            
        //     // $out .= mif_mr_comp::get_show_all();
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            $out .= mif_mr_companion_core::get_show_all();
            $out .= '</div>';
            $out .= '</div>';
            
            $arr = $tree['content']['competencies']['data'];

            $old = '';
            $n = 0;
            $index = array();
            // p($arr);

            foreach ( $arr as $key => $item ) {
                
                if ( empty( $item['category'] ) ) {

                    $index[$n][] = $key;

                } else {

                    if ( $old == $item['category'] ) $n--;
                    $index[$n][] = $key;
                    $old = $item['category'];
                    $n++;

                }

            }
            
            foreach ( $index as $item ) {
                
                // p($item);
                if ( empty( $item ) ) continue;
                
                $out .= '<span class="content-ajax">';
                // $category = ( isset( $arr[$item[0]]['category'] ) ) ? $arr[$item[0]]['category'] : apply_filters( 'mif-mr-sub_default', 'default' );
                $out .= mif_mr_competencies_screen::get_sub_head( array( 'name' => $arr[$item[0]]['category'] ) );
                foreach ( $item as $item2 ) $out .= mif_mr_competencies_screen::get_item_body( $arr[$item2] );
                $out .= '</span>';
                
            }
            





            // $out .= '<div class="striped">';

            // foreach ( $arr as $k => $i ) {
                
            //     // p($i);

            //     // // $course_id = ( isset( $i['course_id'] ) ) ? $i['course_id'] : '';
            //     // $course_id_manual = ( isset( $i['method'] ) && $i['method'] == 'manual' ) ? $i['course_id'] : '';
            //     // // $span_id = ( isset( $i['course_id'] ) ) ? mif_mr_opop_core::get_span_id( $i['course_id'] ) : '';
            //     // // $span_id = ( isset( $i['course_id'] ) ) ? '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-courses/' . $i['course_id'] . '" class="rounded mr-gray p-1 pr-3 pl-3">' . $i['course_id'] . '</a>' : '';
            //     // $span_id = ( isset( $i['course_id'] ) ) ? mif_mr_opop_core::get_a_id( $i['course_id'], mif_mr_opop_core::get_opop_url() . 'lib-courses/' . $i['course_id'] ) : '';
            //     // $question = ( isset( $i['name_old'] ) ) ? '<span class="question rounded-circle mr-gray ml-2 p-1 pr-2 pl-2"><i class="fa-solid fa-question fa-xs"></i></span>' : '';
            //     // $name_old = ( isset( $i['name_old'] ) ) ? '<div class="answer" style="display: none;"><div class="p-2 mt-3 mb-3 mr-gray"><i>Старое название: ' . $i['name_old'] . '</i></div></div>' : '';

            //     // $out .= '<div class="row select-item select-yes ' . $a[$k]['item_1'] . ' ' . $a[$k]['item_2'] . ' ' . $a[$k]['item_3'] . '">';
            //     $out .= '<div class="row select-item select-yes">';
            //     $out .= '<div class="col col-1 p-2">' . $k . '</div>';
                
            //     // $out .= '<div class="col p-2">' . $k . $question . $name_old . '</div>';
            //     $out .= '<div class="col p-2">' . $i['descr'] . '</div>';

            //     $out .= '<div class="col col-2 p-2">';
            //     // $out .= mif_mr_opop_core::get_span_label( $a[$k]['label_3'], $a[$k]['color_3'], $a[$k]['desc_3'] ); 
            //     // $out .= mif_mr_opop_core::get_span_label( $a[$k]['label_2'], $a[$k]['color_2'], $a[$k]['desc_2'] ); 
            //     // $out .= mif_mr_opop_core::get_span_label( $a[$k]['label_1'], $a[$k]['color_1'], $a[$k]['desc_1'] ); 
            //     $out .= '</div>';
                
            //     // if ( isset( $_REQUEST['edit'] ) && $_REQUEST['edit'] == 'visual' ) {

            //     //     $out .= '<div class="col col-1 p-0">';
            //     //     $out .= '<input name="new_course_id[]" type="text" value="' . $course_id_manual . '" class="form-control p-1 pl-2 pr-0 mt-1">';
            //     //     $out .= '<input name="name[]" type="hidden" value="' . $k . '">';
            //     //     $out .= '</div>';

            //     // } else {

            //     //     $out .= '<div class="col col-1 p-2">' . $span_id . '</div>';

            //     // }
                


            //     $out .= '</div>';
                
            }
            
            // $out .= '</div>';















            //     $old = '';
            //     $n = 0;
            //     $index = array();
            //     // p($data);
            // foreach ( $data as $c => $cmp ) {

            
                // $out .= '<div class="row">';

                // $out .= '<div class="col p-0">';
                // $out .= $c;
                // $out .= '</div>';


                // $out .= '</div>';


        //         // p($item);

        //         if ( $old == $item['category'] ) $n--;
        //         $index[$n][] = $key;
        //         $old = $item['category'];
        //         $n++;
                
            // }
            
        //     foreach ( $index as $item ) {
                
        //         if ( empty( $item ) ) continue;
                
                // $out .= '<span class="content-ajax">';
        //         $out .= mif_mr_competencies_screen::get_sub_head( array( 'name' => $data[$item[0]]['category'] ) );
        //         foreach ( $item as $item2 ) $out .= mif_mr_competencies_screen::get_item_body( $data[$item2] );
                // $out .= '</span>';
                
            // }
            
        // }
        
        
        
        return apply_filters( 'mif_mr_show_set_comp', $out );
    }
    
    
    
    // //
    // // Показать cписок компетенций
    // //
    
    // public function show_set_comp()
    // {
    //     global $mr;
    //     if ( ! $mr->user_can(2) ) return;

    //     global $tree;
        
    //     $this->save( 'set-competencies', $this->compose_set_comp() );
    //     // p($_REQUEST);

    //     $out = '';
        
    //     if ( isset( $_REQUEST['edit'] ) ) {
            
    //         $out .= '<div class="row">';
    //         $out .= '<div class="col p-0">';
            
    //         if ( $_REQUEST['edit'] == 'visual' ) {
                
    //             $out .= $this->edit_visual();
                
    //         } else {
                
    //             $out .= $this->companion_edit( 'set-competencies' );
                
    //         }
            
    //         $out .= '</div>';
    //         $out .= '</div>';
            
    //     } else {
            
    //         $out .= '<div class="row fiksa">';
    //         $out .= '<div class="col p-0">';
    //         $out .= '<h4 class="mb-4 mt-0 pt-3 pb-5 bg-body">Компетенции в ОПОП:</h4>';
    //         $out .= '</div>';
    //         $out .= '</div>';
            
    //         // $out .= mif_mr_comp::get_show_all();
            
    //         $out .= '<div class="row">';
    //         $out .= '<div class="col p-0">';
    //         $out .= mif_mr_companion_core::get_show_all();
    //         $out .= '</div>';
    //         $out .= '</div>';

    //         $data = $tree['content']['competencies']['data'];
    //         $old = '';
    //         $n = 0;
    //         $index = array();
    //         // p($data);
    //         foreach ( $data as $key => $item ) {
                
    //             // p($item);

    //             if ( $old == $item['category'] ) $n--;
    //             $index[$n][] = $key;
    //             $old = $item['category'];
    //             $n++;
                
    //         }
            
    //         foreach ( $index as $item ) {
                
    //             if ( empty( $item ) ) continue;
                
    //             $out .= '<span class="content-ajax">';
    //             $out .= mif_mr_competencies_screen::get_sub_head( array( 'name' => $data[$item[0]]['category'] ) );
    //             foreach ( $item as $item2 ) $out .= mif_mr_competencies_screen::get_item_body( $data[$item2] );
    //             $out .= '</span>';
                
    //         }
            
    //     }
        
        
        
    //     return apply_filters( 'mif_mr_show_set_comp', $out );
    // }
    
    
    
    
    // //
    // //
    // //
    
    // public function compose_set_comp()
    // {
    //     $out = '';        
        
    //     // p( $_REQUEST );
        
    //     if ( isset( $_REQUEST['select'] ) ) {

    //         foreach ( (array) $_REQUEST['select'] as $key => $item ) {

    //             $new_name = ( isset( $_REQUEST['new_name'][$key] ) ) ? sanitize_textarea_field( $_REQUEST['new_name'][$key] ) : '';
    //             $name = ( isset( $_REQUEST['name'][$key] ) ) ? sanitize_textarea_field( $_REQUEST['name'][$key] ) : '';
    //             $comp_id = ( isset( $_REQUEST['comp_id'][$key] ) ) ? sanitize_textarea_field( $_REQUEST['comp_id'][$key] ) : '';

    //             $out .= $new_name . ':' . $name . ':' . $comp_id . "\n";

    //         }

    //     }

    //     // p($out);

    //     return apply_filters( 'mif_mr_compose_set_comp', $out );
    // }
    
    
    //
    //
    //
    
    public function compose_set_comp()
    {
        $out = '';        
        
        // p( $_REQUEST );
        
        if ( isset( $_REQUEST['visual'] ) && $_REQUEST['visual'] == 'yes' ) {

            foreach ( (array) $_REQUEST['new_name'] as $key => $new_name ) {

                $new_name = sanitize_text_field( $new_name );

                if ( empty( $new_name ) ) continue;
                if ( $new_name == $_REQUEST['bak_name'][$key] && ! $_REQUEST['method'][$key] == 'manual' ) continue;

                $name = sanitize_text_field( $_REQUEST['name'][$key] );
                $comp_id = sanitize_text_field( $_REQUEST['comp_id'][$key] );

                $out .= $new_name . ':' . $name . ':' . $comp_id . "\n";

            }


            // p('@');
            // foreach ( (array) $_REQUEST['select'] as $key => $item ) {

            //     $new_name = ( isset( $_REQUEST['new_name'][$key] ) ) ? sanitize_textarea_field( $_REQUEST['new_name'][$key] ) : '';
            //     $name = ( isset( $_REQUEST['name'][$key] ) ) ? sanitize_textarea_field( $_REQUEST['name'][$key] ) : '';
            //     $comp_id = ( isset( $_REQUEST['comp_id'][$key] ) ) ? sanitize_textarea_field( $_REQUEST['comp_id'][$key] ) : '';

            //     $out .= $new_name . ':' . $name . ':' . $comp_id . "\n";

            // }

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
        // $n = 0;
        // p($arr);
        
        foreach ( $arr as $item ) {
            
            // p($item['name']); // post 
            // p($item['comp_id']);  
            // p($item['comp_id']);  
            
            foreach ( $item['data'] as $item2 ) {
                
                foreach ( $item2['data'] as $item3 ) {
                    
                    // p($item3['name']);
                    // p($item3['descr']);
                    
                    $arr2[$item['comp_id']][$item3['name']] = array(
                        'comp_id' => $item['comp_id'],
                        'lib_name' => $item['name'],
                        'name' => $item3['name'],
                        'descr' => $item3['descr'],
                        // 'category' => $item3['category'],
                        'category' => $item2['name'],
                        // 'method' => $item3['method'],
                        // 'new_name' => '',
                        // 'old_name' => '',
                        // 'sort' => 65535,
                        // 'sort' => -1,
                        // 'n' => $n++,
                    );
                    // p($item3);
                    
                }
            }
        }
        
        // p($item);
        // p($arr2);

        $arr3 = $tree['content']['competencies']['data'];

        foreach ( $arr3 as $item ) {

            // p($i);

            if ( isset( $item['comp_id'] ) && isset( $arr2[$item['comp_id']][$item['old_name']] ) ) {

                $arr2[$item['comp_id']][$item['old_name']]['new_name'] = $item['name'];  
                $arr2[$item['comp_id']][$item['old_name']]['method'] = $item['method'];  

            }
                
        }


        
        // p($arr3);

        $arr4 = array();
        foreach ( $arr2 as $item )
            foreach ( $item as $item2 ) $arr4[] = $item2;


        $index = array();
        foreach ( $arr3 as $k => $i ) $index[] = $k;
        
        $arr5 = array();

        foreach ( $index as $k => $cmp )            
            foreach ( $arr4 as $k2 => $item )
                if ( isset( $item['new_name'] ) && $item['new_name'] == $cmp ) {

                    $arr5[] = $item;
                    unset( $index[$k] );
                    unset( $arr4[$k2] );
                    continue;

                } 

        foreach ( $arr4 as $item ) $arr5[] = $item;
            

        // p($index);
        // p($arr5);
        // p($arr4);


        $out = '';
        
        if ( ! empty( $index ) ) {
            

            $out .= '<div class="p-2 mb-6 bg-light border rounded fiksa">';
            
            $out .= '<div class="m-3">';
            $out .= '<h4>Компетенции не задействованы:</h4>';
            $out .= '</div>';


            $out .= '<div class="m-3 mb-3">';
            // $out .= '<div class="col">';

            foreach ( $index as $i ) {
                
                $out .= '<span class="mr-2 mb-3 copy-wrapper d-inline-block">';
                $out .= mif_mr_opop_core::get_span_id( $i );
                $out .= '</span> ';
                
            }
            
            $out .= '</div>';
            $out .= '</div>';
        
        }

        $out .= '<table class="vedit">';
        
        $out .= '<thead><tr>';
        
        // $out .= '<th>';
        // $out .= '</th>';
        
        $out .= '<th>';
        $out .= 'Новое имя';
        $out .= '</th>';
        
        $out .= '<th>';
        // $out .= '<th colspan="2">';
        $out .= 'Компетенция';
        $out .= '</th>';
        
        $out .= '<th>';
        $out .= 'Библиотека';
        $out .= '</th>';
        
        $out .= '<th>';
        $out .= 'ID';
        $out .= '</th>';
        
        // $out .= '<th>';
        // $out .= '</th>';
        
        $out .= '</tr></thead>';
        
        $out .= '<tbody>';
        foreach ( $arr5 as $item ) $out .= $this->edit_visual_comp( $item );
        $out .= '</tbody>';
        
        $out .= '</table>';
        
        $out .= '<input name="visual" type="hidden" value="yes">';

        return apply_filters( 'mif_mr_edit_visual', $out );
    }
    
    
    
    

    // //
    // //
    // //

    // public function edit_visual()
    // {
    //     global $tree;
    //     $arr = $tree['content']['lib-competencies']['data'];
    //     $arr2 = array();
    //     $n = 0;
    //     // p($arr);
        
    //     foreach ( $arr as $item ) {
            
    //         // p($item['name']); // post 
    //         // p($item['comp_id']);  
            
    //         foreach ( $item['data'] as $item2 ) {
                
    //             foreach ( $item2['data'] as $item3 ) {
                    
    //                 // p($item3['name']);
    //                 // p($item3['descr']);
                    
    //                 $arr2[] = array(
    //                     'comp_id' => $item['comp_id'],
    //                     'lib_name' => $item['name'],
    //                     'name' => $item3['name'],
    //                     'descr' => $item3['descr'],
    //                     'new_name' => '',
    //                     // 'old_name' => '',
    //                     'sort' => 65535,
    //                     // 'sort' => -1,
    //                     'n' => $n++,
    //                 );
    //                 // p($item3);
                    
    //             }
    //         }
    //     }
        
    //     // p($item);
    //     // p($arr2);
    //     $arr = $tree['content']['competencies']['data'];
    //     // $index = array();
    //     // foreach ( $arr as $item ) $index[] = array( $item['old_name'], $item['comp_id'] );
    //     $sort = 0;
    //     // p($arr);

    //     foreach ( $arr as $key => $item ) {
    //         // p($item);
    //         foreach ( $arr2 as $key2 => $item2 ) {
                
    //             if ( $item['old_name'] == $item2['name'] && $item['comp_id'] == $item2['comp_id'] ) {
                    
    //                 $arr2[$key2]['new_name'] = $item['name'];
    //                 // $arr2[$key2]['old_name'] = $item['old_name'];
    //                 $arr2[$key2]['sort'] = $sort++;
                    
    //             } 
                
    //             // p('$index');
                
    //         }
            
    //     }
    //     // p($arr2);
        
    //     // uasort( $arr2, function ( $a, $b ) { return ( $a['sort'] > $b['sort'] ) ? 1 : 0; });

    //     $index = array();
    //     foreach ( $arr2 as $item2  ) $index[] = $item2['sort'];
    //     sort( $index );
        
    //     $arr3 = array();
    //     foreach ( $index as $i ) 
    //         foreach ( $arr2 as $key2 => $item2 ) 
    //             if ( $item2['sort'] == $i ) {

    //                 $arr3[] = $item2;
    //                 unset( $arr2[$key2] );
    //                 break;

    //             }

    //     // p($arr2);
    //     // p($arr3);
        
    //     $out = '';
        
    //     $out .= '<table class="vedit">';
        
    //     $out .= '<thead><tr>';
        
    //     $out .= '<th>';
    //     $out .= '</th>';
        
    //     $out .= '<th>';
    //     $out .= 'Новое имя';
    //     $out .= '</th>';
        
    //     $out .= '<th colspan="2">';
    //     $out .= 'Компетенция';
    //     $out .= '</th>';
        
    //     $out .= '<th>';
    //     $out .= 'Библиотека';
    //     $out .= '</th>';
        
    //     $out .= '<th>';
    //     $out .= 'ID';
    //     $out .= '</th>';
        
    //     $out .= '<th>';
    //     $out .= '</th>';
        
    //     $out .= '</tr></thead>';
        
    //     $out .= '<tbody>';
    //     foreach ( $arr3 as $item ) $out .= $this->edit_visual_comp( $item );
    //     $out .= '</tbody>';
        
    //     $out .= '</table>';
        
    //     return apply_filters( 'mif_mr_edit_visual', $out );
    // }
    
    
    
    
    //
    //
    //
    
    public function edit_visual_comp( $item )
    {
        $out = '';
        
        $new_name = ( isset( $item['new_name'] ) ) ? $item['new_name'] : '';
        $method = ( isset( $item['method'] ) ) ? $item['method'] : '';

        // p($item);
        $out .= '<tr>';
     
        // $out .= '<td>';
        // $checked = ( empty( $item['new_name'] ) ) ? '' : ' checked';
        // $out .= '<input name="select[' . $item['n'] . ']" type="checkbox"' . $checked . ' class="sel form-check-input mt-1">';
        // $out .= '</td>';
     
        $out .= '<td>';
        // $out .= '<input type="text" class="form-control" style="width: 5em;">';
        // $out .= '<input name="new_name[' . $item['n'] . ']" type="text" value="' . $item['new_name'] . '" class="new_name form-control mt-1">';
        $out .= '<input name="new_name[]" type="text" value="' . $new_name . '" class="new_name form-control mt-1">';
        $out .= '<input name="bak_name[]" type="hidden" value="' . $new_name . '">';
        $out .= '<input name="name[]" type="hidden" value="' . $item['name'] . '">';
        $out .= '<input name="comp_id[]" type="hidden" value="' . $item['comp_id'] . '">';
        $out .= '<input name="method[]" type="hidden" value="' . $method . '">';
        $out .= '</td>';
        
        // $out .= '<td class="fw-bolder">';
        // $out .= '<div class="pl-4 pr-2" style="min-height: 3.2em">' . $item['name'] . '</div>';
        // // $out .= '<input name="name[' . $item['n'] . ']" type="hidden" value="' . $item['name'] . '" class="name">';
        // // $out .= '<input name="name[]" type="hidden" value="' . $item['name'] . '" class="name">';
        // $out .= '</td>';
        
        $out .= '<td>';
        $out .= '<span class="fw-bolder">' . $item['name'] . '.</span> ';
        $out .= $this->mb_substr( $item['descr'], 100 );
        // $out .= '<input name="descr[]" type="hidden" value="' . $item['descr'] . '" class="descr">';
        $out .= '</td>';
        
        $out .= '<td>';
        $out .= $this->mb_substr( $item['lib_name'], 30 );
        // $out .= $item['lib_name'];
        $out .= '</td>';
        
        $out .= '<td>';
        // $out .= '<div class="bg-secondary text-light rounded pl-2 pr-2 mt-1">' . $item['comp_id'] . '</div>';
        $out .= mif_mr_opop_core::get_a_id( $item['comp_id'], 
                                            mif_mr_opop_core::get_opop_url() . 'lib-competencies/' . $item['comp_id'],
                                            mif_mr_functions::mb_substr( get_the_title( $item['comp_id'] ), 30 ) );
        // $out .= '<input name="comp_id[' . $item['n'] . ']" type="hidden" value="' . $item['comp_id'] . '" class="comp_id">';
        $out .= '</td>';
        
        // $out .= '<td class="text-center" style="width: 4em">';
        // // $out .= '<td>';
        // $none = ( $item['sort'] == 65535 ) ? ' d-none' : '';
        // $out .= '<i class="fa-solid fa-arrow-up up' . $none . '"></i>';
        // $out .= '<i class="fa-solid fa-arrow-down down' . $none . '"></i>';
        // // $out .= '<input name="sort[' . $item['n'] . ']" type="text" value="' . $item['sort'] . '" class="sort">';
        // // $out .= '<input name="sort[' . $item['n'] . ']" type="hidden" value="' . $item['sort'] . '" class="sort">';
        // $out .= '<input name="sort[]" type="hidden" value="' . $item['sort'] . '" class="sort">';
        // $out .= '</td>';

        $out .= '</tr>';

        return apply_filters( 'mif_mr_edit_visual_comp', $out, $item );
    }

}

?>