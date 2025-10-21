<?php

//
// Список дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_courses extends mif_mr_table {
    
    // private $explanation = array();


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

        // global $tree;
        // // $m = new modules( $this->get_companion_content( 'courses' ) );
        // // $html = $m->get_html();
        // // $m = new modules();
        
        // $arr = $tree['content']['courses']['data'];
        // // if ( isset( $_REQUEST['courses'] ) ) $arr = $m->get_courses_tree( $arr );
        // if ( isset( $_REQUEST['key'] ) && $_REQUEST['key'] == 'courses' ) $arr = $this->get_courses_tree( $arr );

        $arr = $this->get_courses_arr(); 

        // $html = $m->get_html( $arr );

        // p($m->get_arr() );


        $out = '';
        $out .= '<div class="content-ajax col-12 p-0">';
        
        // $out .= $this->get_companion_content( 'courses' );
        // $out .= '$html';
        // $out .= $html;
        $out .= $this->get_table_html( $arr );       
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_part_get_courses', $out );
    }
    
    



  
    public function filter_tbody_col( $arr, $key, $key2, $courses_arr )
    {
        // p($key);
        // p($key2);
        global $tree;
        // p( $matrix_arr = $tree['content']['matrix']['data'][$key2]);        
        // $text = implode( ', ', $tree['content']['matrix']['data'][$key2] );   
        
        $text = '';

        $c = new cmp();
        if ( isset( $tree['content']['matrix']['data'][$key2] ) ) $text = $c->get_cmp( $tree['content']['matrix']['data'][$key2] );


        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '$class', 'title' => '$title') );


        $text = ( isset( $courses_arr[$key]['courses'][$key2]['kaf'] )) ?
            implode( ', ', (array) $courses_arr[$key]['courses'][$key2]['kaf'] ) :
            '';
        $arr[] = $this->add_to_col( $text, array('elem' => 'td', 'class' => '$class', 'title' => '$title') );

        return $arr;
    }
    
    
    public function filter_thead_col( $arr )
    {
        $arr[] = $this->add_to_col( 'Компетенции', array('elem' => 'th' ) );
        $arr[] = $this->add_to_col( 'Каф.', array('elem' => 'th' ) );
        return $arr;
    }


    public function filter_tbody_colspan( $n )
    {
        return $n + 2;
    }



}

?>