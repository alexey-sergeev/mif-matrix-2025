<?php

//
// Учебный план
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_tools_courses extends mif_mr_tools_info {
    
    function __construct()
    {
        parent::__construct();

        // add_filter( 'lib-upload-save-title', array( $this, 'set_save_title'), 10, 2 );
        // add_filter( 'scheme-data-courses', array( $this, 'scheme_data_courses'), 10 );

        // $this->scheme = apply_filters( 'scheme-data-courses', array() );

        // $this->index = apply_filters( 'index-tools-courses', array(
        //         array( 'title' => 'Цели', 'key' => 'target', 'key2' => NULL, 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Разделы (содержание)', 'key' => 'parts', 'key2' => 'content', 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Разделы (компетенции)', 'key' => 'parts', 'key2' => 'cmp', 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Разделы (трудоемкость)', 'key' => 'parts', 'key2' => 'hours', 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Разделы (знать, уметь, владеть)', 'key' => 'parts', 'key2' => 'outcomes', 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Оценочные средства', 'key' => 'evaluations', 'key2' => NULL, 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Основная литература', 'key' => 'biblio', 'key2' => 'basic', 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Дополнительная литература', 'key' => 'biblio', 'key2' => 'additional', 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Ресурсы Интернета', 'key' => 'it', 'key2' => 'inet', 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Программное обеспечение', 'key' => 'it', 'key2' => 'app', 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Материально-техническое обеспечение', 'key' => 'mto', 'key2' => 'mto', 'return' => ' mr-yellow' ),
        //         array( 'title' => 'Разработчики', 'key' => 'authors', 'key2' => 'authors', 'return' => ' mr-yellow' ),
        //     ) );

        // $this->description = apply_filters( 'description-tools-courses', array(
        //         'curriculum-yes' => 'Входит в учебный план',
        //         'curriculum-no'=> 'Не включена в учебный план', 
        //         'local-no' => 'Локальный контента нет', 
        //         'local-yes' => 'Есть локальный контент',
        //         'local-maybe' => 'Есть локальный контент, но он отличается',
        //         'lib-no' => 'В библиотеке нет контента',
        //         'lib-yes' => 'Есть в библиотеке контент',
        //         'lib-maybe' => 'Есть в библиотеке контент, но он отличается', 
        //     ) );


        // // $this->index_part =  apply_filters( 'index-courses-part', array( 
        // //                                                     'content',
        // //                                                     'evaluations',
        // //                                                     'biblio',
        // //                                                     'it',
        // //                                                     'mto',
        // //                                                     'guidelines',
        // //                                                     'authors',
        // //                                                 ) );
    }
        

    

    // 
    // Показывает  
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-tools-courses.php' ) ) {
           
            load_template( $template, false );
            
        } else {
                
            load_template( dirname( __FILE__ ) . '/../templates/mr-tools-courses.php', false );

        }
    }
    




    // 
    //   
    // 

    public function get_tools_courses()
    {
        $att_id = mif_mr_functions::get_att_id();

        $out = '';

        // p($_FILES);
        // p($_REQUEST);
        
        // Разбор формы
        
        $m = new mif_mr_upload();
        // $res = $m->save( array( 'ext' => array( 'png' ) ) ); 
        $res = $m->save( array( 'ext' => array( 'xls', 'xlsx' ) ) ); 
            
        foreach ( (array) $res as $i ) {
            
            $out .= mif_mr_functions::get_callout( 
                $i['name'] . ' — <span class="fw-semibold">' . $i['messages'] . '</span>', 
                $i['status'] ); 

            if ( isset( $i['id'] ) ) $att_id = $i['id'];

        }        



        // Показать форму

        $out .= $m->form_upload( array( 
                            'text' => 'Загрузите файл шаблона учебной дисциплины в формате Excel', 
                            // 'title_placeholder' => 'Название плана', 
                            'url' => 'tools-courses',
                            'multiple' => true 
                        ) );
        

        // Показать список файлов courses
        
        $out .= $this->show_list_file_courses();
        
        $out .= $this->get_meta( 'courses' );


        // // Показать courses

        // if ( ! empty( $att_id ) ) $out .= $this->show_file_courses( $att_id );
        
        return apply_filters( 'mif_mr_get_tools_courses', $out );

    }
    

    
    
    // 
    // Вывести  
    // 

    public function show_list_file_courses()
    {
        $out = '';

        $arr = $this->get_file( array( 'ext' => array( 'xls', 'xlsx' ), 'orderby' => 'title', 'order' => 'ASC' ) );
        // $arr = $this->get_file();

        // p($arr);

        $arr_info = array();

        foreach ( $arr as $key => $item ) {
            
            $a = $this->get_info_courses( $item->ID );

            // p($a);

            // $arr_info[$key]['title'] = ( $a['is_course'] ) ? $a['title'] : 'Дисциплина не обнаружена';
            // $arr_info[$key]['is_curriculum'] = ( $a['is_curriculum'] ) ? $this->description['curriculum-yes'] : $this->description['curriculum-no'];
            // $arr_info[$key]['is_content_local'] = ( $a['is_content_local'] ) ? $this->description['local-yes'] : $this->description['local-no'];    
            // $arr_info[$key]['id_local'] = ( $a['is_content_local'] ) ? ': ' . $this->get_link_local( $a ) . '': '';    
            // $arr_info[$key]['is_content_lib'] = ( $a['is_content_lib'] ) ? $this->description['lib-yes'] : $this->description['lib-no'];    
            // $arr_info[$key]['id_libs'] = ( $a['is_content_lib'] ) ? ': ' . $this->get_link_lib( $a ) . '' : '';    

            $arr_info[$key]['item_1'] = ( $a['is_curriculum'] ) ? 'curriculum-yes' : 'curriculum-no';
            $arr_info[$key]['item_2'] = ( ! $a['is_content_local'] ) ? 'local-no' : ( ( $a['percent_local'] == 100 ) ? 'local-yes' : 'local-maybe' );
            $arr_info[$key]['item_3'] = ( ! $a['is_content_lib'] ) ? 'lib-no' : ( ( $a['percent_lib_max'] == 100 ) ? 'lib-yes' : 'lib-maybe' );
            
            $arr_info[$key]['class_1'] = ( $arr_info[$key]['item_1'] === 'curriculum-yes' ) ? 'mr-blue-2' : 'mr-red-2';
            $arr_info[$key]['class_2'] = ( $arr_info[$key]['item_2'] === 'local-no' ) ? 'mr-gray-2'  : ( ( $arr_info[$key]['item_2'] === 'local-yes' ) ? 'mr-green-2' : 'mr-orange-2' );
            $arr_info[$key]['class_3'] = ( $arr_info[$key]['item_3'] === 'lib-no' ) ? 'mr-gray-2'  : ( ( $arr_info[$key]['item_3'] === 'lib-yes') ? 'mr-magenta-2' : 'mr-orange-2' );

        }

        $out .= '<div class="container mt-5">';
        
        $out .= '<div class="row">';
        $out .= '<div class="col p-2 pt-4 pb-4 select-menu">';
        $out .= $this->get_select_menu( $arr_info );
        $out .= '</div>';
        $out .= '</div>';
        

        $out .= '<div class="row">';
        $out .= '<div class="col p-0">';
        $out .= '<div class="messages-box"></div>';
        $out .= '</div>';
        $out .= '</div>';
        



        $out .= '<div class="row">';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold">№</div>';
        $out .= '<div class="col p-2 pt-4 pb-4 fw-semibold">Название дисциплины</div>';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold"></div>';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold"></div>';
        $out .= '</div>';
        
        $n = 0;
        $out .= '<div class="striped">';
        
        foreach ( $arr as $key => $item ) {
            
            
            $out .= '<div class="row select-item select-yes ' . $arr_info[$key]['item_1'] . ' ' . $arr_info[$key]['item_2'] . ' ' . $arr_info[$key]['item_3'] . '">';
            $out .= '<div class="col col-1 p-2">' . ++$n . '</div>';
            // $out .= '<div class="col col-1 p-2"><input class="form-check-input ml-2 mr-1" type="checkbox" value=""></div>';
            $out .= '<div class="col p-2"><a href="' .  mif_mr_opop_core::get_opop_url() . 'tools-courses/' . $item->ID . '" class="info">' . $item->post_title . '</a></div>';
            $out .= '<div class="col col-2 p-2 text-center">';
            
            $out .= '<span class="p-1 mr-1 text-light rounded ' . $arr_info[$key]['class_1'] . ' item-1"><i class="fa-solid fa-1 fa-xs"></i></span>';
            $out .= '<span class="p-1 mr-1 text-light rounded ' . $arr_info[$key]['class_2'] . ' item-2"><i class="fa-solid fa-2 fa-xs"></i></span>';
            $out .= '<span class="p-1 mr-1 text-light rounded ' . $arr_info[$key]['class_3'] . ' item-3"><i class="fa-solid fa-3 fa-xs"></i></span>';
            
            $out .= '</div>';
            $out .= '<div class="col col-2 p-2 text-end">';
            $out .= '<a href="#" class="mr-3 info"><i class="fa-solid fa-chart-simple fa-lg"></i></a>';
            // $out .= '<a href="#" class="mr-3 export"><i class="fa-solid fa-file-export fa-lg"></i></a>';
            $out .= '<a href="#" class="mr-3 export"><i class="fa-regular fa-floppy-disk fa-lg"></i></a>';
            $out .= '<a href="#" class="remove" data-attid="' . $item->ID . '"><i class="fa-regular fa-trash-can fa-lg"></i></a>';
            $out .= '<input class="form-check-input ml-2 mr-1" type="checkbox" value="' . $item->ID . '" name="ids[]">';
            // $out .= '<input type="hidden" value="' . $item->ID . '" name="attid">';
            $out .= '</div>';
            $out .= '</div>';
            
            // p($arr_info[$key])
            

            // $out .= $this->analysis( array( 'att_id' => $item->ID ) );

            // $out .= '<div class="row"><div class="col">';
            // $out .= '<div>' . $arr_info[$key]['is_curriculum'] . '</div>';
            // $out .= '<div>' . $arr_info[$key]['is_content_local'] . $arr_info[$key]['id_local'] . '</div>';
            // $out .= '<div>' . $arr_info[$key]['is_content_lib'] . $arr_info[$key]['id_libs'] . '</div>';
            // $out .= '</div></div>';
            
            }
            
            $out .= '</div>';

        $style = ( $n === 0 ) ? '' : ' style="display: none;"'; 
        $out .= '<div class="row no-plans"' . $style . '>';
        $out .= '<div class="col p-4 text-center bg-light border rounded fw-semibold">Нету дисциплин</div>';
        $out .= '</div>';
        
        if ( $n !== 0 ) {

            $out .= '<div class="row bg-light pt-5 pb-5 bottom-panel">';
            $out .= '<div class="col">';
            // $out .= '<button type="button" class="btn btn-primary">Экспорт</button>';
            // $out .= '<button type="button" class="btn btn-primary">Сохранить и удалить</button>';
            // $out .= '<button type="button" class="btn btn-secondary">Удалить</button>';
            $out .= '<a href="#" class="mr-3 export-all"><i class="fa-regular fa-floppy-disk fa-lg"></i>Сохранить</a>';
            $out .= '<a href="#" class="remove-all"><i class="fa-regular fa-trash-can fa-lg"></i>Удалить</a>';
            $out .= '</div>';
            $out .= '<div class="col col-2 text-end pr-2"><input class="form-check-input ml-2 mr-1" type="checkbox" value="" name="all"></div>';
            $out .= '</div>';

        }

        $out .= '</div>';

        return $out;
    }
    
    

    

    private function get_select_menu( $arr_info )
    {
        $out = '';

        // p($arr_info);

        $arr = array(
                'curriculum-yes' => false,
                'curriculum-no' => false,
                'local-no' => false,
                'local-yes' => false,
                'local-maybe' => false,
                'lib-no' => false,
                'lib-yes' => false,
                'lib-maybe' => false,
            );

        foreach ( $arr_info as $item ) {

            $arr[$item['item_1']] = true;
            $arr[$item['item_2']] = true;
            $arr[$item['item_3']] = true;

        }

        // p($arr);

        $arr2 = array( '1' => 0, '2' => 0, '3' => 0 );

        foreach ( $arr as $k => $i ) {

            switch ( $k ) {
                        
                case 'curriculum-yes':
                case 'curriculum-no':

                    // $arr2['1'][$k] = true;
                    if ( $i ) $arr2['1']++;

                break;
                
                case 'local-no':
                case 'local-yes':
                case 'local-maybe':

                    // $arr2['2'][$k] = true;
                    if ( $i ) $arr2['2']++;
                
                break;
                
                case 'lib-no':
                case 'lib-yes':
                case 'lib-maybe':

                    // $arr2['3'][$k] = true;
                    if ( $i ) $arr2['3']++;
                
                break;
                        
                // default:
                // break;
            
            }

        }

        // p($arr2);
        
        foreach ( $arr as $k => $i ) {

            switch ( $k ) {
                        
                case 'curriculum-yes':
                case 'curriculum-no':

                    if ( $arr2['1'] <= 1 ) $arr[$k] = false;

                break;
                
                case 'local-no':
                case 'local-yes':
                case 'local-maybe':

                    if ( $arr2['2'] <= 1 ) $arr[$k] = false;
                
                break;
                
                case 'lib-no':
                case 'lib-yes':
                case 'lib-maybe':

                    if ( $arr2['3'] <= 1 ) $arr[$k] = false;
                
                break;
                        
                // default:
                // break;
            
            }

        }

        // p($arr);

        $arr3 = array(
                array( 'curriculum-yes', 'mr-blue-2', '1', $this->description['curriculum-yes'], '2', $arr['curriculum-yes'] ),
                array( 'curriculum-no', 'mr-red-2', '1', $this->description['curriculum-no'], '5', $arr['curriculum-no'] ),
                array( 'local-no', 'mr-gray-2', '2', $this->description['local-no'], '2', $arr['local-no'] ),
                array( 'local-yes', 'mr-green-2', '2', $this->description['local-yes'], '2', $arr['local-yes'] ),
                array( 'local-maybe', 'mr-orange-2', '2', $this->description['local-maybe'], '5', $arr['local-maybe'] ),
                array( 'lib-no', 'mr-gray-2', '3', $this->description['lib-no'], '2', $arr['lib-no'] ),
                array( 'lib-yes', 'mr-magenta-2', '3', $this->description['lib-yes'], '2', $arr['lib-yes'] ),
                array( 'lib-maybe', 'mr-orange-2', '3', $this->description['lib-maybe'], '2', $arr['lib-maybe'] ),
                );
                
        $g = '1';
                
        foreach ( $arr3 as $i ) {

            if ( $g != $i[2] )  $out .= '<div class="pb-4"></div>';
            $g = $i[2];

            // if ( ! $i[5] ) continue;
            $style = ( ! $i[5] ) ? ' style="display: none;"' : '';

            $out .= '<div class="mb-2"' . $style . '>';
            $out .= '<input class="form-check-input mr-3" type="checkbox" value="' . $i[0] . '" id="' . $i[0] . '" checked>';
            $out .= '<label class="form-check-label" for="' . $i[0] . '">';
            $out .= '<span class="p-1 pl-2 pr-2 mr-1 text-light rounded ' . $i[1] . '"><i class="fa-solid fa-' . $i[2] . ' fa-xs"></i></span> ' . $i[3];
            $out .= '</label>';
            $out .= '</div>';

        }

        return $out;
    }
    
    



    
