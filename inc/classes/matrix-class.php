<?php

//
// Класс для работы с матрицей компетенеций
// А. Н. Сергеев, Волгоград
// Июль 2019
// 

include_once dirname( __FILE__ ) . '/parser-class.php';
include_once dirname( __FILE__ ) . '/cmp-class.php';

class matrix {

    //
    // Инициализация 
    // На входе - массив дисциплин ОПОП или текстовое описание дисциплин и компетенеций
    // Параметр $cmp - перечень в принципе допустимых компетенций
    //

    function __construct( $data = NULL, $cmp = '' )
    {
        // Запомнить перечень допустимых компетенеций

        $this->acceptable_cmp = $cmp;

        // Если данные - в виде текстового описания, то оформить в виде массива

        if ( empty( $data ) ) return;

        if ( is_string( $data ) ) {
    
            $p = new parser();
            $data = $p->get_arr( $data, array( 'section' => 'competence', 'nomarker' => true, 'att_name' => true ) );
    
        }
    
        // Построить массив матрицы компетенций

        $c = new cmp( $this->acceptable_cmp );
    
        foreach ( $data as $item ) {
    
            if ( empty( $item['competence']['att'] ) ) continue;
            $this->matrix_arr[$item['competence']['name']] = $c->get_cmp( $item['competence']['att'][0], 'arr' );
    
        }

    }


    // 
    // Функция возвращает массив матрицы компетенций
    // 

    function get_arr()
    {
        return $this->matrix_arr;
    }


    // 
    // Функция возвращает все возможные компетенции
    // 

    function get_cmp()
    {
        $arr = array();
        $arr[] = $this->acceptable_cmp;
        foreach ( $this->matrix_arr as $item ) $arr = array_merge( $arr, $item );

        $c = new cmp();
        $arr2 = $c->get_cmp( $arr, 'arr' );

        return $arr2;
    }


    // 
    // Функция возвращает статистику компетенций
    // 

    function get_cmp_stat()
    {
        $stat = array();
        $cmp = $this->get_cmp();

        foreach ( $cmp as $item ) $stat[$item] = 0;

        foreach ( $this->matrix_arr as $line ) 
        foreach ( $line as $item ) $stat[$item]++;
        
        return $stat;
    }


    // 
    // Функция возвращает матрицу компетенций в виде HTML-таблицы
    // 


    function get_html( $matrix_arr = NULL, $arr = NULL )
    {
        if ( ! empty( $matrix_arr ) ) $this->matrix_arr = $matrix_arr;

        $html = '';
        
        $h = new html();
        $html .= $h->table_header();
        
        // Определить данные для отображения и режим просмотра
        
        $mode = 'courses';
        $first = current( $arr );
        if ( isset( $first['att'] ) ) $mode = 'modules';
        if ( isset( $first['att']['singular'] ) ) $mode = 'tree';

        // Если просто дисциплины, то привести массив к виду модулей или дерева

        if ( $mode == 'courses' ) $arr = array( 'courses' => array( 'courses' => $arr ) );

        // Строим таблицу

        $n = 1;
        $m = 1;

        $cmp = $this->get_cmp();
        $cmp_count = count( $cmp ) + 1;

        $code = ( $mode == 'modules' ) ? 'Код' : '№';
        $title = ( $mode == 'modules' ) ? 'Модули' : 'Дисциплины и практики';

        $html .= $this->get_html_header();

        foreach ( $arr as $key => $module ) {

            // Вывести название модуля или блока дисциплин

            if ( $mode == 'modules' ) {
              
                $code = ( isset( $module['att']['code'] ) ) ? $module['att']['code'] : '';
                $title = ( $key == $this->default_name ) ? 'Дисциплины и практики вне модулей' : $key;
                $html .= $h->course_name_tr( $title, $code, $cmp_count );
                
            } 
            
            if ( $mode == 'tree' ) {
                
                $title = ( isset( $module['att']['plural'] ) ) ? $module['att']['plural'] : $key;
                $html .= $h->part_tr( $title, $cmp_count + 1 );

            }

            // Вывести дисциплины

            if ( isset( $module['courses'] ) ) {

                foreach ( $module['courses'] as $course => $data ) {
                    
                    $row = '';
                    $row .= $course . "</td>\n";
                    
                    foreach ( $cmp as $item ) {
                        
                        $arr2 = ( isset( $this->matrix_arr[$course] ) ) ? $this->matrix_arr[$course] : array();
                        $marker = ( in_array( $item, $arr2 ) ) ? '1': '';
                        // $marker_class = ( in_array( $item, $arr2 ) ) ? 'yes': 'no';
                        $marker_class = ( ! empty( $marker ) ) ? ' on': '';
                        $alt = ( ! empty( $marker ) ) ? " title=\"$item\"" : '';
                        $row .= "<td class=\"cmp$marker_class\"$alt>$marker</td>\n";
                        
                    }
                    
                    $code = ( $mode == 'modules' && isset( $data['code'] ) ) ? $data['code'] : $m;
                    $html .= $h->course_data_tr( $row, '', '', $code );
                    $m++;
                    
                }
                
            }
            
            $n++;

        }
        
        $html .= $this->get_html_stat();   
        $html .= $h->table_footer();
        
        return $html;
    }




    
    // function get_html3( $arr = NULL, $full = true )
    // {
    //     if ( ! empty( $arr ) ) $this->matrix_arr = $arr;
    //     // p($arr);
    //     $html = '';
        
