<?php

//
// Учебный план
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_tools_templates extends mif_mr_tools_core {
    
    function __construct()
    {
        parent::__construct();
        
        add_filter( 'lib-upload-save-title', array( $this, 'set_save_title' ), 10, 3 );

    }
        

    

    // 
    // Показывает  
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-tools-templates.php' ) ) {
           
            load_template( $template, false );
            
        } else {
                
            load_template( dirname( __FILE__ ) . '/../templates/mr-tools-templates.php', false );

        }
    }
    




    // 
    //   
    // 

    public function get_tools_templates()
    {
        global $mr;
        if ( ! $mr->user_can(3) ) return;
        // $att_id = mif_mr_functions::get_att_id();

        $out = '';

        // Разбор формы

        $m = new mif_mr_upload();
        $res = $m->save( array( 'ext' => array( 'docx' ) ) ); 
        // p($res);
        foreach ( (array) $res as $i ) {
            
            $out .= mif_mr_functions::get_callout( 
                $i['name'] . ' — <span class="fw-semibold">' . $i['messages'] . '</span>', 
                $i['status'] ); 

            if ( isset( $i['id'] ) ) $att_id = $i['id'];

        }        

        // Показать форму

        $out .= mif_mr_upload::form_upload( array( 
                            'text' => 'Загрузите шаблон в формате docx',
                            // 'title_placeholder' => 'Название', 
                            // 'title_placeholder' => 'Название плана', 
                            'url' => 'tools-templates',
                            'type' => 'templates',
                            'multiple' => true  
                        ) );
        

        // Показать список файлов плана
        
        $out .= $this->show_list_file_templates();
        
        
        // // Показать план
        
        // if ( ! empty( $att_id ) ) $out .= $this->show_file_curriculum( $att_id );
        
        
        // // 
        
        $out .= $this->get_meta( 'file' );


        return apply_filters( 'mif_mr_get_tools_curriculum', $out );

    }
    

    
    
    // 
    // Вывести  
    // 

    public function show_list_file_templates()
    {
        $out = '';

        // $arr = $this->get_file_curriculum();
        // $arr = $this->get_file( array( 'ext' => array( 'docx' ) ) );
        $arr = $this->get_file( array( 'type' => 'templates' ) );

        // p($arr);

        $out .= '<div class="container mt-5">';
        
        $out .= '<div class="row">';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold">№</div>';
        $out .= '<div class="col p-2 pt-4 pb-4 fw-semibold">Название шаблона</div>';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold">Файл</div>';
        $out .= '<div class="col col-2 p-2 pt-4 pb-4 fw-semibold"></div>';
        $out .= '</div>';
        
        $n = 0;
        $out .= '<div class="striped">';
        
        foreach ( $arr as $item ) {

            $out .= '<div class="row">';
            $out .= '<div class="col col-1 p-2">' . ++$n . '</div>';
            // $out .= '<div class="col p-2">' . $item->post_title . '</div>';
            $out .= '<div class="col p-2"><a href="' .  $item->guid . '">' . $item->post_title . '</a></div>';
            $out .= '<div class="col col-1 p-2 text-center">' .  
                    mif_mr_opop_core::get_a_file( mif_mr_opop_core::get_opop_url() . '/file/' . $item->ID, 'lines' ) . '</div>';
            // $out .= '<div class="col col-1 p-2 text-center">' .  mif_mr_opop_core::get_a_file( $item->guid, 'word' ) . '</div>';
            $out .= '<div class="col col-2 p-2 text-center">';
            $out .= '<a href="#" class="m-3 reload" data-attid="' . $item->ID . '"><i class="fa-solid fa-repeat fa-lg"></i></a>';
            $out .= '<a href="#" class="m-3 remove" data-attid="' . $item->ID . '"><i class="fa-regular fa-trash-can fa-lg"></i></a>';
            $out .= '</div>';
            $out .= '</div>';
            
        }
            
        $out .= '</div>';

            // $out .= '<div class="row d-none no-plans">';
            $style = ( $n === 0 ) ? '' : ' style="display: none;"'; 
            $out .= '<div class="row no-plans"' . $style . '>';
            $out .= '<div class="col p-4 text-center bg-light border rounded fw-semibold">Нету шаблонов</div>';
            $out .= '</div>';

        $out .= '</div>';

        return $out;
    }



  
    function set_save_title( $title, $tmp_name, $name )
    {
        return preg_replace( '/\.[^.]+$/', '', wp_basename( $name ) );
    }


}

?>