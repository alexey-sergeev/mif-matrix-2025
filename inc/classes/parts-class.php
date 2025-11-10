<?php

//
// Класс для работы с описанием планиуремых достижений по разделам дисциплин
// А. Н. Сергеев, Волгоград
// 11.2025
// 

include_once dirname( __FILE__ ) . '/functions.php';
include_once dirname( __FILE__ ) . '/parser-class.php';
// include_once dirname( __FILE__ ) . '/cmp-class.php';
// include_once dirname( __FILE__ ) . '/matrix-class.php';
include_once dirname( __FILE__ ) . '/html-class.php';

class parts {

    //
    // Инициализация 
    // На входе - массив или текстовое описание всех дисциплин и информации по разделам - компетенции, знать, уметь, владеть
    // $matrix, $cmp - информация для построения матрицы компетенций 
    // 

    function __construct( $data )
    {

        
        if ( is_string( $data ) ) {
            
            $p = new parser();
            $data = $p->get_arr( $data, array( 'section' => 'parts', 'att_name' => false ) );
            
        }
        
        // p($data);
        foreach ( $data as $item ) {
            
            $n = 0;

            foreach ( (array) $item['parts']['parts'] as $part ) {

                // $this->parts_arr[$item['parts']['name']]['parts'][$part['name']]['outcomes'] = $this->get_outcomes( $part['data'] );
                // $this->parts_arr[$item['parts']['name']]['parts'][$part['name']]['cmp'] = $part['att'][0];
                $this->parts_arr['parts'][$n]['name'] = $part['name'];
                $this->parts_arr['parts'][$n]['sub_id'] = $n;
                $this->parts_arr['parts'][$n]['cmp'] = $part['att'][0];
                $this->parts_arr['parts'][$n]['outcomes'] = $this->get_outcomes( $part['data'] );

                $n++;

            }

        }


        // p($this->parts_arr);
    }


    // 
    // Оформить массив "знать", "уметь", "владеть"
    // 

    private function get_outcomes( $data )
    {
        $arr = array();

        $data = implode( "\n", $data );
        $data = explode( "\n-", $data );

        foreach ( $data as $key => $item ) {

            $txt = trim( strim( $item ), ' -.,;' );

            if ( $key % 3 === 0 && $txt != '' ) $arr['z'][] = $txt;
            if ( $key % 3 === 1 && $txt != '' ) $arr['u'][] = $txt;
            if ( $key % 3 === 2 && $txt != '' ) $arr['v'][] = $txt;

        }

        return $arr;
    }

    
    // 
    // Функция возвращает массив разработчиков
    // 
    
    function get_arr()
    {
        return $this->parts_arr;
    }
 



    // 
    // Функция возвращает информацию о разделах в виде HTML-таблицы
    // 

    function get_html( $full = true )
    {
        $html = '';
        
        $h = new html();

        // Если полный формат - добавляем заголовок таблицы

        if ( $full ) {

            $html .= $h->table_header();
            
        }
        
        $n = 1;

        foreach ( $this->parts_arr as $course => $data ) {
            
            $html .= $h->course_name_tr( $course, $n );

            $m = 1;

            foreach ( $data['parts'] as $part => $part_data ) {
                
                $row = "<p><strong>Раздел $m.</strong> $part\n";
                
                if ( isset( $part_data['outcomes']['z'] ) ) {

                    $row .= "<p><em>знать:</em></p>\n";
                    $row .= "<ul>\n<li>" . implode( "</li>\n<li>", $part_data['outcomes']['z'] ) . "</li>\n</ul>\n";

                }

                if ( isset( $part_data['outcomes']['u'] ) ) {

                    $row .= "<p><em>уметь:</em></p>\n";
                    $row .= "<ul>\n<li>" . implode( "</li>\n<li>", $part_data['outcomes']['u'] ) . "</li>\n</ul>\n";

                }

                if ( isset( $part_data['outcomes']['v'] ) ) {

                    $row .= "<p><em>владеть:</em></p>\n";
                    $row .= "<ul>\n<li>" . implode( "</li>\n<li>", $part_data['outcomes']['v'] ) . "</li>\n</ul>\n";

                }

                $cmp = implode( ', ', $part_data['cmp'] );
                $row .= "<p><span>Компетенции: $cmp</span></p>\n";

                $html .= $h->course_data_tr( $row );

                $m++;

            }

            $cmp_stat = implode( ", ", $data['cmp_stat'] );
            $row = "<p><span>ИТОГО по дисциплине:</span> $cmp_stat</p>\n";
            
            if ( ! empty( $data['cmp_missing'] ) ) {
                
                $cmp_missing = implode( ", ", $data['cmp_missing'] );
                $row .= "<p><span class=\"error\">Отсутствуют:</span> $cmp_missing</p>\n";

            }

            $html .= $h->course_data_tr( $row );

            $n++;
        }
        
        // Если полный формат - добавляем низ таблицы

        if ( $full ) {
            
            $html .= $h->table_footer();
            
        }
        
        return $html;
    }

    private $parts_arr = array();

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