//     private function get_link_local( $a )
//     {
//         // return  mif_mr_opop_core::get_span_id( $a['id_local'] ) .  ' (' . '<a href="' . $a['id_local'] . '">' . $a['percent_local'] . '%</a>)';
//         // return  '<a href="' . $a['id_local'] . '">' . $a['percent_local'] . '%</a>';
//         return  '<a href="#" class="info-clarifications" data-id="' . $a['id_local'] . '">' . $a['percent_local'] . '%</a> (' . $a['id_local'] . ')';
//     }




//     private function get_link_lib( $a )
//     {


//     // p($a);
//         // if ( is_array( $ids ) ) {

//         $b = array();
//         // foreach ( $a['id_libs'] as $k => $i ) $b[] = mif_mr_opop_core::get_span_id( $i ) . ' (' . '<a href="' . $i . '">' . $a['percent_libs'][$k] . '%</a>)';
//         foreach ( $a['id_libs'] as $k => $i ) $b[] = '<a href="#" class="info-clarifications" data-id="' . $i . '">' . $a['percent_libs'][$k] . '%</a> (' . $i . ')';
        
//         return implode( ', ', $b );

//         // } else {

//         //     return '<a href="' . $ids . '">' . $ids . '</a>';

//         // }

//     }




//     // 
//     // Вывести статистику  
//     // 
    
