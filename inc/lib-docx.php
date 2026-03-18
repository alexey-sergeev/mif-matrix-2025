<?php

//
// Создание docx-файлов
// 
//


defined( 'ABSPATH' ) || exit;


// 
// Требуется установка библиотеки
// 
// Команда для установки:
// composer require phpoffice/phpword
// 
// Документация: https://github.com/PHPOffice/PHPWord
// 

require dirname( __FILE__ ) . '/../vendor/autoload.php';



class mif_mr_docx {

    private $blank = '';

    function __construct( $blank = '' )
    {
        $this->blank = dirname( __FILE__ ) . '/../docx/default.docx';
        if ( $blank ) $this->blank = $blank; 

    }


    //
    // Получить готовый вщсx-файл
    // 
    //  $arr - данные, которые надо вставить в шаблон
    // 

    function make_docx( $arr = array() )
    {
        // $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor( $this->blank );
        $templateProcessor = new mif_TemplateProcessor( $this->blank );

        foreach ( $arr as $key => $item ) {

            if ( is_array( $item ) ) {
                
                $templateProcessor->cloneRowAndSetValues( $key, $item );

            } else {
                
                $templateProcessor->setValue( $key, $item );

            }

        }

        $file = mif_mr_download::get_path_tmp( 'docx' );

        $templateProcessor->saveAs( $file );


        return $file;
    }




}

?>