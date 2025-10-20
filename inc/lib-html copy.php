<?php

//
// Создание HTML
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_html  {

    function __construct()
    {

    }



    
    // 
    // Возвращает дисциплины в HTML
    // 

    public function get_html( $courses_arr = array() )
    {
        $html = '';

        $arr = $this->get_table_arr( $courses_arr );


        $html .= '<table border="1">';
        // p($arr);
        
        foreach ( $arr as $item ) {

            $html .= '<' . $item['att']['elem'] . '>';

            foreach ( $item['row'] as $item2 ) {

                $html .= '<' . $item2['att']['elem'] . '>';

                foreach ( (array) $item2['col'] as $item3 ) {
                    
                    $colspan = ( isset( $item3['att']['colspan'] ) ) ? ' colspan="' . $item3['att']['colspan'] . '"' : ''; 
                   
                    $html .= '<' . $item3['att']['elem'] . $colspan . '>';
                    $html .= $item3['text'];
                    $html .= '</' . $item3['att']['elem'] . '>';
                    
                }
                
                $html .= '</' . $item2['att']['elem'] . '>';
                
            }
            
            $html .= '</' . $item['att']['elem'] . '>';

        }        
        
        $html .= '</table>';


        return $html;
    }




    // 
    // Возвращает дисциплины в массиве для HTML-таблицы
    // 

    public function get_table_arr( $courses_arr = array() )
    {
        // if ( ! empty( $matrix_arr ) ) $this->matrix_arr = $matrix_arr;
        
        
        // Определить данные для отображения и режим просмотра
        
        $mode = 'courses';
        $first = current( $courses_arr );
        if ( isset( $first['att'] ) ) $mode = 'modules';
        if ( isset( $first['att']['singular'] ) ) $mode = 'tree';
        
        // Если просто дисциплины, то привести массив к виду модулей или дерева
        
        if ( $mode == 'courses' ) $courses_arr = array( 'courses' => array( 'courses' => $courses_arr ) );
        
        // p( $courses_arr );


        $table_arr = array();
        
        $arr = array();
        $arr2 = array();

        $arr2[] = ( $mode == 'modules' ) ? 
            $this->add_to_col( 'Код', array('elem' => 'th') ) :
            $this->add_to_col( '№', array('elem' => 'th') ) ;

        $arr2[] = ( $mode == 'modules' ) ? 
            $this->add_to_col( 'Модули', array('elem' => 'th') ) :
            $this->add_to_col( 'Дисциплины и практики', array('elem' => 'th') ) ;

        $arr[] = $this->add_to_row( $arr2, array('elem' => 'tr') );
        $table_arr[] = $this->add_to_table( $arr, array('elem' => 'tbody') ); 

        $arr = array();
        $n = 1;

        foreach ( $courses_arr as $key => $item ) {

            // p($item);
            $arr2 = array();
            
            $code = ( isset( $item['att']['code'] ) ) ? $item['att']['code'] : '';

            if ( $mode == 'modules' ) $arr2[] = $this->add_to_col( $code, array('elem' => 'th') );

            $arr2[] = ( $mode == 'modules' ) ? 
                $this->add_to_col( $key, array('elem' => 'th') ) :
                $this->add_to_col( $item['att']['plural'], array('elem' => 'th', 'colspan' => '2') ) ;
                
            $arr[] = $this->add_to_row( $arr2, array('elem' => 'tr') );
            
            foreach ( (array) $item['courses'] as $key2 => $item2 ) {
                // p($key2);
                // p($item2);
                
                $arr2 = array();
                $code = ( isset( $item2['code'] ) ) ? $item2['code'] : '';
                
                $arr2[] = ( $mode == 'modules' ) ? 
                    $this->add_to_col( $code, array('elem' => 'td') ) :
                    $this->add_to_col( $n++, array('elem' => 'td') ) ;
                    
                $arr2[] = $this->add_to_col( $key2, array('elem' => 'td') ) ;

                $arr[] = $this->add_to_row( $arr2, array('elem' => 'tr') );

            }

        }


        $table_arr[] = $this->add_to_table( $arr, array('elem' => 'tbody') ); 

        // p($arr);
        return $table_arr;
        // return '$arr';
    }



    private function add_to_table( $row, $att = array() )
    {
        return array(
                    'att' => $att,
                    'row' => $row
                    );
    }


    private function add_to_row( $col, $att = array() )
    {
        return array(
                    'att' => $att,
                    'col' => $col
                    );
    }
    
    private function add_to_col( $text, $att = array() )
    {
        return array(
                    'att' => $att,
                    'text' => $text
                    );
    }


    // private function add_to_arr( $col, $att_c = array(), $att_r = array() )
    // {
    //     return array(
    //                 'att' => $att_r,
    //                 'row' => array(
    //                             'att' => $att_c,
    //                             'col' => $col
    //                             )
    //                 );
    // }




}

?>