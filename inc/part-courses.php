<?php

//
// Список дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_courses extends mif_mr_table {
    
    function __construct()
    {

        parent::__construct();
        

        add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        add_filter( 'mif-mr-thead-col', array( $this, 'filter_thead_col'), 10 );
        add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );
       

        $this->save( 'courses' );

    }
    

    
    // 
    // Показывает часть 
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-part-courses.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-part-courses.php', false );

        }
    }
    
    
    
    // 
    // Показывает дисциплины
    // 
    
    public function get_courses()
    {
        if ( isset( $_REQUEST['edit'] ) ) return $this->companion_edit( 'courses' );
        
        $arr = $this->get_courses_arr(); 
        // return '@2';
        
        $out = '';
        $out .= '<div class="content-ajax col-12 p-0">';
        
        $out .= $this->get_table_html( $arr );       
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_courses', $out );
    }
    
    



  
    public function filter_tbody_col( $arr, $key, $key2, $courses_arr )
    {
        global $tree;
        
        $a = $tree['content']['courses']['index'];
        // p($a[$key2]);

        // // Ссылка 

        // $text = '<a href="#"><i class="fa-regular fa-file-lines"></i></a>';
        // $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );

        // %

        $text = '<span class="mr-green rounded pl-2 pr-2 p-1">100</span>';
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

        $text = '<a href="#" class="mr-gray rounded p-1" title="Шаблон дисциплины"><i class="fa-regular fa-file-excel"></i></a>';
        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );
        
        // Шаблон плюс
        
        $text = '<a href="#" class="mr-gray rounded p-1" title="Шаблон (компетенции плюс)"><i class="fa-regular fa-file-excel"></i></a>';
        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '', 'title' => '') );
        
        // Ссылка 

        $text = '';
        if ( isset( $a[$key2]['course_id'] ) ) {
            $text = mif_mr_opop_core::get_a_id( $a[$key2]['course_id'], 
                                                mif_mr_opop_core::get_opop_url() . 'lib-courses/' . $a[$key2]['course_id'], 
                                                'Ссылка на библиотеку', 'p-1 pl-2 pr-2' );
        } else {

            $text = mif_mr_opop_core::get_a_id( '<i class="fa-regular fa-file-lines"></i><i class="fa-solid fa-arrow-right"></i>', 
                                                mif_mr_opop_core::get_opop_url() . 'lib-courses/', 
                                                'Создать и перейти', 'p-1' );

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
        $arr[] = $this->add_to_col( '', array('elem' => 'th' ) );
        return $arr;
    }


    public function filter_tbody_colspan( $n )
    {
        return $n + 7;
    }


}

?>