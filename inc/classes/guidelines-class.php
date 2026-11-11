<?php

//
// Класс для работы с методическими указаниями
// А. Н. Сергеев, Волгоград
// Август 2026
// 

include_once dirname( __FILE__ ) . '/functions.php';
include_once dirname( __FILE__ ) . '/parser-class.php';
include_once dirname( __FILE__ ) . '/curriculum-class.php';
include_once dirname( __FILE__ ) . '/html-class.php';

class guidelines {


    function __construct( $data = '' )
    {

        if ( is_string( $data ) ) {
    
            $p = new parser();
            $data = $p->get_arr( $data, array( 'section' => 'guidelines' ) );
    
        }
        
        if ( isset( $data['GUIDELINES']['guidelines']['parts'] ) ) {

            foreach ( (array) $data['GUIDELINES']['guidelines']['parts'] as $item ) {
                
                $this->guidelines_arr['guidelines'][$item['att'][0]] = array(
                                                                            
                                                                            'name' => $item['name'],
                                                                            'text' => implode( "\n", $item['data'] ),
                                                                        
                                                                        );

            }
 
        };

    }
 

    

    // 
    // Функция возвращает методические указания из описания
    // 
  
    public function get_guidelines( $raw, $lib, $course )
    {
        if ( empty( $lib['data'] ) ) return $raw;

        $unit = $course['unit'];
        
        foreach ( array( 'lec', 'lab', 'prac', 'srs' ) as $i )
            $flags[$i] = ( isset( $course['meta']['hours'][$i] ) && ! $course['meta']['hours'][$i] ) ? false : true; 
        
        foreach ( array( 'эк', 'зч', 'зчо', 'к', 'кр', 'кп' ) as $i )
            $flags[ 'exam_' . $i ] = ( isset( $course['meta']['hours']['att'][$i] ) && $course['meta']['hours']['att'][$i] ) ? true : false;    

        $flags['exam_мод'] = ( empty( $course['meta']['hours']['att']['эч'] ) &&
                                empty( $course['meta']['hours']['att']['зч'] ) &&
                                empty( $course['meta']['hours']['att']['зчо'] ) &&
                                empty( $course['meta']['hours']['att']['к'] ) ) ? true : false;
        
        $index = array();
        foreach ( $lib['data'] as $k => $d ) $index[$d['att']['slug']][] = $k;
            
        $course_exam = ( ! empty( $course['meta']['hours']['att'] ) ) ? array_unique( array_keys( $course['meta']['hours']['att'] ) ) : array();

        $arr = array();

        foreach ( $index as $k => $d ) {
            
            foreach ( $d as $i ) {

                $arr_units = array_map( 'mb_strtoupper', array_map( 'trim', explode( ',', $lib['data'][$i]['att']['units'] ) ) );
                $arr_exam = array_diff( array_map( 'mb_strtolower', array_map( 'trim', explode( ',', $lib['data'][$i]['att']['exam'] ) ) ), array( '' ) );

                $intersect = array_intersect( $arr_exam, $course_exam );

                if ( in_array( $unit, $arr_units ) ) {

                    if ( empty( $arr_exam ) || ( ! empty( $arr_exam ) && ! empty( $intersect ) ) ) {

                        $arr[$k][] = array( 
                                        'name' => $lib['data'][$i]['data'][0]['key'],
                                        'text' => $this->do_clean( $lib['data'][$i]['data'][0]['value'], $flags ),
                                    );

                    }

                }

            }
        }

        $arr2 = array();
        foreach ( $arr as $k => $a ) {
            
            $arr3 = array();
            foreach ( $a as $i ) $arr3[] = $i['text'];
            
            $arr2[$k]['name'] = $arr[$k][0]['name']; 
            $arr2[$k]['text'] = implode( "\n", $arr3 );

        }
        
        foreach ( (array) $raw as $k => $a ) $arr2[$k] = $a;
        
        return $arr2;
    }
    



    private function do_clean( $t, $flags )
    {
        $adapter = apply_filters( 'mif_mr_guidelines_do_clean', array( 
                    'ЛЕК' => 'lec', 
                    'ЛАБ' => 'lab', 
                    'ПРАК' => 'prac', 
                    'СРС' => 'srs',
                    'ЭК' => 'exam_эк',
                    'ЗЧ' => 'exam_зч',
                    'ЗЧО' => 'exam_зчо', 
                    'К' => 'exam_к',
                    'КР' => 'exam_кр', 
                    'КП' => 'exam_кп',
                    'МОД' => 'exam_мод',
                ) );

        $arr = explode( '{', $t );

        $s = '';

        foreach ( $arr as $a ) {

            preg_match( '/^(?<key>.*):(?<value>.*)}(?<value2>.*\s.*)$/U', $a, $m );

            if ( empty( $m['key'] ) ) {
    
                $s .= $a;
                continue;

            }

            $key = ( isset( $m['key'] ) ) ? mb_strtoupper( trim( $m['key'] ) ) : '';
            $value = ( isset( $m['value'] ) ) ? trim( $m['value'] ) : '';
            $value2 = ( isset( $m['value2'] ) ) ? $m['value2'] : '';

            $value3 = '';
            if ( isset( $adapter[$key] ) && $flags[ $adapter[$key] ] ) $value3 = $value;
            
            $s .= $value3 . $value2;

        }

        $arr2 = explode( "\n", $s );

        $arr3 = array();
        
        foreach ( $arr2 as $a ) {

            if ( empty( $a ) ) continue;

            $a = strim( $a );
            $a = preg_replace( '/, \./', '.', $a );

            $arr3[] = $a;

        }

        $out = implode( "\n", $arr3 );

        return $out;
    }




    // 
    // Функция возвращает массив методических указаний
    // 
    
    function get_arr()
    {
        return $this->guidelines_arr;
    }
 

    private $guidelines_arr = array();

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