//     private function get_info_courses( $att_id )
//     {
//         global $tree;
    
//         $arr = array();

//         $m = new mif_mr_xlsx( get_attached_file( $att_id ) );
//         $name = $m->get( $this->scheme['name'][0] );

//         // p($c);

//         $arr['title'] = $name;    
//         $arr['is_course'] = ( ! empty( $name ) ) ? true : false; // !!!!
//         $arr['is_curriculum'] = ( ! empty( $tree['content']['courses']['index'][$name]) ) ? true : false;
     
//         // p(mif_mr_opop_core::get_opop_id());
//         // p($tree['content']['lib-courses']['data']);
        
//         // if ( isset( $tree['content']['lib-courses']['data'] ) ) {
            
//         $f_local = false;
//         $f_lib = false;

//         foreach ( $tree['content']['lib-courses']['data'] as $i ) {
//             if ( $i['name'] != $name ) continue;
//             if ( $i['from_id'] == mif_mr_opop_core::get_opop_id() ) $f_local = true; else $f_lib = true;
//             // if ( $i['from_id'] == mif_mr_opop_core::get_opop_id() ) $arr['local_course_id'] = $i['comp_id'];
//         } 
        
//         $arr['is_content_local'] = $f_local;    
//         $arr['is_content_lib'] = $f_lib;    
        

//         $arr['id_local'] = NULL;    
//         $arr['id_libs'] = array();  
        
//         $arr['percent_local'] = 0;
//         $arr['percent_libs'] = array(); 
//         $arr['percent_lib_max'] = 0;    
        
//         foreach ( $tree['content']['lib-courses']['data'] as $i ) {
            
//             if ( $i['name'] == $name ) {
            
//                 // $a = array( 'id' => $i['comp_id'], 'percent' => $this->get_stats_courses( 
//                 //         $this->set_courses_form_xls( $att_id ), 
//                 //         $tree['content']['lib-courses']['data'][$i['comp_id']]['data'] ) );
//                 // $a = array( 'id' => $i['comp_id'], 'percent' => '' ); 
                
                
//                 $p = $this->get_stats_courses( 
//                         $this->get_courses_form_xls( $att_id ), 
//                         $tree['content']['lib-courses']['data'][$i['comp_id']]['data'] );

//                 if ( $i['from_id'] == mif_mr_opop_core::get_opop_id() ) {

//                     $arr['id_local'] = $i['comp_id']; 
//                     $arr['percent_local'] = $p; 
                    
//                 } else {
                    
//                     $arr['id_libs'][] = $i['comp_id'];
//                     $arr['percent_libs'][] = $p; 

//                     if ( $p > $arr['percent_lib_max'] ) $arr['percent_lib_max'] = $p;
            
//                 }



//             }

//         } 
        

//         // [mif_mr_opop_core::get_opop_id()] as $i ) p($i);



//         // } else {

//         //     $f = false;

//         // }


//         return $arr;
//     }
    




//     // 
//     // Вывести  
//     // 

//     public function show_file_courses( $att_id, $courses_id = NULL )
//     {
//         // Course 1
        
//         $att = get_post( $att_id );
        
//         if ( empty( $att ) ) return;
//         if ( $att->post_type != 'attachment' ) return;
//         if ( ! in_array( mif_mr_functions::get_ext( $att->guid ), array( 'xls', 'xlsx' ) ) ) return;
        
//         $arr = $this->get_courses_form_xls( $att_id );
        
//         // Course 2

        
//         // $courses_id = 1162;
        
//         global $tree;
//         $arr2 = array();
//         if ( isset( $tree['content']['lib-courses']['data'][$courses_id]['data'] ) ) $arr2 = $tree['content']['lib-courses']['data'][$courses_id]['data'];

//         $from_id = ( $tree['content']['lib-courses']['data'][$courses_id]['from_id'] ) ? $tree['content']['lib-courses']['data'][$courses_id]['from_id'] : NULL;

//         // p($tree['content']['lib-courses']['data'][$courses_id]);


//         // p($arr);

//         // p($this->scheme);

//         $m = new mif_mr_xlsx( get_attached_file( $att_id ) );
//         $c = $m->get( $this->scheme['name'][0] );

        
        
//         //  ## @@@ ### !!!!!!!!!!
        
//         // $arr = $m->get_arr();
        
//         // foreach ( $arr as $k => $i )
//         // foreach ( $i as $k2 => $i2 ) {

//         //     if ( empty( $i2 ) ) continue;
//         //     if ( preg_match( '/^\[/', $i2 ) ) {
                
//         //         $i2 = trim( $i2, '[]' );
//         //         p(  '$arr["' . $i2 . '"][] = "' . $k2 . $k . '";' );

//         //     }
//         //     // p($k);
//         //     // p($k2);
    
    
//         // }





//         // p($c);

//         $out = '';
        
//         // $out .= '<p>.';
//         // $out .= '<p>.';
//         // $out .= '<p>.';


//         // p( $this->get_stats_courses( $arr, $arr2 ) );


//         $out .= '<div class="container show-file" data-attid="' . $att_id . '">';
        
//         $out .= '<div class="row">';
//         $out .= '<div class="col d-none d-sm-block mt-3 mb-3 pr-0 text-end">';
//         $out .= '<a href="#" class="text-secondary" id="fullsize">';
//         $out .= '<i class="fa-solid fa-expand fa-2x"></i><i class="d-none fa-solid fa-compress fa-2x"></i>';
//         $out .= '</a>';
//         $out .= '</div>';
//         $out .= '</div>';
        
//         if ( ! empty( $from_id ) ) {

//             $out .= '<div class="row">';
//             $out .= '<div class="col fw-semibold p-3">';
//             $out .= 'Дисциплина ' . mif_mr_opop_core::get_span_id( $courses_id );
//             $out .= ( $from_id == mif_mr_opop_core::get_opop_id() ) ? ' из текущей ОПОП' : 
//                 ' из ОПОП: <a href="' .  get_permalink( $from_id ) . 'lib-courses/' . $courses_id . '">'
//                  . mif_mr_functions::mb_substr( get_the_title( $from_id ), 40 ) . '</a>';
    
//             $out .= '</div>';
//             $out .= '</div>';

//         }    

// // '<a href="' .  get_permalink( $att['from_id'] ) . '' . $att['type']. '/' . $att['comp_id'] . '" title="' . 
// //                 mif_mr_functions::mb_substr( get_the_title( $att['from_id'] ), 20 ) . '">' . $att['from_id'] . '</a>'


//         $out .= '<div class="row">';
//         $out .= '<div class="col fw-semibold bg-light p-3 text-center">' . $arr['name'] . '</div>';
//         $out .= '</div>';

//         $out .= '<div class="row">';
//         $out .= '<div class="col col-6 text-center fw-semibold mt-2 mb-2">из файла</div>';
//         $out .= '<div class="col col-6 text-center fw-semibold mt-2 mb-2">из Matrix</div>';
//         $out .= '</div>';
        

//         foreach ( $this->index as $i ) {

//             // p( $i );
            
//             $out .= '<div class="row">';
//             $out .= '<div class="col fw-semibold bg-light p-2 text-center">' . $i['title'] . '</div>';
//             $out .= '</div>';
            
//             $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, $i['key'], $i['key2'], ' mr-yellow' ) . '">';
//             $out .= $this->get_course_part( $arr, $i['key'], $i['key2'] );
//             $out .= $this->get_course_part( $arr2, $i['key'], $i['key2'] );
//             $out .= '</div>';


//         }


//         // $out .= '<div class="row mb-5">';
//         // $out .= '<div class="col bg-light p-4">';
        
        

//         // $out .= '<a href="#" class="mr-3 export-panel"><i class="fa-regular fa-floppy-disk fa-lg"></i></a>';
//         // $out .= '<a href="#" class="remove-panel"><i class="fa-regular fa-trash-can fa-lg"></i></a>';
//         // $out .= '<p>Комментарий</p>';



        
//         // $out .= '</div>';
//         // $out .= '</div>';

//         $out .= '</div>';



