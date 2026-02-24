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
        
        $this->description = apply_filters( 'description-set-courses', array(
                'none' => 'Не закреплена',
                'local'=> 'Автоматически (локальная)', 
                'lib' => 'Автоматически (из библиотеки)', 
                'manual' => 'Ручной метод',
                'count-yes' => 'Существуют другие варианты',
                'count-no' => 'Единственный вариант в библиотеке',
                'name-old-yes' => 'Название изменилось',
                'name-old-no' => 'Название не изменилось',
            ) );
            
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
        
        if ( isset( $_REQUEST['edit'] ) ) {
                
            $out = $this->companion_edit( 'set-courses' );
            
        } else {
            
            $arr = $tree['content']['courses']['index'];
            // ksort( $arr );
            // p($arr);

            $a = array();

            foreach ( $arr as $k => $i ) {

                // label_1

                $a[$k]['label_1'] = 'o';
                $a[$k]['color_1'] = 'mr-red-2';
                $a[$k]['item_1'] = 'none';
                $a[$k]['desc_1'] = $this->description['none'];

                if ( isset( $i['method'] ) && $i['method'] == 'local' ) {
                    $a[$k]['label_1'] = 'a';
                    $a[$k]['color_1'] = 'mr-green-2';
                    $a[$k]['item_1'] = 'local';
                    $a[$k]['desc_1'] = $this->description['local'];
                }
                    
                if ( isset( $i['method'] ) && $i['method'] == 'lib' ) {
                    $a[$k]['label_1'] = 'b';
                    $a[$k]['color_1'] = 'mr-blue-2';
                    $a[$k]['item_1'] = 'lib';
                    $a[$k]['desc_1'] = $this->description['lib'];
                }
                    
                if ( isset( $i['method'] ) && $i['method'] == 'manual' ) {
                    $a[$k]['label_1'] = 'p';
                    $a[$k]['color_1'] = 'mr-magenta-2';
                    $a[$k]['item_1'] = 'manual';
                    $a[$k]['desc_1'] = $this->description['manual'];
                }

                // label_2

                $a[$k]['label_2'] = '';
                $a[$k]['color_2'] = '';
                $a[$k]['item_2'] = 'count-no';
                $a[$k]['desc_2'] = '';
                // $a[$k]['desc_2']['desc'] = $this->description['count-no'];
                // $a[$k]['label_2'] = 'o';
                // $a[$k]['color_2'] = 'mr-gray-2';
                
                if ( isset( $i['count'] ) && $i['count'] > 1 ) {
                    $a[$k]['label_2'] = 'm';
                    $a[$k]['color_2'] = 'mr-blue-2';
                    $a[$k]['item_2'] = 'count-yes';
                    $a[$k]['desc_2'] = $this->description['count-yes'];
                }

                // label_3

                $a[$k]['label_3'] = '';
                $a[$k]['color_3'] = '';
                $a[$k]['item_3'] = 'name-old-no';
                $a[$k]['desc_3'] = '';
                // $a[$k]['desc_3']['desc'] = $this->description['name-old-no'];
                // $a[$k]['label_3'] = 'o';
                // $a[$k]['color_3'] = 'mr-gray-2';
                
                if ( isset( $i['name_old'] ) && $i['name_old'] != $k ) {
                    $a[$k]['label_3'] = 'e';
                    $a[$k]['color_3'] = 'mr-orange-2';
                    $a[$k]['item_3'] = 'name-old-yes';
                    $a[$k]['desc_3'] = $this->description['name-old-yes'];
                }

            }


            // $arr2 = array(
            //             array( 'none', 'mr-red-2', 'o', '1', $this->description['none'], true ),
            //             array( 'local', 'mr-green-2', 'a', '1', $this->description['local'], true ),
            //             array( 'lib', 'mr-blue-2', 'b', '1', $this->description['lib'], true ),
            //             array( 'manual', 'mr-magenta-2', 'o', '1', $this->description['manual'], true ), 
            //             array( 'count-yes', 'mr-blue-2', 'p', '2', $this->description['count-yes'], true ),
            //             array( 'count-no', 'mr-gray-2', 'o', '2', $this->description['count-no'], true ),
            //             array( 'name-old-yes', 'mr-orange-2', 'e', '3', $this->description['name-old-yes'], true ), 
            //             array( 'name-old-no', 'mr-gray-2', 'o', '3', $this->description['name-old-no'], true ),
            //             );

                     
            // foreach ($this->description as $k => $d ) $arr2[] = array( $k, 'mr-gray-2', '1', $d, true );

            // p($arr2);



            $out = '';

            $out .= '<div class="row fiksa">';
            $out .= '<div class="col p-0">';
            $out .= '<h4 class="mb-4 mt-0 pt-3 pb-5 bg-body">Дисциплины в ОПОП:</h4>';
            $out .= '</div>';
            $out .= '</div>';

            $out .= '<div class="row">';
            $out .= '<div class="col p-2 pt-4 pb-4 select-menu">';
            $out .= $this->get_select_menu( $arr );
            $out .= '</div>';
            $out .= '</div>';


            $out .= '<div class="row fw-semibold">';
            $out .= '<div class="col col-1 p-2 pt-4 pb-4">№</div>';
            $out .= '<div class="col col-11 p-2 pt-4 pb-4">Название дисциплины</div>';
            $out .= '</div>';

            $n = 1;
            
            $out .= '<div class="striped">';

            foreach ( $arr as $k => $i ) {
                
                // p($i);
                $span_id = ( isset( $i['course_id']) ) ? mif_mr_opop_core::get_span_id( $i['course_id'] ) : '';
                $question = ( isset( $i['name_old']) ) ? '<span class="question rounded-circle mr-gray ml-2 p-1 pr-2 pl-2"><i class="fa-solid fa-question fa-xs"></i></span>' : '';
                $name_old = ( isset( $i['name_old']) ) ? '<div class="answer" style="display: none;"><div class="p-2 mt-3 mb-3 mr-gray"><i>Старое название: ' . $i['name_old'] . '</i></div></div>' : '';

                $out .= '<div class="row select-item select-yes ' . $a[$k]['item_1'] . ' ' . $a[$k]['item_2'] . ' ' . $a[$k]['item_3'] . '">';
                $out .= '<div class="col col-1 p-2">' . $n++ . '</div>';
                
                $out .= '<div class="col p-2">' . $k . $question . $name_old . '</div>';

                $out .= '<div class="col col-2 p-2">';
                $out .= mif_mr_opop_core::get_span_label( $a[$k]['label_3'], $a[$k]['color_3'], $a[$k]['desc_3'] ); 
                $out .= mif_mr_opop_core::get_span_label( $a[$k]['label_2'], $a[$k]['color_2'], $a[$k]['desc_2'] ); 
                $out .= mif_mr_opop_core::get_span_label( $a[$k]['label_1'], $a[$k]['color_1'], $a[$k]['desc_1'] ); 
                $out .= '</div>';
                
                $out .= '<div class="col col-1 p-2">' . $span_id . '</div>';


                $out .= '</div>';
                
            }
            
            $out .= '</div>';

        }
        
        



        
        return apply_filters( 'mif_mr_show_set_courses', $out );
    }
    
    
    

    //
    // Получить меню выбора
    //

    private function get_select_menu( $arr_info )
    {
        // p($arr_info);

        $arr = array(
                'none' => false,
                'local' => false,
                'lib' => false,
                'manual' => false,
                'count-yes' => false,
                'count-no' => false,
                'name-old-yes' => false,
                'name-old-no' => false,
            );

        // foreach ( $arr_info as $item ) {

        //     $arr[$item['item_1']] = true;
        //     $arr[$item['item_2']] = true;
        //     $arr[$item['item_3']] = true;

        // }
        
        // p($arr);
        
        $arr2 = array( '1' => array(), '2' => array(), '3' => array() );
        
        foreach ( $arr_info as $i ) {

            if ( isset( $i['method'] ) ) $arr2['1'][$i['method']] = true;
            if ( ! isset( $i['method'] ) ) $arr2['1']['none'] = true;
            
            
            if ( ! isset( $i['count'] ) ) $arr2['2']['count-no'] = true;
            if ( isset( $i['count'] ) && ! $i['count'] > 1 ) $arr2['2']['count-no'] = true;
            if ( isset( $i['count'] ) && $i['count'] > 1 ) $arr2['2']['count-yes'] = true;

            if ( isset( $i['name_old'] ) ) $arr2['3']['name-old-yes'] = true;
            if ( ! isset( $i['name_old'] ) ) $arr2['3']['name-old-no'] = true;

        }
        
        foreach ( $arr2 as $k => $i ) if ( count( $i ) <= 1 ) unset( $arr2[$k] );
        foreach ( $arr2 as $i ) foreach ( $i as $k => $v ) $arr[$k] = true;

        // p($arr);

        $arr3 = array(
                    array( 'none', 'mr-red-2', 'o', 'item-1', $this->description['none'], $arr['none'] ),
                    array( 'local', 'mr-green-2', 'a', 'item-1', $this->description['local'], $arr['local'] ),
                    array( 'lib', 'mr-blue-2', 'b', 'item-1', $this->description['lib'], $arr['lib'] ),
                    array( 'manual', 'mr-magenta-2', 'p', 'item-1', $this->description['manual'], $arr['manual'] ), 
                    array( 'count-yes', 'mr-blue-2', 'm', 'item-2', $this->description['count-yes'], $arr['count-yes'] ),
                    array( 'count-no', 'mr-gray-2', 'o', 'item-2', $this->description['count-no'], $arr['count-no'] ),
                    array( 'name-old-yes', 'mr-orange-2', 'e', 'item-3', $this->description['name-old-yes'], $arr['name-old-yes'] ), 
                    array( 'name-old-no', 'mr-gray-2', 'o', 'item-3', $this->description['name-old-no'], $arr['name-old-no'] ),
                    );

        $out = '';
        $out .= mif_mr_opop_core::get_select_menu_show( $arr3 );        

        return $out;
    }
    




    protected $description = array();

}

?>