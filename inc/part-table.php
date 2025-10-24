<?php

//
// Создание HTML
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_table extends mif_mr_companion {

    function __construct()
    {
        parent::__construct();

    }


    
    // 
    // Возвращает table
    // 

    public function get_table_html( $courses_arr = array() )
    {
        $html = '';

        $html .= '<table border="1">';

        $arr = $this->get_thead_arr($courses_arr);
        
        $html .= '<thead>';
        $html .= $this->get_html_part($arr);
        $html .= '</thead>';
        

        $arr = $this->get_tbody_arr($courses_arr);
        
        $html .= '<tbody>';
        $html .= $this->get_html_part($arr);
        $html .= '</tbody>';

        $html .= '</table>';

        return $html;
    }





    public function get_html_part( $arr = array() )
    {
        $html = '';

        foreach ( $arr as $item ) {

            $class = ( isset( $item['att']['class'] ) ) ? ' class="' . $item['att']['class'] . '"' : ''; 
            $html .= '<' . $item['att']['elem'] . $class . '>';

            foreach ( (array) $item['col'] as $item2 ) {
                
                $colspan = ( isset( $item2['att']['colspan'] ) ) ? ' colspan="' . $item2['att']['colspan'] . '"' : ''; 
                $rowspan = ( isset( $item2['att']['rowspan'] ) ) ? ' rowspan="' . $item2['att']['rowspan'] . '"' : ''; 
                $class = ( isset( $item2['att']['class'] ) ) ? ' class="' . $item2['att']['class'] . '"' : ''; 
                $title = ( isset( $item2['att']['title'] ) ) ? ' title="' . $item2['att']['title'] . '"' : ''; 
                $data_cmp = ( isset( $item2['att']['data-cmp'] ) ) ? ' data-cmp="' . $item2['att']['data-cmp'] . '"' : ''; 

                $html .= '<' . $item2['att']['elem'] . $colspan . $rowspan . $class . $title . $data_cmp . '>';
                $html .= $item2['text'];

                $html .= '</' . $item2['att']['elem'] . '>';
                
            }
            
            $html .= '</' . $item['att']['elem'] . '>';
            
        }
        
        return $html;
    }




    public function get_thead_arr( $courses_arr = array() )
    {
        $mode = $this->get_mode($courses_arr);
        
        $arr = array();
        $arr2 = array();
        
        $arr2[] = ( $mode == 'modules' ) ? 
                $this->add_to_col( 'Код', array( 'elem' => 'th' ) ) :
                $this->add_to_col( '№', array( 'elem' => 'th' ) ) ;
        
        $arr2[] = ( $mode == 'modules' ) ? 
                $this->add_to_col( 'Модули', array( 'elem' => 'th', 'colspan' => apply_filters( 'mif-mr-thead-colspan', 1 ) ) ) :
                $this->add_to_col( 'Дисциплины и практики', array( 'elem' => 'th', 'colspan' => apply_filters( 'mif-mr-thead-colspan', 1 ) ) ) ;

        $arr2 = apply_filters( 'mif-mr-thead-col', $arr2, $courses_arr );        

        $arr[] = $this->add_to_row( $arr2, array('elem' => 'tr') );

        $arr = apply_filters( 'mif-mr-thead-row', $arr, $courses_arr ); 

        return $arr;
    }


    // 
    // Возвращает дисциплины в массиве для HTML-таблицы
    // 

    public function get_tbody_arr( $courses_arr = array() )
    {
        
        $mode = $this->get_mode($courses_arr);

        $arr = array();
        $n = 1;

        foreach ( $courses_arr as $key => $item ) {

            $arr2 = array();
            
            $code = ( isset( $item['att']['code'] ) ) ? $item['att']['code'] : '';

            if ( $mode == 'modules' ) $arr2[] = $this->add_to_col( $code, array('elem' => 'th') );

            $text = ( $key == '__default__' ) ? 'Дисциплины и практики вне модулей' : $key;
            $arr2[] = ( $mode == 'modules' ) ? 
                $this->add_to_col( $text, array( 'elem' => 'th', 'colspan' => apply_filters( 'mif-mr-tbody-colspan', 1 ) ) ) :
                $this->add_to_col( $item['att']['plural'], array( 'elem' => 'th', 'colspan' => apply_filters( 'mif-mr-tbody-colspan', 2 ) ) ) ;
                
            $arr[] = $this->add_to_row( $arr2, array('elem' => 'tr') );
            
            foreach ( (array) $item['courses'] as $key2 => $item2 ) {
                
                $arr2 = array();
                $code = ( isset( $item2['code'] ) ) ? $item2['code'] : '';
                
                $arr2[] = ( $mode == 'modules' ) ? 
                    $this->add_to_col( $code, array('elem' => 'td') ) :
                    $this->add_to_col( $n++, array('elem' => 'td') ) ;
                    
                $arr2[] = $this->add_to_col( $key2, array('elem' => 'td') ) ;

                $arr2 = apply_filters( 'mif-mr-tbody-col', $arr2, $key, $key2, $courses_arr ); 

                $arr[] = $this->add_to_row( $arr2, array('elem' => 'tr', 'class' => apply_filters( 'mif-mr-tbody-class-tr', 'can-select', $key2 ) ) );

            }

            $arr = apply_filters( 'mif-mr-tbody-section-row', $arr, $key, $courses_arr ); 
            
        }
        
        $arr = apply_filters( 'mif-mr-tbody-end-row', $arr, $courses_arr ); 

        return $arr;
    }



    public static function add_to_row( $col, $att = array() )
    {
        return array(
                    'att' => $att,
                    'col' => $col
                    );
    }
    
    public static function add_to_col( $text, $att = array() )
    {
        return array(
                    'att' => $att,
                    'text' => $text
                    );
    }


    
    private function get_mode( $courses_arr )
    {
        // Определить данные для отображения и режим просмотра
        
        $mode = 'courses';
        $first = current( $courses_arr );
        if ( isset( $first['att'] ) ) $mode = 'modules';
        if ( isset( $first['att']['singular'] ) ) $mode = 'tree';
        
        // Если просто дисциплины, то привести массив к виду модулей или дерева
        
        if ( $mode == 'courses' ) $courses_arr = array( 'courses' => array( 'courses' => $courses_arr ) );
    
        return $mode;
    }


    // 
    // Получить matrix
    // 

    public function get_matrix_arr()
    {
        global $tree;
        $arr = $tree['content']['matrix']['data'];
        return $arr;
    }

    // 
    // Получить curriculum
    // 

    public function get_curriculum_arr()
    {
        global $tree;
        $arr = $tree['content']['curriculum']['data'];
        return $arr;
    }


    // 
    // Получить courses
    // 

    public function get_courses_arr()
    {
        global $tree;
        $arr = $tree['content']['courses']['data'];
        if ( isset( $_REQUEST['key'] ) && $_REQUEST['key'] == 'courses' ) $arr = $this->get_courses_tree( $arr );
        return $arr;
    }



}

?>