//         // // Цели

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Цели</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'target', NULL, ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'target' );
//         // $out .= $this->get_course_part( $arr2, 'target' );
//         // $out .= '</div>';

//         // // Разделы (содержание)

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Разделы (содержание)</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'parts', 'content', ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'parts', 'content' );
//         // $out .= $this->get_course_part( $arr2, 'parts', 'content' );
//         // $out .= '</div>';

//         // // Разделы (компетенции)

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Разделы (компетенции)</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'parts', 'cmp', ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'parts', 'cmp' );
//         // $out .= $this->get_course_part( $arr2, 'parts', 'cmp' );
//         // $out .= '</div>';

//         // // Разделы (трудоемкость)

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Разделы (трудоемкость)</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'parts', 'hours', ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'parts', 'hours' );
//         // $out .= $this->get_course_part( $arr2, 'parts', 'hours' );
//         // $out .= '</div>';

//         // // Разделы (знать, уметь, владеть)

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Разделы (знать, уметь, владеть)</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'parts', 'outcomes', ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'parts', 'outcomes' );
//         // $out .= $this->get_course_part( $arr2, 'parts', 'outcomes' );
//         // $out .= '</div>';
  
//         // // Оценочные средства

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Оценочные средства</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'evaluations', NULL, ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'evaluations' );
//         // $out .= $this->get_course_part( $arr2, 'evaluations' );
//         // $out .= '</div>';
  
//         // // Основная литература

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Основная литература</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'biblio', 'basic', ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'biblio', 'basic' );
//         // $out .= $this->get_course_part( $arr2, 'biblio', 'basic' );
//         // $out .= '</div>';
  
//         // // Дополнительная литература

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Дополнительная литература</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'biblio', 'additional', ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'biblio', 'additional' );
//         // $out .= $this->get_course_part( $arr2, 'biblio', 'additional' );
//         // $out .= '</div>';
  
//         // // Ресурсы Интернета

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Ресурсы Интернета</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'it', 'inet', ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'it', 'inet' );
//         // $out .= $this->get_course_part( $arr2, 'it', 'inet' );
//         // $out .= '</div>';
  
//         // // Программное обеспечение

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Программное обеспечение</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'it', 'app', ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'it', 'app' );
//         // $out .= $this->get_course_part( $arr2, 'it', 'app' );
//         // $out .= '</div>';
  
//         // // Материально-техническое обеспечение

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Материально-техническое обеспечение</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'mto', 'mto', ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'mto', 'mto' );
//         // $out .= $this->get_course_part( $arr2, 'mto', 'mto' );
//         // $out .= '</div>';
  
//         // // Разработчики

//         // $out .= '<div class="row">';
//         // $out .= '<div class="col fw-semibold bg-light p-2 text-center">Разработчики</div>';
//         // $out .= '</div>';
        
//         // $out .= '<div class="row' . $this->get_diff_courses( $arr, $arr2, 'authors', 'authors', ' mr-yellow' ) . '">';
//         // $out .= $this->get_course_part( $arr, 'authors', 'authors' );
//         // $out .= $this->get_course_part( $arr2, 'authors', 'authors' );
//         // $out .= '</div>';

//         // $out .= '</div>';
        
//         // $out .= '<input type="hidden" name="attid" value="' . $att_id . '" />'; 
    
//     return $out;
//     }
    




//     //
//     //
//     //
    
//     private function get_diff_courses( $arr, $arr2, $key, $key2, $return = NULL )
//     {
//         $yes = true;  
//         $no = false;  
        
//         if ( $return !== NULL ) {
            
//             $yes = $return;  
//             $no = '';  

//         }
    
//         // if ( $key='parts' && $key2='content') {

//         //     p($arr['content']['parts'][0]);
//         //     p($arr2['content']['parts'][0]);

//         // }

//         $s = $this->get_course_part( $arr, $key, $key2 );
//         $s2 = $this->get_course_part( $arr2, $key, $key2 );
//         // $s = strim( $this->get_course_part( $arr, $key, $key2 ) );
//         // $s2 = strim( $this->get_course_part( $arr2, $key, $key2 ) );

//         if ( $s == $s2 ) $res = $no; else $res = $yes;

//         // p($s);
//         // p($s2);


//         return $res;
//     }




//     //
//     //
//     //
    
//     private function get_stats_courses( $arr, $arr2 )
//     {
//         $p = 0;
//         $n = 0;

//         foreach ( $this->index as $i ) {

//             if ( ! $this->get_diff_courses( $arr, $arr2, $i['key'], $i['key2'] ) ) $p++; 
//             $n++;            
        
//         }

//         return round( $p / $n * 100, 0 );
//     }






//     //
//     //
//     //      
    
//     private function get_course_part( $arr, $key, $key2 = NULL )
//     {
        
//         // Пропускаем
    
//         switch ( $key ) {
                    
//             case 'target':
//             case 'parts':
    
//                 if ( empty( $arr['content'][$key] ) ) return;
            
//             break;
                
//             case 'evaluations':
    
//                 if ( empty( $arr[$key] ) ) return;

//             break;
            
//             case 'biblio':
//             case 'it':
//             case 'mto':
//             case 'authors':

//                 if ( empty( $arr[$key][$key2]) ) return;
            
//             break;
                    
//             // default:
//             // break;
        
//         }


//         // Собираем

//         $out = '';    
//         $out .= '<div class="col col-6 mt-3 mb-3">';

//         switch ( $key ) {
                    
//             case 'target':
//     // p($arr['content']['target']);
//                 $out .= '<div class="mb-3">' . $arr['content']['target'] . '</div>';

//             break;
            
//             case 'parts':

//                 foreach ( $arr['content']['parts'] as $i ) {
                    
//                     if ( preg_match( '/.*\)$/', $i['name'] ) ) $i['name'] .= '.'; // !!!!
                
//                     $out .= '<div class="mb-3 fw-bold">= ' . $i['name'] . '</div>';
//                     // $out .= '<div class="mb-3">= ' . $i['name'] . '</div>';
//                     if ( $key2 == 'content' ) $out .= '<div class="mb-3">' . $i['content'] . '</div>';
//                     if ( $key2 == 'cmp' ) $out .= '<div class="mb-3">' . $i['cmp'] . '</div>';
                    
//                     if ( $key2 == 'hours' ) {

//                         $out .= '<div class="mb-3">' . $i['hours_raw'] . '</div>';

//                     }
                    
//                     if ( $key2 == 'outcomes' ) {
                        
//                         $ii = array_diff( (array) $i['outcomes']['z'], array( '' ) );
//                         $out .= '<div class="mb-3"><i>знать:</i><br />— ' . implode( '<br />— ', $ii ) . '</div>';
//                         $ii = array_diff( (array) $i['outcomes']['u'], array( '' ) );
//                         $out .= '<div class="mb-3"><i>уметь:</i><br />— ' . implode( '<br />— ', $ii ) . '</div>';
//                         $ii = array_diff( (array) $i['outcomes']['v'], array( '' ) );
//                         $out .= '<div class="mb-3"><i>владеть:</i><br />— ' . implode( '<br />— ', $ii ) . '</div>';
                    
//                     }

//                 }
            
//             break;
            
//             case 'evaluations':
                
//                 foreach ( $arr['evaluations'] as $k => $d ) {
                
//                     if ( $k != 0 ) $out .= '<br />';

//                     foreach ( $d['data'] as $i ) {

//                         $out .= '<div>' . $i['name'];
//                         if ( preg_match( '/.*\)$/', $i['name'] ) ) $out .= '.'; // !!!!
//                         $out .= ' (' . $i['att']['rating'] . ') (' . $i['att']['cmp'] . ')' . '</div>';

//                     }

//                 }
                
                
//             break;
            
//             case 'biblio':

//                 if ( $key2 == 'basic' ) $out .= '<div class="mb-3">' . implode( '<br /><br />', $arr['biblio']['basic'] ) . '</div>';
//                 if ( $key2 == 'additional' ) $out .= '<div class="mb-3">' . implode( '<br /><br />', $arr['biblio']['additional'] ) . '</div>';
                
//             break;
            
//             case 'it':

//                 if ( $key2 == 'inet' ) $out .= '<div class="mb-3">— ' . implode( '<br />— ', $arr['it']['inet'] ) . '</div>';
//                 if ( $key2 == 'app' ) $out .= '<div class="mb-3">— ' . implode( '<br />— ', $arr['it']['app'] ) . '</div>';
                
