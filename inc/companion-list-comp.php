<?php

//
//  Список компетенций
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_lib_comp extends mif_mr_companion_core {
    

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

    public function show_lib_comp( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        ####!!!!!
        
        $f = true;
        
        $this->create( $opop_id );

        $out = '';
        
        // $list = $this->get_list_companions( 'competencies' );
        
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
        global $tree;

        $arr = array();
        if ( isset( $tree['content']['competencies']['data'] ) ) $arr = $tree['content']['competencies']['data'];
    
        foreach ( $arr as $item ) {
     
            // p($item);

            $out .= '<div class="row mt-3 mb-3">';
            
            $out .= '<div class="col-10 col-md-11 pt-1 pb-1">';
            $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'competencies/' . $item['comp_id'] . '">' . $item['name'] . '</a>';
            $out .= '</div>';
            
            $out .= '<div class="col-2 col-md-1 pt-1 pb-1 text-end">';
            $out .= ( $item['parent'] == mif_mr_opop_core::get_opop_id() ||  $item['parent'] == 0 ) ?
                    // $item['parent'] :
                    '' :
                    '<a href="' .  get_permalink( $item['parent'] ) . 'competencies/' . $item['comp_id'] . '" title="' . 
                    $this->mb_substr( get_the_title( $item['parent'] ), 20 ) . '">' . $item['parent'] . '</a>';
            $out .= '</div>';
            
            $out .= '</div>';
            
        }
        
        if ( $f ) $out .= $this->show_lib_comp_create();
    
        $out .= '</div>';
        
        $out .= '</div>';
        
        // $out .= '</div>';
        
        
        return apply_filters( 'mif_mr_show_list_competencies', $out );
    }
    
    
    
    //
    // Форму создания 
    //
    
    private  function show_lib_comp_create()
    {
        $out = '';
        
        $out .= '<div class="row mt-5">';
        $out .= '<div class="col">';
        // $out .= '<button type="button" class="btn btn-primary">Создать список компетенций</button>';
        $out .= '<button type="button" class="btn btn-primary new">Создать список</button>';
        $out .= '</div>';
        $out .= '</div>';
        
        $out .= '<div class="row new" style="display: none;">';
        $out .= '<div class="col mt-5">';
        // $out .= '<button type="button" class="btn btn-primary">Создать список компетенций</button>';
        
        // $out .= '';
        
        $out .= '<div class="mb-3">';
        // $out .= '<label class="form-label">Название перечня компетенций</label>';
        $out .= '<label class="form-label">Название cписка компетенций:</label>';
        $out .= '<input name="title" class="form-control" autofocus>';
        $out .= '<div class="form-text">Например: ФГОС "Информатика и вычислительная техника", ОПОП "Математика", ...</div>';
        $out .= '</div>';
        
        $out .= '<div class="mb-3">';
        // $out .= '<label class="form-label">Данные <i class="fa-regular fa-circle-question" style="color: #aaa;"></i></label>';
        $out .= '<label class="form-label">Данные:</label>';
        // $out .= mif_mr_functions::get_callout( '<a href="' . '123' . '">Помощь</a>', 'warning' );
        $out .= '<textarea name="data" class="form-control" rows="3"></textarea>';
        $out .= '<div class="form-text">Например: УК-1. Способен использовать философские знания, ... (<a href="' . '123' . '">помощь</a>)</div>';
        $out .= '<button type="button" class="btn btn-primary mt-4 mr-3 create">Сохранить <i class="fas fa-spinner fa-spin d-none"></i></button>';
        $out .= '<button type="button" class="btn btn-light border mt-4 mr-3 cancel">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';
        
        $out .= '<input type="hidden" name="opop" value="' . mif_mr_opop_core::get_opop_id() . '">';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
        $out .= '</div>';
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_show_list_compe_create', $out );
    }
    
}


?>