<?php

//
// Создание xlsx-файлов
// 
//


defined( 'ABSPATH' ) || exit;


// 
// Требуется установка библиотеки
// 
// Команда для установки:
// composer require phpoffice/phpspreadsheet
// 
// Документация: https://phpspreadsheet.readthedocs.io/en/latest/
// 

require dirname( __FILE__ ) . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


class mif_mr_xlsx  {

    private $blank = '';

    // function __construct( $blank = '' )
    // {
    //     $this->blank = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';
    //     if ( $blank ) $this->blank = $blank;        

    // }

    function __construct( $path = NULL )
    {
        if ( empty( $path ) ) $path = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';
        $spreadsheet = IOFactory::load( $path );
        $this->sheet = $spreadsheet->getActiveSheet();
        // $cellValue = $spreadsheet->getActiveSheet()->getCell('C4')->getValue();
        // $cellValue = $this->sheet->getCell('C4')->getValue();
        // p($cellValue);
        // p($this->sheet);

    }

    // function __construct()
    // {


    // }


    //
    // Получить готовый xlsx-файл
    // 
    //  $arr - данные, которые надо вставить в таблицу
    //  $cell - левая верхняя ячейка, откуда начинать заполнять
    // 

    function set( $arr = array(), $blank = '', $cell = 'A1' )
    {
        if ( empty( $blank ) ) $blank = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';

        $spreadsheet = IOFactory::load( $blank );
        $sheet = $spreadsheet->getActiveSheet();

        // $sheet->setCellValue( 'A1', 'Hello World !' );
        $sheet->fromArray( $arr, '', $cell, true );
       
        $upload_dir = (object) wp_upload_dir();
        $file = trailingslashit( $upload_dir->path ) . md5( serialize( $arr ) . $cell ) . '.xlsx';
        
        $writer = new Xlsx( $spreadsheet );
        $writer->save( $file );

        return $file;
    }



    //
    // Получить из файла данные
    // 
    // 

    function get_arr()
    {
        $arr = array();
        $arr = $this->sheet->ToArray(null, true, true, true);
        return $arr;
    }


    //
    // Получить из файла данные
    // 
    // 

    function get( $cell = 'A1' )
    {
        $cell_value = $this->sheet->getCell($cell)->getValue();
        if ( empty( $cell_value ) ) $cell_value = '';
        return strim( $cell_value );
    }



    //
    // Получить из файла данные
    // 
    // 

    function get_courses( $path )
    {
        $arr = $this->get( $path );

        p($arr);

        return $arr;
    }


    private $sheet;


}

?>