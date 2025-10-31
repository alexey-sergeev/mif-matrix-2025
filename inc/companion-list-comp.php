<?php

//
//  Список компетенций
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_list_comp extends mif_mr_companion_core {
    

    function __construct()
    {
        parent::__construct();

    }
  
        
    // 
    // Показывает компетенции
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-list-comp.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-list-comp.php', false );

        }
    }
    




    //
    // Показать cписок компетенций
    //

    public function show_list_comp( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();

        ####!!!!!
        
        $f = true;

        $out = '';
        
        $list = $this->get_list_companions( 'competencies' );
             
        // p($list);
        // p($arr);
        
        $out .= '<div class="content-ajax">';
        
        $out .= '<div class="container bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';
        // $out .= '<div class="container no-gutters">';

        $out .= '<div class="row">';

        $out .= '<div class="col">';
        $out .= '<h4 class="border-bottom pb-5"><i class="fa-regular fa-file-lines"></i> Библиотека компетенций</h4>';
        // $out .= '<hr class="bg-secondary fs-1">';
        $out .= '</div>';
        
        $out .= '</div>';

        foreach ( $list as $item ) {
          
            $out .= '<div class="row mt-3 mb-3">';

            $out .= '<div class="col-10 col-md-11 pt-1 pb-1">';
            $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'competencies/' . $item['id'] . '">' . $item['title'] . '</a>';
            $out .= '</div>';
            
            $out .= '<div class="col-2 col-md-1 pt-1 pb-1 text-end">';

            $out .= ( $item['parent'] == mif_mr_opop_core::get_opop_id() ||  $item['parent'] == 0 ) ?
                    $item['parent'] :
                    '<a href="' .  get_permalink( $item['parent'] ) . '" title="' . 
                    $this->mb_substr( get_the_title( $item['parent'] ), 20 ) . '">' . $item['parent'] . '</a>';
            $out .= '</div>';
            
            $out .= '</div>';

        }
                
        if ( $f ) $out .= '<div class="row mt-5">';
        if ( $f ) $out .= '<div class="col">';
        // $out .= '<button type="button" class="btn btn-primary">Создать список компетенций</button>';
        if ( $f ) $out .= '<button type="button" class="btn btn-primary new">Создать список</button>';
        if ( $f ) $out .= '</div>';
        if ( $f ) $out .= '</div>';

        if ( $f ) $out .= '<div class="row new" style="display: none;">';
        if ( $f ) $out .= '<div class="col mt-5">';
        // $out .= '<button type="button" class="btn btn-primary">Создать список компетенций</button>';
        
        // $out .= '';

        if ( $f ) $out .= '<div class="mb-3">';
        // $out .= '<label class="form-label">Название перечня компетенций</label>';
        if ( $f ) $out .= '<label class="form-label">Название cписка компетенций:</label>';
        if ( $f ) $out .= '<input name="title" class="form-control" autofocus>';
        if ( $f ) $out .= '<div class="form-text">Например: ФГОС "Информатика и вычислительная техника", ОПОП "Математика", ...</div>';
        if ( $f ) $out .= '</div>';
        
        if ( $f ) $out .= '<div class="mb-3">';
        // $out .= '<label class="form-label">Данные <i class="fa-regular fa-circle-question" style="color: #aaa;"></i></label>';
        if ( $f ) $out .= '<label class="form-label">Данные:</label>';
        // $out .= mif_mr_functions::get_callout( '<a href="' . '123' . '">Помощь</a>', 'warning' );
        if ( $f ) $out .= '<textarea name="data" class="form-control" rows="3"></textarea>';
        if ( $f ) $out .= '<div class="form-text">Например: УК-1. Способен использовать философские знания, ... (<a href="' . '123' . '">помощь</a>)</div>';
        if ( $f ) $out .= '<button type="button" class="btn btn-primary mt-4 mr-3 create">Сохранить <i class="fas fa-spinner fa-spin d-none"></i></button>';
        if ( $f ) $out .= '<button type="button" class="btn btn-light border mt-4 mr-3 cancel">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';

        if ( $f ) $out .= '<input type="hidden" name="opop" value="' . mif_mr_opop_core::get_opop_id() . '">';
        if ( $f ) $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';

        $out .= '</div>';


        $out .= '</div>';
        $out .= '</div>';


        $out .= '</div>';

        $out .= '</div>';


        return apply_filters( 'mif_mr_show_list_competencies', $out );
    }
    

 
}


?>