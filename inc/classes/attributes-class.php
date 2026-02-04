<?php

//
// Класс для работы с матрицей компетенеций
// А. Н. Сергеев, Волгоград
// 02.2026
// 

// include_once dirname( __FILE__ ) . '/parser-class.php';
// include_once dirname( __FILE__ ) . '/cmp-class.php';

class attributes {

    //
    // Инициализация 
    // На входе - массив дисциплин ОПОП или текстовое описание 
    // Параметр $names_attributes - перечень names attributes
    //

    // function __construct( $data, $names_attributes = array() )
    function __construct( $data )
    {
        // Если данные - в виде текстового описания, то оформить в виде массива

        if ( ! is_string( $data ) ) return;

        // $arr = explode( "\n", $data );
        // $arr = array_map( 'strim', $arr );

        $arr = preg_split( '/\\r\\n?|\\n/', $data );
        $arr = array_map( array( $this, 'strim' ), $arr );

        // p($arr);

        foreach ( $arr as $item ) {
            
            if ( empty( $item ) ) continue;
            // p($item);

            // if ( preg_match( '/^(.+<key>\w+):(?<value>.*)/', $item, $m ) ) {
            
            $key = '';
            $value = '';
            
            if ( preg_match( '/^(.*)\s*:/mU', $item, $m ) ) $key = $m[1];
            if ( preg_match( '/:\s*(.*)/m', $item, $m ) ) $value = $m[1];
                
            // p('@');
            // p($key);
            // p($value);

            // if ( ! empty( $key ) )$this->attributes_arr[] = array( 'key' => $key, 'value' => $value );
            if ( ! empty( $key ) )$this->attributes_arr[$key] = $value;

        }

        // p($this->attributes_arr);

    
    }


    // 
    // Функция возвращает массив матрицы компетенций
    // 

    function get_arr()
    {
        return $this->attributes_arr;
    }



    //
    // Удаляет в строке лишние пробелы и всякие табуляции
    //

    private function strim( $str )
    {
        $str = trim( $str );
        $str = preg_replace( '/\s+/', ' ', $str );

        return $str;
    }


    private $attributes_arr = array();

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