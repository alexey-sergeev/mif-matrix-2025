<?php

//
// Список список дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_courses_list extends mif_mr_table {
    
    function __construct()
    {

        parent::__construct();
        

        add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        add_filter( 'mif-mr-thead-col', array( $this, 'filter_thead_col'), 10 );
        add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );
       

        $this->save( 'courses' );

    }
    

    
    // // 
    // // Показывает часть 
    // // 

    // public function the_show()
    // {

    //     global $wp_query;


    //     if ( isset( $wp_query->query_vars['id'] ) ) {

    //         if ( $template = locate_template( 'mr-course.php' ) ) {
               
    //             load_template( $template, false );
    
    //         } else {
    
    //             load_template( dirname( __FILE__ ) . '/../templates/mr-course.php', false );
    
    //         }

    //     } else {

    //         if ( $template = locate_template( 'mr-part-courses.php' ) ) {
               
    //             load_template( $template, false );
    
    //         } else {
    
    //             load_template( dirname( __FILE__ ) . '/../templates/mr-part-courses.php', false );
    
    //         }
        
    //     }

    // }
    
    
    
    // 
    // Показывает список дисциплины
    // 
    
    public function get_list_courses()
    {
        if ( isset( $_REQUEST['edit'] ) ) return $this->companion_edit( 'courses' );
        
        $arr = $this->get_courses_arr(); 
        // return '@2';
        
        $out = '';
        $out .= '<div class="content-ajax col-12 p-0">';
        
        $out .= $this->get_table_html( $arr );       
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_list_courses', $out );
    }
    
    



  
    public function filter_tbody_col( $arr, $key, $key2, $courses_arr )
    {
        global $tree;
        
        $a = $tree['content']['courses']['complete'];
        // p($a[$key2]);
        // p($courses_arr[$key]['courses'][$key2]['unit']);

        $making = ( in_array( $courses_arr[$key]['courses'][$key2]['unit'], array( 'ЭК', 'ЗЧ', 'ЗЧО', 'К', 'КР', 'КРП', 'ГИА' ) )  ) ? false : true;

        $course_id = ( isset( $a[$key2]['course_id'] ) ) ? $a[$key2]['course_id'] : 0;
        $link_lib = mif_mr_opop_core::get_opop_url() . 'lib-courses/' . $course_id;
        $link_clean = ( isset( $a[$key2]['course_id'] ) ) ? mif_mr_opop_core::get_opop_url() . 'courses/' . $a[$key2]['course_id'] : '';
        
        $percent = ( isset( $tree['content']['courses']['clean'][$course_id]['percent'] ) ) ? $tree['content']['courses']['clean'][$course_id]['percent'] : 0;
        $percent_color = ( $percent == 100 ) ? 'mr-green' : 'mr-red';
        

        // p($making);




        // // Ссылка 

        // $text = '<a href="#"><i class="fa-regular fa-file-lines"></i></a>';
        // $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );

        // %

        $text = ( $making ) ? '<span class="' . $percent_color . ' rounded pl-2 pr-2 p-1">' .   $percent   . '</span>' : '';
        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );

        // НС

        $text = '';
        if ( isset( $a[$key2]['category'] ) && $a[$key2]['category'] == 'local') 
            $text = mif_mr_opop_core::get_span_label( 'a', 'mr-green-2', 'Локальная дисциплина', 'p-1 pr-2 pl-2' );    
        if ( isset( $a[$key2]['category'] ) && $a[$key2]['category'] == 'lib') 
            $text = mif_mr_opop_core::get_span_label( 'b', 'mr-blue-2', 'Дисциплина наследуется', 'p-1 pr-2 pl-2' );    
        // $text = '<span class="rounded mr-green p-1" title="Дисциплина наследуется"><i class="fa-solid fa-link fa-xs"></i></span>';
        if ( isset( $a[$key2]['category'] ) && $a[$key2]['category'] == 'missing') 
            $text = mif_mr_opop_core::get_span_label( 'a', 'mr-orange-2', 'Есть родительская дисциплина, но наследование не применяется', 'p-1 pr-2 pl-2' );    
            // $text = '<span class="rounded mr-yellow p-1" title="Есть родительская дисциплина, но наследование не применяется"><i class="fa-solid fa-link-slash fa-xs"></i></span>';

        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );    

        // Компетенции

        $text = '';
        $c = new cmp();
        if ( isset( $tree['content']['matrix']['data'][$key2] ) ) $text = $c->get_cmp( $tree['content']['matrix']['data'][$key2] );
        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );

        // Каф.

        $text = ( isset( $courses_arr[$key]['courses'][$key2]['kaf'] )) ?
            implode( ', ', (array) $courses_arr[$key]['courses'][$key2]['kaf'] ) : '';
        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );    
        
        // Шаблон

        $name_new_course = ( isset( $a[$key2]['course_id'] ) ) ? '' : '&name=' . urlencode( $key2 );

        $text = ( $making ) ? '<a href="' . $link_lib . '?download=course-x-tpl' . $name_new_course . '" class="mr-gray rounded p-1" title="Шаблон дисциплины"><i class="fa-regular fa-file-excel"></i></a>' : '';
        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );
        
        // Ссылка (clean) 
        
        // $text = ( isset( $a[$key2]['course_id'] ) ) ? '<a href="' . mif_mr_opop_core::get_opop_url() . 'courses/' . $a[$key2]['course_id'] . '" class="mr-gray rounded p-1" title="Смотреть дисциплину"><i class="fa-solid fa-caret-right"></i></a>' : '';
        $text = ( isset( $a[$key2]['course_id'] ) ) ? '<a href="' . $link_clean . '" class="mr-gray rounded p-1" title="Смотреть дисциплину"><i class="fa-solid fa-caret-right"></i></a>' : '';
        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );
        
        // Ссылка на библиотеку 

        $text = '';

        if ( isset( $a[$key2]['course_id'] ) ) {
            $text = ( $making ) ? mif_mr_opop_core::get_a_id( $a[$key2]['course_id'], 
                                                // mif_mr_opop_core::get_opop_url() . 'lib-courses/' . $a[$key2]['course_id'] . '?back=courses', 
                                                $link_lib . '?back=courses', 
                                                'Ссылка на библиотеку', 'p-1 pl-2 pr-2' ) : '';
        } else {

            $text = ( $making ) ? mif_mr_opop_core::get_a_id( '<i class="fa-regular fa-file-lines"></i><i class="fa-solid fa-arrow-right"></i>', 
                                                // mif_mr_opop_core::get_opop_url() . 'lib-courses/0?back=courses&title=' . urlencode( $key2 ), 
                                                $link_lib . '?back=courses&title=' . urlencode( $key2 ), 
                                                'Создать и перейти', 'p-1' ) : '';

        }

        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );


        return $arr;
    }
    
    
    public function filter_thead_col( $arr )
    {
        // $arr[] = $this->add_to_col( '', array('elem' => 'th' ) );
        $arr[] = $this->add_to_col( '%', array('elem' => 'th' ) );
        $arr[] = $this->add_to_col( 'НС', array('elem' => 'th' ) );
        $arr[] = $this->add_to_col( 'Компетенции', array('elem' => 'th' ) );
        $arr[] = $this->add_to_col( 'Каф.', array('elem' => 'th' ) );
        $arr[] = $this->add_to_col( '', array('elem' => 'th' ) );
        $arr[] = $this->add_to_col( '', array('elem' => 'th' ) );
        $arr[] = $this->add_to_col( 'Raw', array('elem' => 'th' ) );
        return $arr;
    }


    public function filter_tbody_colspan( $n )
    {
        return $n + 7;
    }


}

?>