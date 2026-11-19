<?php

//
// Настройки справочников
// 
//


defined( 'ABSPATH' ) || exit;

class mif_mr_set_templates extends mif_mr_set_core {
    
    function __construct()
    {
        parent::__construct();

        $this->do_save();                                       
                                                
    }
    
    
    
    // 
    // Показывает  
    // 
    
    public function the_show()
    {
        global $mr;

        if ( ! $mr->user_can( 2 ) ) {
            
            echo $mr->access_denied();
            return false;
        
        }

        if ( $template = locate_template( 'mr-set-templates.php' ) ) {
            
            load_template( $template, false );
            
        } else {
            
            load_template( dirname( __FILE__ ) . '/../templates/mr-set-templates.php', false );
            
        }
    }
    
    


    //
    // Показать cписок templates
    //
    
    public function show_set_templates()
    {
        global $mr;
        if ( ! $mr->user_can(2) ) return;
    
        global $tree;
        
        $out = '';
        
        if ( isset( $_REQUEST['edit'] ) ) {
            
            if ( $_REQUEST['edit'] == 'easy' ) {
                
                $out .= $this->edit_easy();
                
            } else {
                
                $out .= $this->companion_edit( 'set-templates' );
                
            }
            
        } else {
            
            
            $out .= '<div class="row fiksa">';
            $out .= '<div class="col p-0">';
            $out .= '<h4 class="mb-4 mt-0 pt-3 pb-5 bg-body">Шаблоны:</h4>';
            $out .= '</div>';
            $out .= '</div>';
    
            if ( ! empty( $tree['content']['templates']['data']['templates'] ) ) {
                
                $templates = $tree['content']['templates']['data']['templates'];
                
                $out .= '<div class="row bg-light border rounded">';
                
                $out .= '<div class="col-10 col-md-10 pt-5 pb-5">';
                $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-templates/' . $templates['comp_id'] . '">' . $templates['name'] . '</a>';
                $out .= '</div>';
                
                $out .= '<div class="col-2 col-md-2 pt-5 pb-5 text-end">';
                $out .= mif_mr_opop_core::get_a_id( $templates['comp_id'], mif_mr_opop_core::get_opop_url() . 'lib-templates/' . $templates['comp_id'] );
                $out .= '</div>';
                
                $out .= '</div>';

                $out .= '<div class="row">';
                $out .= '<div class="col p-0 pt-3 pb-5">';
                
                $out .= mif_mr_lib_templates_screen::get_templates_data( $templates, false );

                $out .= '</div>';
                $out .= '</div>';
                
            } else {
                
                $out .= '<div class="row">';
                $out .= '<div class="col p-0">';
                
                $out .= mif_mr_functions::get_callout( 'Нет данных', 'warning' );
                
                $out .= '</div>';
                $out .= '</div>';
            
            }
           
        }
        
        return apply_filters( 'mif_mr_show_set_templates', $out );
    }
    

    

    //
    //
    //

    public function edit_easy()
    {
        global $tree;    
    
        $out = '';

        $n = 0; 

        $out .= '<table>';
    
        $out .= '<thead><tr>';
        $out .= '<th><div class="p-2">№</div></th>';
        $out .= '<th><div class="p-2">Наименование комплекта шаблонов</div></th>';
        $out .= '<th><div class="p-2">Идентификатор</div></th>';
        $out .= '</tr></thead>';    
        
        $old_data = ( ! empty( $tree['content']['set-templates']['data']['templates'] ) ) ? $tree['content']['set-templates']['data']['templates'] : ''; 
    
        $out .= '<tr>';
        $out .= '<td><div class="p-2">';
        $out .= '1';
        $out .= '</div></td>';

        $out .= '<td><div class="p-2">';
        $out .= 'Шаблоны';
        $out .= '</div></td>';
        
        $out .= '<td style="vertical-align: middle;">';
        $out .= '<input class="form-control" type="text" name="data" value="' . $old_data . '">';
        $out .= '</td>';

        $out .= '</tr>';
            
        $out .= '</table>';
        
        return apply_filters( 'mif_mr_edit_easy', $out );
    }
        

    
    
    
    
    private function do_save() 
    {
        if ( isset( $_REQUEST['data'] ) ) {
            
            $arr = array();
            foreach ( (array) $_REQUEST['data'] as $k => $d ) $arr[] =  $d;
        
            $this->save( 'set-templates', implode( "\n", $arr ) ); 
        
        } else {
            
            $this->save( 'set-templates' ); 
            
        }
        
        
    }
                
}

?>