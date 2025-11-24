<?php

//
// Настройки дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_set_courses extends mif_mr_set_core {
    
    function __construct()
    {

        parent::__construct();
        
        add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        add_filter( 'mif-mr-thead-col', array( $this, 'filter_thead_col'), 10 );
        add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );

        $this->save( 'set-courses' );

    }
    
    
    
    // 
    // Показывает  
    // 
    
    public function the_show()
    {
        if ( $template = locate_template( 'mr-set-courses.php' ) ) {
            
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-set-courses.php', false );
            
        }
    }
    
    


    //
    // Показать cписок дисциплин
    //
    
    public function show_set_courses()
    {
        global $tree;
        
        $arr = $tree['content']['courses']['data'];
        // p($arr);
        $m = new modules();
        $arr = $m->get_courses_tree( $arr );
        
        // p( $tree['content']['courses']['index'] );


        //     $this->save( 'set-competencies', $this->compose_set_comp() );
        //     // p($_REQUEST);
        // foreach ( $arr as $item ) {

        //     foreach ( $item['courses'] as $key2 => $item2 ) {

        //         $this->get_course_ifno($key2);
        //         // p($key2);

        //     };

        // }


        $out = '';
        
        if ( isset( $_REQUEST['edit'] ) ) {
            
    //         $out .= '<div class="row">';
    //         $out .= '<div class="col p-0">';
            
    //         if ( $_REQUEST['edit'] == 'visual' ) {
                
    //             $out .= $this->edit_visual();
                
    //         } else {
                
                $out .= $this->companion_edit( 'set-courses' );
                
    //         }
            
    //         $out .= '</div>';
    //         $out .= '</div>';
            
        } else {
            
            $out .= '<div class="row fiksa">';
            $out .= '<div class="col p-0">';
            $out .= '<h4 class="mb-4 mt-0 pt-3 pb-5 bg-body">Дисциплины в ОПОП:</h4>';
            $out .= '</div>';
            $out .= '</div>';
            
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0 mb-3">';
            $out .= mif_mr_companion_core::get_show_all();
            $out .= '</div>';
            $out .= '</div>';
            // $arr = $this->get_courses_arr(); 
            // return '@2';
            
            // $out .= '<div class="content-ajax col-12 p-0">';
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            $out .= $this->get_table_html( $arr );       
            $out .= '</div>';
            $out .= '</div>';
            // $out .= '</div>';
            



            // $out .= mif_mr_comp::get_show_all();
            
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
                
    //             $out .= '<span>';
    //             $out .= mif_mr_comp::get_sub_head( array( 'name' => $data[$item[0]]['category'] ) );
    //             foreach ( $item as $item2 ) $out .= mif_mr_comp::get_item_body( $data[$item2] );
    //             $out .= '</span>';
                
    //         }
            
        }
        
        
        
        return apply_filters( 'mif_mr_show_set_courses', $out );
    }
    
    
    
    
    // //
    // //
    // //
    
    // public function get_course_ifno( $course )
    // {
    //     global $tree;
        
    //     $arr = array();

    //     $arr_raw = $tree['content']['courses']['data'];
    //     $arr_set = $tree['content']['set-courses']['data'];

    //     // p($arr_raw);
    //     // p($arr_set);

    //     foreach ( $arr_raw as $item ) {

    //         foreach ( $item['courses'] as $key2 => $item2 ) {

    //             // p($key2);

    //         }

    //         // p($item);

    //     }


    //     return apply_filters( 'mif_mr_get_course_ifno', $arr );
    // }
        


    //
    //
    //

    public function filter_tbody_col( $arr, $key, $key2, $courses_arr )
    {
        global $tree;
        
        // $index = $tree['content']['courses']['index'];
        $i = $tree['content']['courses']['index'][$key2];
        
        
        // p($arr);
        // p($key2);
        // p($index[$key2]);
        // p($i);
        
        if ( empty( $i['course_id'] ) ) {
            
            $text = '';
            $text .= '<i class="mr-red text-danger fa-solid fa-xmark"></i> ';
            $text .= $arr[1]['text'];
            $arr[1]['text'] = $text;

            $arr[] = $this->add_to_col( '', array( 'elem' => 'td' ) );
            $arr[] = $this->add_to_col( '', array( 'elem' => 'td' ) );
            
        } else {
            
            
            // Col 2
            
            $selection_method = ( $i['auto'] ) ? 'автоматически' : 'ручной';
            $up = ' d-none';
            $down = '';

            $text = '';

            $text .= '<span>';
            $text .= '<div class="container">';
            $text .= '<div class="row">';
            $text .= '<div class="col-10 p-0">';
            $text .= '<i class="mr-green text-success fa-solid fa-check"></i> ';
            $text .= $arr[1]['text'];
            $text .= '</div>';
            $text .= '<div class="col-2 p-0 text-end">';
            $text .= '<a href="#" class="roll-up' . $up . '"><i class="fa-solid fa-angle-up"></i></a>';
            $text .= '<a href="#" class="roll-down' . $down . '"><i class="fa-solid fa-chevron-down"></i></a>';
            $text .= '</div>';
            $text .= '</div>';
            $text .= '</div>';

            $text .= '<div class="coll" style="display: none;">';
            $text .= '<div class="p-3 pt-5">';
            $text .= '<div class="mr-gray p-3 b order rounded">';
           
            $text .= '<p class="mb-2">Метод выбора: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2">' . $selection_method . '</span></p>';
            
            if ( ! empty( $i['name_old'] ) && $i['name_old'] != $i['name'] ) 
                $text .= '<p class="mb-2">Старое название: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2">' . $i['name_old'] . '</span></p>';
           
            if ( ! empty( $i['course_id'] ) ) 
                $text .= '<p class="mb-2">Идентификатор дисциплины: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2">' . $i['course_id'] . '</span></p>';
           
            if ( ! empty( $i['from_id'] ) ) {
                // $text .= '<p class="mb-2">Идентификатор ОПОП: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2"  title="' . 
                //         $this->mb_substr( get_the_title( $i['from_id'] ), 20 ) . '">' . $i['from_id'] . 
                //         '</span></p>';
                $text .= '<p class="mb-2">Идентификатор ОПОП: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2">' . $i['from_id'] . '</span></p>';
                $text .= '<p class="mb-2">Название ОПОП: <span class="bg-secondary text-light rounded p-1 pl-2 pr-2">' . 
                        $this->mb_substr( get_the_title( $i['from_id'] ), 50 ) . '</span></p>';
            }

            $text .= '</div>';
            $text .= '</div>';
            $text .= '</div>';
            $text .= '</span>';
            
            $arr[1]['text'] = $text;
            

            // Col 3
            
            $text = '';

            $text .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-courses/' . $i['course_id'] . '">';
            // $text .= ( $i['auto'] ) ? 'auto' : $i['course_id'];
            $text .= $i['course_id'];
            $text .= '</a>';
            
            $arr[] = $this->add_to_col( $text, array( 'elem' => 'td' ) );
            
            
            // Col 4
            
            $text = '';
            
            if ( $i['auto'] ) $text .= '<span class="hint" title="Автоматически">А</span> ';
            if ( count( $i['course_id_all'] ) > 1 ) $text .= '<span class="hint" title="Есть другие дисциплины">М</span> ';
            if ( ! empty( $i['name_old'] ) && $i['name_old'] != $i['name'] ) $text .= '<span class="hint" title="Поменяли название">П</span> ';
            if ( $i['from_id'] != mif_mr_opop_core::get_opop_id() ) $text .= '<span class="hint" title="По наследованию">Н</span> ';
            
            $arr[] = $this->add_to_col( $text, array( 'elem' => 'td' ) );


        }

        // p($arr);




        return $arr;
    }



    public function filter_thead_col( $arr )
    {
        $arr[] = $this->add_to_col( 'id', array( 'elem' => 'th' ) );
        $arr[] = $this->add_to_col( 'Прим.', array( 'elem' => 'th' ) );
        return $arr;
    }


    public function filter_tbody_colspan( $n )
    {
        return $n + 2;
    }


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
        
    //     $out .= '<table>';
        
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
    
    
    
    
    // //
    // //
    // //
    
    // public function edit_visual_comp( $item )
    // {
    //     $out = '';
        
    //     // p($item);
    //     $out .= '<tr>';
     
    //     $out .= '<td>';
    //     $checked = ( empty( $item['new_name'] ) ) ? '' : ' checked';
    //     $out .= '<input name="select[' . $item['n'] . ']" type="checkbox"' . $checked . ' class="sel form-check-input mt-1">';
    //     $out .= '</td>';
     
    //     $out .= '<td>';
    //     // $out .= '<input type="text" class="form-control" style="width: 5em;">';
    //     $out .= '<input name="new_name[' . $item['n'] . ']" type="text" value="' . $item['new_name'] . '" class="new_name form-control mt-1">';
    //     $out .= '</td>';
        
    //     $out .= '<td class="fw-bolder">';
    //     $out .= '<div class="pl-4 pr-2" style="min-height: 3.2em">' . $item['name'] . '</div>';
    //     $out .= '<input name="name[' . $item['n'] . ']" type="hidden" value="' . $item['name'] . '" class="name">';
    //     $out .= '</td>';
        
    //     $out .= '<td>';
    //     $out .= $this->mb_substr( $item['descr'], 100 );
    //     // $out .= '<input name="descr[]" type="hidden" value="' . $item['descr'] . '" class="descr">';
    //     $out .= '</td>';
        
    //     $out .= '<td>';
    //     $out .= $this->mb_substr( $item['lib_name'], 30 );
    //     // $out .= $item['lib_name'];
    //     $out .= '</td>';
        
    //     $out .= '<td>';
    //     $out .= '<div class="bg-secondary text-light rounded pl-2 pr-2 mt-1">' . $item['comp_id'] . '</div>';
    //     $out .= '<input name="comp_id[' . $item['n'] . ']" type="hidden" value="' . $item['comp_id'] . '" class="comp_id">';
    //     $out .= '</td>';
        
    //     $out .= '<td class="text-center" style="width: 4em">';
    //     // $out .= '<td>';
    //     $none = ( $item['sort'] == 65535 ) ? ' d-none' : '';
    //     $out .= '<i class="fa-solid fa-arrow-up up' . $none . '"></i>';
    //     $out .= '<i class="fa-solid fa-arrow-down down' . $none . '"></i>';
    //     // $out .= '<input name="sort[' . $item['n'] . ']" type="text" value="' . $item['sort'] . '" class="sort">';
    //     $out .= '<input name="sort[' . $item['n'] . ']" type="hidden" value="' . $item['sort'] . '" class="sort">';
    //     $out .= '</td>';

    //     $out .= '</tr>';

    //     return apply_filters( 'mif_mr_edit_visual_comp', $out, $item );
    // }

  
    
}

?>