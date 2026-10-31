<?php

//
// Ядро Инструменты разработки ОПОП
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_tools_core {

    function __construct()
    {

        $this->remove_all();
        $this->reload();

    }
    
    

    // 
    // Показывает меню  
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-tools.php' ) ) {
           
            load_template( $template, false );
            
        } else {
                
            load_template( dirname( __FILE__ ) . '/../templates/mr-tools.php', false );

        }
    }
    



    // 
    // Получить файлы 
    //    

    public static function get_file( $att = array() )
    {
        // global $post;

        $opop_id = ( isset( $att['opop_id'] ) ) ? $att['opop_id'] : mif_mr_opop_core::get_opop_id();
        $type = ( isset( $att['type'] ) ) ? $att['type'] : '';

        $args = array(
            'numberposts' => -1,
            'post_parent' => $opop_id, 
            'post_type' => 'file',
            'order' => 'DESC',
            'post_status' => 'publish',
            'tax_query' => array( array(
                                    'taxonomy' => 'file_type',
                                    'field' => 'name',
                                    'terms' => $type
                                ) ),   
        );

        if ( ! empty( $att['orderby'] ) ) $args['orderby'] = $att['orderby'];
        if ( ! empty( $att['order'] ) ) $args['order'] = $att['order'];

        $arr = get_posts( $args );
            
        return $arr;
    }



    //
    // Мета-поля
    //

    public function get_meta( $type = 'courses' )
    {
        $out = '';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';  
        $out .= '<input type="hidden" name="opop" value="' . mif_mr_opop_core::get_opop_id() . '" />';  
        $out .= '<input type="hidden" name="type" value="' . $type . '" />';  
        $out .= '<input type="hidden" name="opop_title" value="' . mif_mr_opop_core::get_opop_title() . '" />';  
        
        return $out;
    }



    //
    // 
    //

    public function reload()
    {
        // !!!!!!!

        if ( ! ( isset( $_REQUEST['do'] ) && $_REQUEST['do'] == 'reload' ) ) return;
        if ( empty( $_REQUEST['attid'] )) return;

        $m = new mif_mr_upload();
        $res = $m->reload(); 

        $att = get_post( (int) $_REQUEST['attid'] );
        
        global $messages;
        $messages[] = ( $res ) ? array( $att->post_title . ' — <b>Обновлено</b>', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 28030', 'danger' );

        return;
    }



    //
    //
    //   

    function remove( $attid )
    {
        // ###!!!!!!!
        global $mr;
        if ( ! $mr->user_can(3) ) return;

        $m = new mif_mr_upload();
        return $m->remove( $attid );
    }



    //
    //
    //   

    function remove_all()
    {
        // !!!!!!!

        if ( ! isset( $_REQUEST['remove_all'] ) ) return;
        
        global $wp_query;

        $m = new mif_mr_upload();

        if ( $wp_query->query['part'] == 'tools-curriculum' ) $arr = $this->get_file( array( 'ext' => array( 'plx' ) ) );
        if ( $wp_query->query['part'] == 'tools-courses' ) $arr = $this->get_file( array( 'ext' => array( 'xls', 'xlsx' ) ) );

        // p($arr);

        $res = true;

        foreach ( $arr as $a ) {

            if ( $m->remove( $a->ID ) === false ) $res = false;
            // if ( wp_delete_attachment( $a->ID, true ) === false ) $res = false;

        }
        
        global $messages;
        $messages[] = ( $res ) ? array( 'Все файлы были удалены', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 1802', 'danger' );
        
        return;
    }




    //
    //
    //   

    function remove_all_link( $type = 'courses' )
    {
        global $mr;
        if ( ! $mr->user_can(3) ) return;
        return '<div class="pb-4"><a href="' . mif_mr_opop_core::get_opop_url() . 'tools-' . $type . '/?remove_all">Удалить все файлы</a></div>';
    }



    //
    //
    //

    function get_path( $id )
    {
        $m = new mif_mr_upload();    
        return $m->get_path( $id );
    }


}

?>