//             break;
            
//             case 'mto':
                
//                 if ( $key2 == 'mto' ) $out .= '<div class="mb-3">— ' . implode( '<br />— ', $arr['mto']['mto'] ) . '</div>';
                
//             break;
            
//             case 'authors':
                
//                 if ( $key2 == 'authors' ) $out .= '<div class="mb-3">— ' . implode( '<br />— ', $arr['authors']['authors'] ) . '</div>';
            
//             break;
                    
//             // default:
//             // break;
        
//         }

//         $out .= '</div>';

//         return $out;
//     }
    
    







    
    
//     // 
//     //   
//     // 
    
//     public function get_courses_form_xls( $att_id )
//     {

//         $m = new mif_mr_xlsx( get_attached_file( $att_id ) );
//         // $c = $m->get( $this->scheme['name'][0] );
//         $arr = array();

//         // Название, Цель

//         $arr['name'] = $m->get( $this->scheme['name'][0] );
//         $arr['content']['target'] = $m->get( $this->scheme['target'][0] );

//         // Разделы

//         for ( $i = 0; $i < 10; $i++ ) { 

//             if ( empty( $m->get( $this->scheme['parts_name'][$i] ) ) ) continue;

//             $arr['content']['parts'][$i]['name'] = $m->get( $this->scheme['parts_name'][$i] );
//             $arr['content']['parts'][$i]['content'] = $m->get( $this->scheme['parts_content'][$i] );
//             $arr['content']['parts'][$i] ['cmp'] = $m->get( $this->scheme['parts_cmp'][$i] );
//             $arr['content']['parts'][$i] ['hours_raw'] = $m->get( $this->scheme['parts_hours'][$i] );
//             $arr['content']['parts'][$i] ['hours'] = content::get_hours( $m->get( $this->scheme['parts_hours'][$i] ) );
            
//             for ( $j = 0; $j < 10; $j++ ) { 
                
//                 if ( isset( $this->scheme['parts_outcomes_z_'.$j][$i] ) ) $arr['content']['parts'][$i]['outcomes']['z'][] = $m->get( $this->scheme['parts_outcomes_z_'.$j][$i] );
//                 if ( isset( $this->scheme['parts_outcomes_u_'.$j][$i] ) ) $arr['content']['parts'][$i]['outcomes']['u'][] = $m->get( $this->scheme['parts_outcomes_u_'.$j][$i] );
//                 if ( isset( $this->scheme['parts_outcomes_v_'.$j][$i] ) ) $arr['content']['parts'][$i]['outcomes']['v'][] = $m->get( $this->scheme['parts_outcomes_v_'.$j][$i] );

//             }
            
//         }

//         // Оценочные средства
            
//         for ( $i = 0; $i < 10; $i++ ) {

//             for ( $j = 0; $j < 10; $j++ ) { 

//                 if ( empty( $this->scheme['evaluations_name_'.$i][$j] ) ) continue;
//                 if ( empty( $m->get( $this->scheme['evaluations_name_'.$i][$j] ) ) ) continue;
                
//                 $arr['evaluations'][$j]['sem'] = $j;
//                 if ( isset( $this->scheme['evaluations_name_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['name'] = $m->get( $this->scheme['evaluations_name_'.$i][$j] );
//                 if ( isset( $this->scheme['evaluations_rating_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['att']['rating'] = $m->get( $this->scheme['evaluations_rating_'.$i][$j] );
//                 if ( isset( $this->scheme['evaluations_cmp_'.$i][$j] ) ) $arr['evaluations'][$j]['data'][$i]['att']['cmp'] = $m->get( $this->scheme['evaluations_cmp_'.$i][$j] );
                
    
//             }

//         } 

//         // Литература

//         for ( $i = 0; $i < 10; $i++ ) {

//             if ( empty( $this->scheme['biblio_basic'][$i] ) ) continue;
//             if ( empty( $m->get( $this->scheme['biblio_basic'][$i] ) ) ) continue;
            
//             $arr['biblio']['basic'][] = $m->get( $this->scheme['biblio_basic'][$i] );

//         } 

//         for ( $i = 0; $i < 10; $i++ ) {
            
//             if ( empty( $this->scheme['biblio_additional'][$i] ) ) continue;
//             if ( empty( $m->get( $this->scheme['biblio_additional'][$i] ) ) ) continue;
            
//             $arr['biblio']['additional'][] = $m->get( $this->scheme['biblio_additional'][$i] );
        
//         } 
        
//         // Информационные технологии
        
//         for ( $i = 0; $i < 10; $i++ ) {

//             if ( empty( $this->scheme['it_inet'][$i] ) ) continue;
//             if ( empty( $m->get( $this->scheme['it_inet'][$i] ) ) ) continue;
            
//             $arr['it']['inet'][] = $m->get( $this->scheme['it_inet'][$i] );

//         } 
        
//         for ( $i = 0; $i < 10; $i++ ) {

//             if ( empty( $this->scheme['it_app'][$i] ) ) continue;
//             if ( empty( $m->get( $this->scheme['it_app'][$i] ) ) ) continue;
            
//             $arr['it']['app'][] = $m->get( $this->scheme['it_app'][$i] );

//         } 
    
//         // Материально-техническое обеспечение

//         for ( $i = 0; $i < 10; $i++ ) {

//             if ( empty( $this->scheme['mto'][$i] ) ) continue;
//             if ( empty( $m->get( $this->scheme['mto'][$i] ) ) ) continue;
            
//             $arr['mto']['mto'][] = $m->get( $this->scheme['mto'][$i] );

//         } 
    
//         // Разработчики

//         for ( $i = 0; $i < 10; $i++ ) {

//             if ( empty( $this->scheme['authors'][$i] ) ) continue;
//             if ( empty( $m->get( $this->scheme['authors'][$i] ) ) ) continue;
            
//             $arr['authors']['authors'][] = $m->get( $this->scheme['authors'][$i] );

//         } 
    

//         //
//         // guidelines ???? !!!!
//         //








//         // p($arr);


//         return $arr;
//     }


        
    
//     //
//     // Анализ
//     // 
    
//     public function analysis( $att = array() )
//     {
//     //     // p($_REQUEST);
//         // p($att);
        
//         // Course 1
        
//         if ( empty( $att['att_id'] ) ) return;

//         // $att = get_post( $att['att_id'] );
        
//         // if ( empty( $att ) ) return;
//         // if ( $att->post_type != 'attachment' ) return;
//         // if ( ! in_array( mif_mr_functions::get_ext( $att->guid ), array( 'xls', 'xlsx' ) ) ) return;
        
//         // $arr = $this->set_courses_form_xls( $att_id );
//         $a = $this->get_info_courses( $att['att_id'] );
        
//         $arr_info['title'] = ( $a['is_course'] ) ? $a['title'] : 'Дисциплина не обнаружена';
//         $arr_info['is_curriculum'] = ( $a['is_curriculum'] ) ? $this->description['curriculum-yes'] : $this->description['curriculum-no'];
//         $arr_info['is_content_local'] = ( $a['is_content_local'] ) ? $this->description['local-yes'] : $this->description['local-no'];    
//         $arr_info['id_local'] = ( $a['is_content_local'] ) ? ': ' . $this->get_link_local( $a ) . '': '';    
//         $arr_info['is_content_lib'] = ( $a['is_content_lib'] ) ? $this->description['lib-yes'] : $this->description['lib-no'];    
//         $arr_info['id_libs'] = ( $a['is_content_lib'] ) ? ': ' . $this->get_link_lib( $a ) . '' : '';    

//         $comment = '';
        
//         if ( ! $a['is_curriculum'] ) $comment .= 'curriculum-no';

//         if ( $a['is_content_local'] && $a['percent_local'] == 100 ) $comment .= 'local-yes, 100';
//         elseif ( $a['is_content_local'] && $a['percent_local'] < 100 ) $comment .= 'local-no, < 99';
//         elseif ( $a['is_content_lib'] && $a['percent_lib_max'] == 100 ) $comment .= 'lib-yes, 100';
//         elseif ( $a['is_content_lib'] && $a['percent_lib_max'] < 100 ) $comment .= 'lib-yes, < 99';
//         elseif ( ! $a['is_content_local'] ) $comment .= 'local-no';
        
//         // $comment = '###';

//         // p($a);

//         if ( ! empty( $a['id_local'] ) ) {