    //     // Если полный формат - добавляем заголовок таблицы

    //     if ( $full ) {

    //         $html .= "<table border='1'>\n";
    //         $html .= $this->get_html_header();
            
    //     }
        
    //     $n = 1;
    //     $cmp = $this->get_cmp();

    //     foreach ( $this->matrix_arr as $name => $row ) {
            
    //         $html .= "<tr>\n";
    //         $html .= "<td>$n</td>\n";
    //         $html .= "<td>$name</td>\n";
            
    //         foreach ( $cmp as $item ) {
                
    //             $marker = ( in_array( $item, $row ) ) ? '1': '';
    //             $marker_class = ( in_array( $item, $row ) ) ? 'yes': 'no';
    //             $html .= "<td class=\"$marker_class\">$marker</td>\n";
                
    //         }
            
    //         $html .= "</tr>\n";
    //         $n++;
    //     }
        
    //     // Если полный формат - добавляем низ таблицы

    //     if ( $full ) {
            
    //         $html .= $this->get_html_stat();            
    //         $html .= "</table>";
            
    //     }
        
    //     return $html;
    // }
    
    
    
    //  
    // Возвращает строку со статистикой для таблицы HTML
    // 
    
    private function get_html_stat()
    {
        $html = '';

        $stat = $this->get_cmp_stat();

        $html .= "<tr>\n";
        $html .= "<th></th>\n";
        $html .= "<th>ИТОГО:</th>\n";

        foreach ( $stat as $item ) $html .= "<th>$item</th>\n";
        
        return $html;
    }
    

    //  
    // Возвращает заголовок с компетенциями для таблицы HTML
    // 
    
    private function get_html_header()
    {
        $html = '';
        
        $cmp = $this->get_cmp();
        $index = array();

        foreach ( $cmp as $item ) {

            $data = explode( '-', $item );
            $index[$data[0]][] = $data[1];

        }

        $html .= "<thead>\n";
        $html .= "<tr>\n";
        $html .= "<th rowspan=\"2\"></th>\n";
        $html .= "<th rowspan=\"2\"></th>\n";
        
        $row2 = '';
        
        foreach ( $index as $key => $numerics ) {
            
            // p($key);
            $c = count( $numerics );
            $html .= "<th colspan=\"$c\">$key</th>\n";
            foreach ( $numerics as $item ) $row2 .= "<th class=\"selectable\">$item</th>\n";
            // foreach ( $numerics as $item ) $row2 .= "<th class=\"selectable\" data-cmp=\"ttt\">$item</th>\n";
            
        }
        
        $html .= "</tr>\n";
        
        $html .= "<tr>\n";
        $html .= $row2;
        $html .= "</tr>\n";
        $html .= "</thead>\n";
        
        return $html;
    }



    private $acceptable_cmp = '';
    private $matrix_arr = array();
    private $default_name = '__default__';

}
    


if ( ! function_exists( "p") ) {
    
    function p( $t )
    {
        print_r( "<pre>" );
        print_r( $t );
        print_r( "</pre>" );
    }

}



?>