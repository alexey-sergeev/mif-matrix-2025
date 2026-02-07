<?php

//
// Учебный план
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_tools_courses extends mif_mr_tools_core {
    
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

        $out = '';

        // p($_FILES);
        // p($_REQUEST);
        
        // Разбор формы
        
        $m = new mif_mr_upload();
        // $res = $m->save( array( 'ext' => array( 'png' ) ) ); 
        $res = $m->save( array( 'ext' => array( 'xlsx' ) ) ); 
        
        foreach ( (array) $res as $i ) $out .= mif_mr_functions::get_callout( 
                $i['name'] . ' — <span class="fw-semibold">' . $i['messages'] . '</span>', 
                $i['status'] ); 
            
        // Показать форму

        $out .= $m->form_upload( array( 
                            'text' => 'Загрузите файл шаблона учебной дисциплины в формате Excel', 
                            // 'title_placeholder' => 'Название плана', 
                            'url' => 'tools-courses',
                            'multiple' => true 
                        ) );
        

        // Показать список файлов плана
        
        $out .= $this->show_list_file_courses();
        
        // $out .= $this->get_meta();

        return apply_filters( 'mif_mr_get_tools_courses', $out );

    }
    

    
    
    // 
    // Вывести  
    // 

    public function show_list_file_courses()
    {
        $out = '';

        // $arr = $this->get_file_curriculum();
        // $arr = $this->get_file( array( 'unset' => array( 'xlsx' ) ) );
        $arr = $this->get_file();

        // p($arr);

        $out .= '<div class="container mt-5">';
        
        $out .= '<div class="row">';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold">№</div>';
        $out .= '<div class="col p-2 pt-4 pb-4 fw-semibold">Название дисциплины</div>';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold">Файл</div>';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold"></div>';
        $out .= '</div>';
        
        $n = 0;
        $out .= '<div class="striped">';
        
        foreach ( $arr as $item ) {
            
            $out .= '<div class="row">';
            $out .= '<div class="col col-1 p-2">' . ++$n . '</div>';
            $out .= '<div class="col p-2"><a href="' .  mif_mr_opop_core::get_opop_url() . 'tools-courses/' . $item->ID . '">' . $item->post_title . '</a></div>';
            $out .= '<div class="col col-1 p-2 text-center"><a href="' . $item->guid . '"><i class="fa-regular fa-file-code fa-lg"></i></a></div>';
            $out .= '<div class="col col-1 p-2 text-center"><a href="#" class="remove" data-attid="' . $item->ID . '"><i class="fa-regular fa-trash-can fa-lg"></i></a></div>';
            $out .= '</div>';
            
        }
            
        $out .= '</div>';

            $style = ( $n === 0 ) ? '' : ' style="display: none;"'; 
            $out .= '<div class="row no-plans"' . $style . '>';
            $out .= '<div class="col p-4 text-center bg-light border rounded fw-semibold">Нету дисциплин</div>';
            $out .= '</div>';

        $out .= '</div>';

        return $out;
    }





        
    
    // //
    // // Анализ
    // // 
    
    // public function analysis( $att = array() )
    // {
    //     // p($_REQUEST);
    //     // p($att);
    //     $out = '';

    //     $m = new mif_mr_part_companion();
    //     $data[0] = $this->get_date_from_plx( $att['key'], $att['att_id'] );
    //     $data[1] = $m->get_companion_content( $att['key'], $att['opop_id'] );
    
    //     $data = $this->analysis_process( $data, $att['method'] );

    //     $one = '<div class="p-1 pt-3 pb-3 bg-light">' . $data[0] . '</div>';
    //     $two = '<div class="p-1 pt-3 pb-3 bg-light">' . $data[1] . '</div>';
        
    //     $out .= '<div class="container mt-5 fullsize-fix">';

    //     $out .= '<div class="row">';
    //     $out .= '<div class="col d-none d-sm-block mt-5 pr-0 text-end">';
    //     $out .= '<a href="#" class="text-secondary" id="fullsize">';
    //     $out .= '<i class="fa-solid fa-expand fa-2x"></i><i class="d-none fa-solid fa-compress fa-2x"></i>';
    //     $out .= '</a>';
    //     $out .= '</div>';
    //     $out .= '</div>';

    //     $out .= '<div class="row">';

    //     $out .= '<div class="col col-6 p-0 pr-2 pt-4 pb-4"><div class="text-center fw-semibold">из файла</div>' . $one. '</div>';
    //     $out .= '<div class="col col-6 p-0 pl-2 pt-4 pb-4"><div class="text-center fw-semibold">из Matrix</div>' . $two. '</div>';

    //     $out .= '</div>';
   
    //     $out .= '<div class="row">';

    //     $out .= '<div class="col p-3 bg-light">';
    //     $out .= '<a href="#" class="mr-3 save" data-yes="yes">Сохранить</a>';
    //     $out .= '<a href="#" class="mr-3 cancel-analysis">Отменить</a>';
    //     $out .= '</div>';

    //     $out .= '<div class="col p-3 bg-light text-end">';
    //     $out .= '<a href="#" class="mr-3 help"><i class="fa-solid fa-circle-question fa-xl"></i></a>';
    //     $out .= '</div>';

    //     $out .= '</div>';
  
    //     $out .= '<div class="row">';

    //     $out .= '<div class="col bg-light help-box" style="display: none;">';
    //     $out .= mif_mr_functions::get_callout( 
    //                 'белые — эти строки доступны одинаково как в первом, так и во втором списке<br />
    //                  желтые — есть дисциплина, но параметры другие<br />
    //                  красные — это строки, которые написаны один раз, отсутствуют в другом списке.
    //                 ', 'info' );;
    //     $out .= '</div>';

    //     $out .= '</div>';
  
    //     $out .= '</div>';

    //     return $out;
    // }




    // private function analysis_process( $data = array(), $method = '' )
    // {

    //     foreach ( array( 0, 1 ) as $k ) $arr[$k] = array_map( 'strim', explode( "\n", $data[$k] ) );     
        
    //     foreach ( array( 0, 1 ) as $k ) 
    //     foreach ( $arr[$k] as $item ) {
        
    //         if ( empty( $method ) ) {
                
    //             $p = new parser();
    //             $d = $p->parse_name( $item );
                
    //         } elseif ( $method == 'dots' ) {
                
    //             $p = new attributes( $item );
    //             $a = array_keys( $p->get_arr() );
    //             $d['name'] = $a[0];

    //         }

    //         // p('$d');
    //         // p($d);

    //         $a = trim( preg_replace( '/' . $d['name'] . '/', '', $item ) ); 
    //         $arr2[$k][] = array( $d['name'], $a );       
            
    //         $arr3[$k][] = 2;

    //     }

    //     foreach ( $arr2[0] as $k => $i )
    //     foreach ( $arr2[1] as $k2 => $i2 ) {
        
    //         if ( $i[0] === $i2[0] ) {

    //             if ( $arr3[0][$k] != 0 ) $arr3[0][$k] = 1;
    //             if ( $arr3[1][$k2] != 0 ) $arr3[1][$k2] = 1;

    //         }
            
    //         if ( $i[0] === $i2[0] && $i[1] === $i2[1] ) {
                
    //             $arr3[0][$k] = 0;
    //             $arr3[1][$k2] = 0;
            
    //         }

    //     }
        
    //     $f = true;
    //     foreach ( array( 0, 1 ) as $k ) if ( empty( $data[$k]) ) $f = false;
        
    //     foreach ( array( 0, 1 ) as $k ) foreach ( $arr[$k] as $key => $item ) {
            
    //         $c = '';
            
    //         if ( $f && $arr3[$k][$key] === 1 ) $c = ' mr-yellow'; 
    //         if ( $f && $arr3[$k][$key] === 2 ) $c = ' mr-red'; 
            
    //         $arr[$k][$key] = '<div class="pl-3 pr-3 m-1' . $c . '">' . $item . '</div>';
        
    //     }

    //     foreach ( array( 0, 1 ) as $k ) $data[$k] = implode( "\n", $arr[$k]);

    //     return $data;
    // }




    // private function is_empty( $type, $opop_id )
    // {
    //     $m = new mif_mr_part_companion();
    //     $res = $m->get_companion_id( $type, $opop_id );

    //     return ( $res ) ? true : false;
    // }




    // private function get_date_from_plx( $type, $att_id )
    // {
    //     $plx = new plx( get_attached_file( $att_id ) );

    //     switch ( $type ) {  
    //         case 'courses': $data = $plx->get_courses(); break;  
    //         case 'curriculum': $data = $plx->get_curriculum(); break;  
    //         case 'matrix': $data = $plx->get_matrix(); break;  
    //         case 'lib-competencies': $data = $plx->get_cmp(); break;  
    //         case 'lib-references-kaf': $data = $plx->get_kaf(); break;  
    //         case 'lib-references-staff': $data = $plx->get_att( 'staff' ); break;  
    //         case 'attributes': $data = $plx->get_att(); break;  
    //         case 'get_att_arr': $data = $plx->get_att_arr(); break;  
    //         default: $data = ''; break;  
    //     }  
        
    //     return $data;
    // }


    

    // function remove( $attid )
    // {
    //     // !!!!!!!

    //     $res = ( wp_delete_attachment( $attid, true ) === false ) ? false : true;
    //     return $res;
    // }




    // private static function get_meta()
    // {
    //     $out = '';
    //     $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';  
    //     $out .= '<input type="hidden" name="opop" value="' . mif_mr_opop_core::get_opop_id() . '" />';  
    //     $out .= '<input type="hidden" name="opop_title" value="' . mif_mr_opop_core::get_opop_title() . '" />';  
    //     return $out;
    // }

}

?>