//             $course_id = $a['id_local'];
            
//         } elseif ( ! empty( $a['id_libs'][0] ) ) {
            
//             $course_id = $a['id_libs'][0];
            
//         } else {
            
//             $course_id = NULL;

//         }

//         if ( ! empty( $att['course_id'] ) ) $course_id = $att['course_id'];

//         // Course 2
        
//         // $courses_id = 600;
        
//         // global $tree;
//         // $arr2 = array();
//         // if ( isset( $tree['content']['lib-courses']['data'][$courses_id]['data'] ) ) $arr2 = $tree['content']['lib-courses']['data'][$courses_id]['data'];
//         // // p($tree);
        
//         // p($att);
//         $out = '';

//         if ( $att['course_id'] == 0 ) $out .= '<div class="row analysis-box" style="display:none;">';
      
//         $out .= '<div class="col p-3">';
        
//         $out .= '<div class="bg-light border p-3 rounded">';
//         $out .= '<div>' . $arr_info['is_curriculum'] . '</div>';
//         $out .= '<div>' . $arr_info['is_content_local'] . $arr_info['id_local'] . '</div>';
//         $out .= '<div>' . $arr_info['is_content_lib'] . $arr_info['id_libs'] . '</div>';
//         $out .= '</div>';
        
//         // $out .= '<div class="col p-3">';

//         $out .= $this->show_file_courses( $att['att_id'], $course_id );


//         // $out .= '<div class="ro mb-5">';
//         $out .= '<div class="bg-light p-4 pt-5 pb-5">';

//         $out .= '<a href="#" class="mr-3 export-panel"><i class="fa-regular fa-floppy-disk fa-lg"></i></a>';
//         $out .= '<a href="#" class="mr-3 remove-panel"><i class="fa-regular fa-trash-can fa-lg"></i></a>';
//         $out .= '<a href="#" class="mr-3 cancel-panel"><i class="fa-solid fa-angle-up"></i></a>';
//         $out .= '<div  class="mt-3">' . $comment . '</div>';

//         $out .= '</div>';
//         // $out .= '</div>';

//         $out .= '</div>';
        
//         if ( $att['course_id'] == 0 ) $out .= '</div>';

//         return $out;
//     }




//     // 
//     // Save (export)
//     // 

//     function export( $att = array() )
//     {
//         global $tree;
        
//         $out = '';
        
//         $arr = $this->get_courses_form_xls( $att['att_id'] );
//         $arr_info = $this->get_info_courses( $att['att_id'] );
        
//         $m = new mif_mr_lib_courses();
//         $arr2 = $m->arr_to_text( $arr );
        
//         if ( empty( $arr_info['id_local'] ) ) {
            
//             $res = $m->companion_insert( array(
//                                             'title' => $arr['name'],
//                                             'data' => $arr2,
//                                             'type'     => 'lib-courses',
//                                             'opop_id'   => $att['opop_id'],
//                                             ) );

//         } else {
            
//             $res = $m->save( $arr_info['id_local'], $arr2 );

//         }

//         if ( $res ) {

//             $out .= mif_mr_functions::get_callout( 'Сохранено: <span class="fw-semibold">' . $arr['name'] . 
//                                                     '</span> <a href="' .  mif_mr_opop_core::get_opop_url() . 
//                                                     'lib-courses/' . $res . '" target="_blank"><i class="fa-solid fa-arrow-right"></i></a>', 'success' );

//         } else {

//             $out .= mif_mr_functions::get_callout( 'Какая-то ошибка: <span class="fw-semibold">' . $arr['name'] . '</span>', 'danger' );

//         }

        

//         return $out;
//     }






//     function set_save_title( $title, $tmp_name )
//     {
//         $m = new mif_mr_xlsx( $tmp_name );
//         $name = $m->get( $this->scheme['name'][0] );

//         if ( ! empty( $name ) ) $title = $name;

//         return $title;
//     }





//     function scheme_data_courses( $arr )
//     {

//         //
//         //  ## @@@ ### !!!!!!!!!!
//         //

//         // $m = new mif_mr_xlsx( get_attached_file( $att_id ) );
//         // $arr = $m->get_arr();

//         // foreach ( $arr as $k => $i )
//         // foreach ( $i as $k2 => $i2 ) {

//         //     if ( empty( $i2 ) ) continue;
//         //     if ( preg_match( '/^\[/', $i2 ) ) {
                
//         //         $i2 = trim( $i2, '[]' );
//         //         p(  '$arr["' . $i2 . '"][] = "' . $k2 . $k . '";' );

//         //     }
//         //     // p($k);
//         //     // p($k2);
    
    
//         // }

