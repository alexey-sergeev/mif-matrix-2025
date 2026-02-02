<?php

//
//  Список компетенций
//  Компетенции
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_lib_references_screen extends mif_mr_lib_references {
    

    function __construct()
    {
        parent::__construct();

        // $this->sub_default = apply_filters( 'mif-mr-sub_default', 'default' );
        $this->save_all();

    }
  
        
    // 
    // Показывает компетенции
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-references.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-references.php', false );

        }
    }
    

    
    
    //
    // Показать references
    //
    
    public function show_references( $comp_id = NULL, $opop_id = NULL )
    {

        p('22');

        // Init $comp_id, $opop_id
        
        // if ( empty( $comp_id ) ) {
            
        //     global $wp_query;
        //     if ( ! isset( $wp_query->query_vars['id'] ) ) return 'wp: error 1';
        //     $comp_id = $wp_query->query_vars['id'];

        // }

        // if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
           
        
        // // Save

        // // if ( isset( $_REQUEST['do'] ) && $_REQUEST['do'] == 'save' ) {
            
        //     if ( isset( $_REQUEST['sub'] ) ) $this->save_part( (int) $_REQUEST['sub'], $comp_id, $opop_id );

        // // }


        // // HTML

        // global $tree;
        
        // $out = '';
        $out = '@1@';
        // $f = true;

        // $out .= '<div class="content-ajax">';

        // // if ( $f ) $out .= '<div><a href="' . get_edit_post_link( $comp_id ) . '">Расширенный редактор</a></div>';
        
        // if ( isset( $tree['content']['lib-references']['data'][$comp_id] ) ) {

        //     $item = $tree['content']['lib-references']['data'][$comp_id];

        //     // $out .= '<h4 class="mb-6 mt-5 ">' . $item['name'] . '</h4>';
        //     $out .= '<h4 class="mb-4 mt-0 pb-5 pt-5 bg-body fiksa">' . $item['name'] . '</h4>';
        //     // $out .= 'id: ' . $comp_id;
        //     $out .= $this->get_show_all();

        //     $out .= '<div class="container no-gutters">';

        //     foreach ( $item['data'] as $item2 ) {
                
        //         $out .= '<span>';
                
        //         $out .= $this->show_comp_sub( $item2['sub_id'], $comp_id, $opop_id );
                
        //         $out .= '</span>';
                
        //     }
        
        //     // New

        //     if ( $f ) $out .= '<div class="row mb-3 mt-6">';
        //     if ( $f ) $out .= '<div class="col mr-gray p-3 fw-bolder text-center">';
        //     if ( $f ) $out .= '<a href="#" class="new"><i class="fa-solid fa-plus fa-xl"></i></a>';
        //     if ( $f ) $out .= '</div>';
        //     if ( $f ) $out .= '</div>';
            
        //     $out .= '</div>';
            
        // }
        
        // // if ( $f ) $out .= '<div class="row mt-3">';
        // // if ( $f ) $out .= '<div class="col">';
        // // if ( $f ) $out .= '<small><a href="#" class="msg-remove">Удалить</a></small>';
        
        // // $msg = '<div>Вы уверены?</div>';

        // // $msg .= '<div><label class="form-label mt-4"><input type="checkbox" name="yes" value="on" class="form-check-input"> Да</label></div>';
        // // $msg .= '<button type="button" class="btn btn-primary mr-3 remove">Удалить <i class="fas fa-spinner fa-spin d-none"></i></button>';
        // // $msg .= '<button type="button" class="btn btn-light border mr-3 cancel">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';
      
      
        // // if ( $f ) $out .= '<div class="alert pl-0 pr-0" style="display: none;">' . mif_mr_functions::get_callout( $msg, 'warning' ) . '</div>';
        
        // // if ( $f ) $out .= '</div>';
        // // if ( $f ) $out .= '</div>';

        // // Hidden
        
        // if ( $f ) $out .= '<input type="hidden" name="opop" value="' . $opop_id . '">';
        // if ( $f ) $out .= '<input type="hidden" name="comp" value="' . $comp_id . '">';
        // if ( $f ) $out .= '<input type="hidden" name="action" value="lib-references">';
        // if ( $f ) $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
        // $out .= '</div>';
        
        return apply_filters( 'mif_mr_show_references', $out );
    }
    
    
    

    //
    // Показать cписок компетенций - часть
    //
    
    public function show_comp_sub( $sub_id, $comp_id, $opop_id = NULL )
    {
        global $tree;
        
        $f = true;
        
        if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        if ( ! ( isset( $tree['content']['lib-competencies']['data'][$comp_id] ) || $sub_id == '-1' ) ) return 'wp: error 2';
              
        $item = $tree['content']['lib-competencies']['data'][$comp_id]['data'][$sub_id];
        // $style = ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) ? '' : 'style="display: none;"';
        
        // HTML
        
        $out = '';   
        $out .= '<span class="content-ajax">';
        
        $out .= $this->get_sub_head( array(
                                            'name' => $item['name'],
                                            'sub_id' => $sub_id,
                                            'f' => $f            
                                            ) );

        // $out .= '<div class="row mb-3 mt-3">';
        
        // // Наименование категории

        // $out .= '<div class="col-11 mr-gray p-3 fw-bolder">';
        // $out .= $item2['name'];
        // $out .= '</div>';
        
        // // Кнопка edit

        // $out .= '<div class="col-1 mr-gray p-3 text-end">';
        // if ( $f ) $out .= '<i class="fas fa-spinner fa-spin d-none"></i> ';
        // if ( $f ) $out .= '<a href="#" class="edit pr-1" data-sub="' . $sub_id . '"><i class="fa-regular fa-pen-to-square"></i></a>';
        // $out .= '<a href="#" class="roll-up d-none"><i class="fa-solid fa-angle-up"></i></a>';
        // $out .= '<a href="#" class="roll-down"><i class="fa-solid fa-chevron-down"></i></a>';
        // $out .= '</div>';
        
        // $out .= '</div>';

        if ( isset( $_REQUEST['do'] ) && $_REQUEST['do'] == 'edit' ) {
            
            // Режим edit

            if ( $f ) $out .= $this->get_sub_edit( $sub_id, $comp_id, $opop_id );
            
        } else {
            
            // Режим отображения
            
            foreach ( $item['data'] as $item2 ) $out .= $this->get_item_body( $item2 );

        }
        
        $out .= '</span>';
   
        return apply_filters( ' mif_mr_show_competencies_sub', $out, $sub_id, $comp_id, $opop_id );
    }
    
    
    

    
    //
    // Показать cписок компетенций - компетенция, body
    //
    
    public static function get_item_body( $item )
    {
        $name_indicators = apply_filters( 'mif-mr-name-indicators', array( 'знать', 'уметь' ) );
        
        $out = '';

        $out .= '<div class="row">';
        
        $out .= '<div class="col col-2 col-md-1 fw-bolder">';
        $out .= ( isset( $item['name'] ) ) ? $item['name'] : '';
        $out .= '</div>';
        
        $out .= '<div class="col mb-3">';
        $out .= ( isset( $item['descr'] ) ) ? $item['descr'] : '';
        $out .= '</div>';
        
        $out .= '</div>';

        // Индикаторы

        if ( isset( $item['indicators'] ) ) {

            foreach ( (array) $item['indicators'] as $key4 => $item4 ) {
                
                $style = ' style="display: none;"';

                $out .= '<div class="row coll"' . $style . '>';
                
                $out .= '<div class="col col-2 col-md-1">';
                $out .= '</div>';
                
                $out .= '<div class="col p-1 pl-3 m-3 mr-gray fst-italic">';
                $out .= ( isset( $name_indicators[$key4] ) ) ? $name_indicators[$key4] : 'индикатор ' . $key4;
                $out .= '</div>';
                
                $out .= '</div>';
                
                $out .= '<div class="row coll"' . $style . '">';
                $out .= '<div class="col col-2 col-md-1">';
                $out .= '</div>';
                
                $out .= '<div class="col">';
                foreach ( $item4 as $item5 ) $out .= '<p class="m-2">' . $item5 . '</p>';
                $out .= '</div>';
                $out .= '</div>';
                
            }
            
            $out .= '<div class="row coll"' . $style . '">';
            $out .= '<div class="col col-2 col-md-1">';
            $out .= '</div>';
            
            $out .= '<div class="col">';
            $out .= '&nbsp;';
            $out .= '</div>';
            $out .= '</div>';
            
        
        }
        
        return apply_filters( ' mif_mr_get_item_body', $out, $item );
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
        
        $out .= '<div class="comp container bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';
        // $out .= '<div class="container no-gutters">';
        
        $out .= '<div class="row">';
        
        $out .= '<div class="col">';
        $out .= '<h4 class="border-bottom pb-5"><i class="fa-regular fa-file-lines"></i> Библиотека компетенций</h4>';
        // $out .= '<hr class="bg-secondary fs-1">';
        $out .= '</div>';
        
        $out .= '</div>';
        global $tree;

        $arr = array();
        if ( isset( $tree['content']['lib-competencies']['data'] ) ) $arr = $tree['content']['lib-competencies']['data'];
    
        foreach ( $arr as $item ) {
     
            // p($item);

            $out .= '<div class="row mt-3 mb-3">';
            
            $out .= '<div class="col-10 col-md-11 pt-1 pb-1">';
            $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-competencies/' . $item['comp_id'] . '">' . $item['name'] . '</a>';
            $out .= '</div>';
            
            $out .= '<div class="col-2 col-md-1 pt-1 pb-1 text-end">';
            $out .= ( $item['from_id'] == mif_mr_opop_core::get_opop_id() ||  $item['from_id'] == 0 ) ?
                    // $item['parent'] :
                    '' :
                    '<a href="' .  get_permalink( $item['from_id'] ) . 'lib-competencies/' . $item['from_id'] . '" title="' . 
                    $this->mb_substr( get_the_title( $item['from_id'] ), 20 ) . '">' . $item['from_id'] . '</a>';
            $out .= '</div>';
            
            $out .= '</div>';
            
        }
        
        if ( $f ) $out .= $this->show_lib_comp_create();
    
        $out .= '</div>';
        
        $out .= '</div>';
        
        $out .= '</div>';
        
        
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
        $out .= '<input type="hidden" name="action" value="lib-competencies">';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
        $out .= '</div>';
        $out .= '</div>';
       
        return apply_filters( 'mif_mr_show_list_compe_create', $out );
    }
    





   
}


?>