<?php

//
// Экспорт данных учебных дисциплин
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_tools_courses extends mif_mr_tools_info {
    
    function __construct()
    {
        parent::__construct();

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
        
        $out .= $this->get_list();
        
        
        // Мета-поля

        $out .= $this->get_meta( 'courses' );


        // // Показать courses

        // if ( ! empty( $att_id ) ) $out .= $this->show_file_courses( $att_id );
        
        return apply_filters( 'mif_mr_get_tools_courses', $out );

    }
    

    
    
    // 
    // Вывести  
    // 

    public function get_list()
    {
        $out = '';

        $arr = $this->get_file( array( 'ext' => array( 'xls', 'xlsx' ), 'orderby' => 'title', 'order' => 'ASC' ) );

        // p($arr);

        $arr_info = array();

        foreach ( $arr as $key => $item ) {
            
            $a = $this->get_info_courses( $item->ID );

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
    





    // 
    // Save (export)
    // 

    function export( $att = array() )
    {
        global $tree;
        
        $out = '';
        
        $arr = $this->get_courses_form_xls( $att['att_id'] );
        $arr_info = $this->get_info_courses( $att['att_id'] );
        
        $m = new mif_mr_lib_courses();
        $arr2 = $m->arr_to_text( $arr );
        
        if ( empty( $arr_info['id_local'] ) ) {
            
            $res = $m->companion_insert( array(
                                            'title' => $arr['name'],
                                            'data' => $arr2,
                                            'type'     => 'lib-courses',
                                            'opop_id'   => $att['opop_id'],
                                            ) );

        } else {
            
            $res = $m->save( $arr_info['id_local'], $arr2 );

        }

        if ( $res ) {

            $out .= mif_mr_functions::get_callout( 'Сохранено: <span class="fw-semibold">' . $arr['name'] . 
                                                    '</span> <a href="' .  mif_mr_opop_core::get_opop_url() . 
                                                    'lib-courses/' . $res . '" target="_blank"><i class="fa-solid fa-arrow-right"></i></a>', 'success' );

        } else {

            $out .= mif_mr_functions::get_callout( 'Какая-то ошибка: <span class="fw-semibold">' . $arr['name'] . '</span>', 'danger' );

        }

        return $out;
    }



}

?>