//         $arr["version"][] = "A0";
//         $arr["name"][] = "C4";
//         $arr["target"][] = "C7";
//         $arr["parts_name"][] = "C9";
//         $arr["parts_name"][] = "F9";
//         $arr["parts_name"][] = "I9";
//         $arr["parts_name"][] = "L9";
//         $arr["parts_name"][] = "O9";
//         $arr["parts_name"][] = "R9";
//         $arr["parts_name"][] = "U9";
//         $arr["parts_name"][] = "X9";
//         $arr["parts_name"][] = "AA9";
//         $arr["parts_name"][] = "AD9";
//         $arr["parts_cmp"][] = "C10";
//         $arr["parts_cmp"][] = "F10";
//         $arr["parts_cmp"][] = "I10";
//         $arr["parts_cmp"][] = "L10";
//         $arr["parts_cmp"][] = "O10";
//         $arr["parts_cmp"][] = "R10";
//         $arr["parts_cmp"][] = "U10";
//         $arr["parts_cmp"][] = "X10";
//         $arr["parts_cmp"][] = "AA10";
//         $arr["parts_cmp"][] = "AD10";
//         $arr["parts_hours"][] = "C11";
//         $arr["parts_hours"][] = "F11";
//         $arr["parts_hours"][] = "I11";
//         $arr["parts_hours"][] = "L11";
//         $arr["parts_hours"][] = "O11";
//         $arr["parts_hours"][] = "R11";
//         $arr["parts_hours"][] = "U11";
//         $arr["parts_hours"][] = "X11";
//         $arr["parts_hours"][] = "AA11";
//         $arr["parts_hours"][] = "AD11";
//         $arr["parts_content"][] = "C12";
//         $arr["parts_content"][] = "F12";
//         $arr["parts_content"][] = "I12";
//         $arr["parts_content"][] = "L12";
//         $arr["parts_content"][] = "O12";
//         $arr["parts_content"][] = "R12";
//         $arr["parts_content"][] = "U12";
//         $arr["parts_content"][] = "X12";
//         $arr["parts_content"][] = "AA12";
//         $arr["parts_content"][] = "AD12";
//         $arr["parts_outcomes_z_0"][] = "C13";
//         $arr["parts_outcomes_z_0"][] = "F13";
//         $arr["parts_outcomes_z_0"][] = "I13";
//         $arr["parts_outcomes_z_0"][] = "L13";
//         $arr["parts_outcomes_z_0"][] = "O13";
//         $arr["parts_outcomes_z_0"][] = "R13";
//         $arr["parts_outcomes_z_0"][] = "U13";
//         $arr["parts_outcomes_z_0"][] = "X13";
//         $arr["parts_outcomes_z_0"][] = "AA13";
//         $arr["parts_outcomes_z_0"][] = "AD13";
//         $arr["parts_outcomes_u_0"][] = "C14";
//         $arr["parts_outcomes_u_0"][] = "F14";
//         $arr["parts_outcomes_u_0"][] = "I14";
//         $arr["parts_outcomes_u_0"][] = "L14";
//         $arr["parts_outcomes_u_0"][] = "O14";
//         $arr["parts_outcomes_u_0"][] = "R14";
//         $arr["parts_outcomes_u_0"][] = "U14";
//         $arr["parts_outcomes_u_0"][] = "X14";
//         $arr["parts_outcomes_u_0"][] = "AA14";
//         $arr["parts_outcomes_u_0"][] = "AD14";
//         $arr["parts_outcomes_v_0"][] = "C15";
//         $arr["parts_outcomes_v_0"][] = "F15";
//         $arr["parts_outcomes_v_0"][] = "I15";
//         $arr["parts_outcomes_v_0"][] = "L15";
//         $arr["parts_outcomes_v_0"][] = "O15";
//         $arr["parts_outcomes_v_0"][] = "R15";
//         $arr["parts_outcomes_v_0"][] = "U15";
//         $arr["parts_outcomes_v_0"][] = "X15";
//         $arr["parts_outcomes_v_0"][] = "AA15";
//         $arr["parts_outcomes_v_0"][] = "AD15";
//         $arr["parts_outcomes_z_1"][] = "C16";
//         $arr["parts_outcomes_z_1"][] = "F16";
//         $arr["parts_outcomes_z_1"][] = "I16";
//         $arr["parts_outcomes_z_1"][] = "L16";
//         $arr["parts_outcomes_z_1"][] = "O16";
//         $arr["parts_outcomes_z_1"][] = "R16";
//         $arr["parts_outcomes_z_1"][] = "U16";
//         $arr["parts_outcomes_z_1"][] = "X16";
//         $arr["parts_outcomes_z_1"][] = "AA16";
//         $arr["parts_outcomes_z_1"][] = "AD16";
//         $arr["parts_outcomes_u_1"][] = "C17";
//         $arr["parts_outcomes_u_1"][] = "F17";
//         $arr["parts_outcomes_u_1"][] = "I17";
//         $arr["parts_outcomes_u_1"][] = "L17";
//         $arr["parts_outcomes_u_1"][] = "O17";
//         $arr["parts_outcomes_u_1"][] = "R17";
//         $arr["parts_outcomes_u_1"][] = "U17";
//         $arr["parts_outcomes_u_1"][] = "X17";
//         $arr["parts_outcomes_u_1"][] = "AA17";
//         $arr["parts_outcomes_u_1"][] = "AD17";
//         $arr["parts_outcomes_v_1"][] = "C18";
//         $arr["parts_outcomes_v_1"][] = "F18";
//         $arr["parts_outcomes_v_1"][] = "I18";
//         $arr["parts_outcomes_v_1"][] = "L18";
//         $arr["parts_outcomes_v_1"][] = "O18";
//         $arr["parts_outcomes_v_1"][] = "R18";
//         $arr["parts_outcomes_v_1"][] = "U18";
//         $arr["parts_outcomes_v_1"][] = "X18";
//         $arr["parts_outcomes_v_1"][] = "AA18";
//         $arr["parts_outcomes_v_1"][] = "AD18";
//         $arr["evaluations_name_0"][] = "C22";
//         $arr["evaluations_rating_0"][] = "D22";
//         $arr["evaluations_cmp_0"][] = "E22";
//         $arr["evaluations_name_0"][] = "F22";
//         $arr["evaluations_rating_0"][] = "G22";
//         $arr["evaluations_cmp_0"][] = "H22";
//         $arr["evaluations_name_0"][] = "I22";
//         $arr["evaluations_rating_0"][] = "J22";
//         $arr["evaluations_cmp_0"][] = "K22";
//         $arr["evaluations_name_0"][] = "L22";
//         $arr["evaluations_rating_0"][] = "M22";
//         $arr["evaluations_cmp_0"][] = "N22";
//         $arr["evaluations_name_0"][] = "O22";
//         $arr["evaluations_rating_0"][] = "P22";
//         $arr["evaluations_cmp_0"][] = "Q22";
//         $arr["evaluations_name_0"][] = "R22";
//         $arr["evaluations_rating_0"][] = "S22";
//         $arr["evaluations_cmp_0"][] = "T22";
//         $arr["evaluations_name_0"][] = "U22";
//         $arr["evaluations_rating_0"][] = "V22";
//         $arr["evaluations_cmp_0"][] = "W22";
//         $arr["evaluations_name_0"][] = "X22";
//         $arr["evaluations_rating_0"][] = "Y22";
//         $arr["evaluations_cmp_0"][] = "Z22";
//         $arr["evaluations_name_0"][] = "AA22";
//         $arr["evaluations_rating_0"][] = "AB22";
//         $arr["evaluations_cmp_0"][] = "AC22";
//         $arr["evaluations_name_0"][] = "AD22";
//         $arr["evaluations_rating_0"][] = "AE22";
//         $arr["evaluations_cmp_0"][] = "AF22";
//         $arr["evaluations_name_1"][] = "C23";
//         $arr["evaluations_rating_1"][] = "D23";
//         $arr["evaluations_cmp_1"][] = "E23";
//         $arr["evaluations_name_1"][] = "F23";
//         $arr["evaluations_rating_1"][] = "G23";
//         $arr["evaluations_cmp_1"][] = "H23";
//         $arr["evaluations_name_1"][] = "I23";
//         $arr["evaluations_rating_1"][] = "J23";
//         $arr["evaluations_cmp_1"][] = "K23";
//         $arr["evaluations_name_1"][] = "L23";
//         $arr["evaluations_rating_1"][] = "M23";
//         $arr["evaluations_cmp_1"][] = "N23";
//         $arr["evaluations_name_1"][] = "O23";
//         $arr["evaluations_rating_1"][] = "P23";
//         $arr["evaluations_cmp_1"][] = "Q23";
//         $arr["evaluations_name_1"][] = "R23";
//         $arr["evaluations_rating_1"][] = "S23";
//         $arr["evaluations_cmp_1"][] = "T23";
//         $arr["evaluations_name_1"][] = "U23";
//         $arr["evaluations_rating_1"][] = "V23";
//         $arr["evaluations_cmp_1"][] = "W23";
//         $arr["evaluations_name_1"][] = "X23";
//         $arr["evaluations_rating_1"][] = "Y23";
//         $arr["evaluations_cmp_1"][] = "Z23";
//         $arr["evaluations_name_1"][] = "AA23";
//         $arr["evaluations_rating_1"][] = "AB23";
//         $arr["evaluations_cmp_1"][] = "AC23";
//         $arr["evaluations_name_1"][] = "AD23";
//         $arr["evaluations_rating_1"][] = "AE23";
//         $arr["evaluations_cmp_1"][] = "AF23";
//         $arr["evaluations_name_2"][] = "C24";
//         $arr["evaluations_rating_2"][] = "D24";
//         $arr["evaluations_cmp_2"][] = "E24";
//         $arr["evaluations_name_2"][] = "F24";
//         $arr["evaluations_rating_2"][] = "G24";
//         $arr["evaluations_cmp_2"][] = "H24";
//         $arr["evaluations_name_2"][] = "I24";
//         $arr["evaluations_rating_2"][] = "J24";
//         $arr["evaluations_cmp_2"][] = "K24";
//         $arr["evaluations_name_2"][] = "L24";
//         $arr["evaluations_rating_2"][] = "M24";
//         $arr["evaluations_cmp_2"][] = "N24";
//         $arr["evaluations_name_2"][] = "O24";
//         $arr["evaluations_rating_2"][] = "P24";
//         $arr["evaluations_cmp_2"][] = "Q24";
//         $arr["evaluations_name_2"][] = "R24";
//         $arr["evaluations_rating_2"][] = "S24";
//         $arr["evaluations_cmp_2"][] = "T24";
//         $arr["evaluations_name_2"][] = "U24";
//         $arr["evaluations_rating_2"][] = "V24";
//         $arr["evaluations_cmp_2"][] = "W24";
//         $arr["evaluations_name_2"][] = "X24";
//         $arr["evaluations_rating_2"][] = "Y24";
//         $arr["evaluations_cmp_2"][] = "Z24";
//         $arr["evaluations_name_2"][] = "AA24";
//         $arr["evaluations_rating_2"][] = "AB24";
//         $arr["evaluations_cmp_2"][] = "AC24";
//         $arr["evaluations_name_2"][] = "AD24";
//         $arr["evaluations_rating_2"][] = "AE24";
//         $arr["evaluations_cmp_2"][] = "AF24";
//         $arr["evaluations_name_3"][] = "C25";
//         $arr["evaluations_rating_3"][] = "D25";
//         $arr["evaluations_cmp_3"][] = "E25";
//         $arr["evaluations_name_3"][] = "F25";
//         $arr["evaluations_rating_3"][] = "G25";
//         $arr["evaluations_cmp_3"][] = "H25";
//         $arr["evaluations_name_3"][] = "I25";
//         $arr["evaluations_rating_3"][] = "J25";
//         $arr["evaluations_cmp_3"][] = "K25";
//         $arr["evaluations_name_3"][] = "L25";
//         $arr["evaluations_rating_3"][] = "M25";
//         $arr["evaluations_cmp_3"][] = "N25";
//         $arr["evaluations_name_3"][] = "O25";
//         $arr["evaluations_rating_3"][] = "P25";
//         $arr["evaluations_cmp_3"][] = "Q25";
//         $arr["evaluations_name_3"][] = "R25";
//         $arr["evaluations_rating_3"][] = "S25";
//         $arr["evaluations_cmp_3"][] = "T25";
//         $arr["evaluations_name_3"][] = "U25";
//         $arr["evaluations_rating_3"][] = "V25";
//         $arr["evaluations_cmp_3"][] = "W25";
//         $arr["evaluations_name_3"][] = "X25";
//         $arr["evaluations_rating_3"][] = "Y25";
//         $arr["evaluations_cmp_3"][] = "Z25";
//         $arr["evaluations_name_3"][] = "AA25";
//         $arr["evaluations_rating_3"][] = "AB25";
//         $arr["evaluations_cmp_3"][] = "AC25";
//         $arr["evaluations_name_3"][] = "AD25";
//         $arr["evaluations_rating_3"][] = "AE25";
//         $arr["evaluations_cmp_3"][] = "AF25";
//         $arr["evaluations_name_4"][] = "C26";
//         $arr["evaluations_rating_4"][] = "D26";
//         $arr["evaluations_cmp_4"][] = "E26";
//         $arr["evaluations_name_4"][] = "F26";
//         $arr["evaluations_rating_4"][] = "G26";
//         $arr["evaluations_cmp_4"][] = "H26";
//         $arr["evaluations_name_4"][] = "I26";
//         $arr["evaluations_rating_4"][] = "J26";
//         $arr["evaluations_cmp_4"][] = "K26";
//         $arr["evaluations_name_4"][] = "L26";
//         $arr["evaluations_rating_4"][] = "M26";
//         $arr["evaluations_cmp_4"][] = "N26";
//         $arr["evaluations_name_4"][] = "O26";
//         $arr["evaluations_rating_4"][] = "P26";
//         $arr["evaluations_cmp_4"][] = "Q26";
//         $arr["evaluations_name_4"][] = "R26";
//         $arr["evaluations_rating_4"][] = "S26";
//         $arr["evaluations_cmp_4"][] = "T26";
//         $arr["evaluations_name_4"][] = "U26";
//         $arr["evaluations_rating_4"][] = "V26";
//         $arr["evaluations_cmp_4"][] = "W26";
//         $arr["evaluations_name_4"][] = "X26";
//         $arr["evaluations_rating_4"][] = "Y26";
//         $arr["evaluations_cmp_4"][] = "Z26";
//         $arr["evaluations_name_4"][] = "AA26";
//         $arr["evaluations_rating_4"][] = "AB26";
//         $arr["evaluations_cmp_4"][] = "AC26";
//         $arr["evaluations_name_4"][] = "AD26";
//         $arr["evaluations_rating_4"][] = "AE26";
//         $arr["evaluations_cmp_4"][] = "AF26";
//         $arr["evaluations_name_5"][] = "C27";
//         $arr["evaluations_rating_5"][] = "D27";
//         $arr["evaluations_cmp_5"][] = "E27";
//         $arr["evaluations_name_5"][] = "F27";
//         $arr["evaluations_rating_5"][] = "G27";
//         $arr["evaluations_cmp_5"][] = "H27";
//         $arr["evaluations_name_5"][] = "I27";
//         $arr["evaluations_rating_5"][] = "J27";
//         $arr["evaluations_cmp_5"][] = "K27";
//         $arr["evaluations_name_5"][] = "L27";
//         $arr["evaluations_rating_5"][] = "M27";
//         $arr["evaluations_cmp_5"][] = "N27";
//         $arr["evaluations_name_5"][] = "O27";
//         $arr["evaluations_rating_5"][] = "P27";
//         $arr["evaluations_cmp_5"][] = "Q27";
//         $arr["evaluations_name_5"][] = "R27";
//         $arr["evaluations_rating_5"][] = "S27";
//         $arr["evaluations_cmp_5"][] = "T27";
//         $arr["evaluations_name_5"][] = "U27";
//         $arr["evaluations_rating_5"][] = "V27";
//         $arr["evaluations_cmp_5"][] = "W27";
//         $arr["evaluations_name_5"][] = "X27";
//         $arr["evaluations_rating_5"][] = "Y27";
//         $arr["evaluations_cmp_5"][] = "Z27";
//         $arr["evaluations_name_5"][] = "AA27";
//         $arr["evaluations_rating_5"][] = "AB27";
//         $arr["evaluations_cmp_5"][] = "AC27";
//         $arr["evaluations_name_5"][] = "AD27";
//         $arr["evaluations_rating_5"][] = "AE27";
//         $arr["evaluations_cmp_5"][] = "AF27";
//         $arr["evaluations_name_6"][] = "C28";
//         $arr["evaluations_rating_6"][] = "D28";
//         $arr["evaluations_cmp_6"][] = "E28";
//         $arr["evaluations_name_6"][] = "F28";
//         $arr["evaluations_rating_6"][] = "G28";
//         $arr["evaluations_cmp_6"][] = "H28";
//         $arr["evaluations_name_6"][] = "I28";
//         $arr["evaluations_rating_6"][] = "J28";
//         $arr["evaluations_cmp_6"][] = "K28";
//         $arr["evaluations_name_6"][] = "L28";
//         $arr["evaluations_rating_6"][] = "M28";
//         $arr["evaluations_cmp_6"][] = "N28";
//         $arr["evaluations_name_6"][] = "O28";
//         $arr["evaluations_rating_6"][] = "P28";
//         $arr["evaluations_cmp_6"][] = "Q28";
//         $arr["evaluations_name_6"][] = "R28";
//         $arr["evaluations_rating_6"][] = "S28";
//         $arr["evaluations_cmp_6"][] = "T28";
//         $arr["evaluations_name_6"][] = "U28";
//         $arr["evaluations_rating_6"][] = "V28";
//         $arr["evaluations_cmp_6"][] = "W28";
//         $arr["evaluations_name_6"][] = "X28";
//         $arr["evaluations_rating_6"][] = "Y28";
//         $arr["evaluations_cmp_6"][] = "Z28";
//         $arr["evaluations_name_6"][] = "AA28";
//         $arr["evaluations_rating_6"][] = "AB28";
//         $arr["evaluations_cmp_6"][] = "AC28";
//         $arr["evaluations_name_6"][] = "AD28";
//         $arr["evaluations_rating_6"][] = "AE28";
//         $arr["evaluations_cmp_6"][] = "AF28";
//         $arr["authors"][] = "C31";
//         $arr["authors"][] = "C32";
//         $arr["authors"][] = "C33";
//         $arr["authors"][] = "C34";
//         $arr["authors"][] = "C35";
//         $arr["biblio_basic"][] = "C41";
//         $arr["biblio_basic"][] = "C42";
//         $arr["biblio_basic"][] = "C43";
//         $arr["biblio_basic"][] = "C44";
//         $arr["biblio_basic"][] = "C45";
//         $arr["biblio_additional"][] = "C47";
//         $arr["biblio_additional"][] = "C48";
//         $arr["biblio_additional"][] = "C49";
//         $arr["biblio_additional"][] = "C50";
//         $arr["biblio_additional"][] = "C51";
//         $arr["biblio_additional"][] = "C52";
//         $arr["biblio_additional"][] = "C53";
//         $arr["biblio_additional"][] = "C54";
//         $arr["biblio_additional"][] = "C55";
//         $arr["biblio_additional"][] = "C56";
//         $arr["it_inet"][] = "C60";
//         $arr["it_inet"][] = "C61";
//         $arr["it_inet"][] = "C62";
//         $arr["it_inet"][] = "C63";
//         $arr["it_inet"][] = "C64";
//         $arr["it_app"][] = "C66";
//         $arr["it_app"][] = "C67";
//         $arr["it_app"][] = "C68";
//         $arr["it_app"][] = "C69";
//         $arr["it_app"][] = "C70";
//         $arr["mto"][] = "C73";
//         $arr["mto"][] = "C74";
//         $arr["mto"][] = "C75";
//         $arr["mto"][] = "C76";
//         $arr["mto"][] = "C77";

//         return $arr;
//     }


//     protected $scheme = array();
//     protected $index = array();
//     protected $description = array();
//     // protected $index_part = array();

}

?>