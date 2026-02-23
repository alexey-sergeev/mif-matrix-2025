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

                if ( isset( $i['method'] ) && $i['method'] == 'local' ) {
                    $a[$k]['label_1'] = 'a';
                    $a[$k]['color_1'] = 'mr-green-2';
                    $a[$k]['item_1'] = 'local';
                }
                    
                if ( isset( $i['method'] ) && $i['method'] == 'lib' ) {
                    $a[$k]['label_1'] = 'b';
                    $a[$k]['color_1'] = 'mr-blue-2';
                    $a[$k]['item_1'] = 'lib';
                }
                    
                if ( isset( $i['method'] ) && $i['method'] == 'manual' ) {
                    $a[$k]['label_1'] = 'p';
                    $a[$k]['color_1'] = 'mr-magenta-2';
                    $a[$k]['item_1'] = 'manual';
                }

                // label_2

                $a[$k]['label_2'] = '';
                $a[$k]['color_2'] = '';
                $a[$k]['item_2'] = 'count-no';
                // $a[$k]['label_2'] = 'o';
                // $a[$k]['color_2'] = 'mr-gray-2';
                
                if ( isset( $i['count'] ) && $i['count'] > 1 ) {
                    $a[$k]['label_2'] = 'm';
                    $a[$k]['color_2'] = 'mr-blue-2';
                    $a[$k]['item_2'] = 'count-yes';
                }

                // label_3

                $a[$k]['label_3'] = '';
                $a[$k]['color_3'] = '';
                $a[$k]['item_3'] = 'name-old-no';
                // $a[$k]['label_3'] = 'o';
                // $a[$k]['color_3'] = 'mr-gray-2';
                
                if ( isset( $i['name_old'] ) && $i['name_old'] != $k ) {
                    $a[$k]['label_3'] = 'e';
                    $a[$k]['color_3'] = 'mr-orange-2';
                    $a[$k]['item_3'] = 'name-old-yes';
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
                
                $out .= '<div class="row select-item select-yes ' . $a[$k]['item_1'] . ' ' . $a[$k]['item_2'] . ' ' . $a[$k]['item_3'] . '">';
                $out .= '<div class="col col-1 p-2">' . $n++ . '</div>';
                $out .= '<div class="col p-2">' . $k . '</div>';

                $out .= '<div class="col col-2 p-2">';
                $out .= mif_mr_opop_core::get_span_label( $a[$k]['label_3'], $a[$k]['color_3'] ); 
                $out .= mif_mr_opop_core::get_span_label( $a[$k]['label_2'], $a[$k]['color_2'] ); 
                $out .= mif_mr_opop_core::get_span_label( $a[$k]['label_1'], $a[$k]['color_1'] ); 
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

    private function get_select_menu( $arr )
    {
        // // p($arr_info);

        // $arr = array(
        //         'curriculum-yes' => false,
        //         'curriculum-no' => false,
        //         'local-no' => false,
        //         'local-yes' => false,
        //         'local-maybe' => false,
        //         'lib-no' => false,
        //         'lib-yes' => false,
        //         'lib-maybe' => false,
        //     );

        // foreach ( $arr_info as $item ) {

        //     $arr[$item['item_1']] = true;
        //     $arr[$item['item_2']] = true;
        //     $arr[$item['item_3']] = true;

        // }

        // // p($arr);

        // $arr2 = array( '1' => 0, '2' => 0, '3' => 0 );

        // foreach ( $arr as $k => $i ) {

        //     switch ( $k ) {
                        
        //         case 'curriculum-yes':
        //         case 'curriculum-no':

        //             // $arr2['1'][$k] = true;
        //             if ( $i ) $arr2['1']++;

        //         break;
                
        //         case 'local-no':
        //         case 'local-yes':
        //         case 'local-maybe':

        //             // $arr2['2'][$k] = true;
        //             if ( $i ) $arr2['2']++;
                
        //         break;
                
        //         case 'lib-no':
        //         case 'lib-yes':
        //         case 'lib-maybe':

        //             // $arr2['3'][$k] = true;
        //             if ( $i ) $arr2['3']++;
                
        //         break;
                        
        //         // default:
        //         // break;
            
        //     }

        // }

        // // p($arr2);
        
        // foreach ( $arr as $k => $i ) {

        //     switch ( $k ) {
                        
        //         case 'curriculum-yes':
        //         case 'curriculum-no':

        //             if ( $arr2['1'] <= 1 ) $arr[$k] = false;

        //         break;
                
        //         case 'local-no':
        //         case 'local-yes':
        //         case 'local-maybe':

        //             if ( $arr2['2'] <= 1 ) $arr[$k] = false;
                
        //         break;
                
        //         case 'lib-no':
        //         case 'lib-yes':
        //         case 'lib-maybe':

        //             if ( $arr2['3'] <= 1 ) $arr[$k] = false;
                
        //         break;
                        
        //         // default:
        //         // break;
            
        //     }

        // }


        $arr2 = array(
                    array( 'none', 'mr-red-2', 'o', '1', $this->description['none'], true ),
                    array( 'local', 'mr-green-2', 'a', '1', $this->description['local'], true ),
                    array( 'lib', 'mr-blue-2', 'b', '1', $this->description['lib'], true ),
                    array( 'manual', 'mr-magenta-2', 'p', '1', $this->description['manual'], true ), 
                    array( 'count-yes', 'mr-blue-2', 'm', '2', $this->description['count-yes'], true ),
                    array( 'count-no', 'mr-gray-2', 'o', '2', $this->description['count-no'], true ),
                    array( 'name-old-yes', 'mr-orange-2', 'e', '3', $this->description['name-old-yes'], true ), 
                    array( 'name-old-no', 'mr-gray-2', 'o', '3', $this->description['name-old-no'], true ),
                    );



        $out = '';
        $out .= mif_mr_opop_core::get_select_menu_show( $arr2 );        

  
        return $out;
    }
    




    protected $description = array();

}

?>