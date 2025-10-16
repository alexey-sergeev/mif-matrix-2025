<?php
/*
Plugin Name: MIF Matrix 2025
Plugin URI: https://github.com/alexey-sergeev/mif-matrix
Description: Плагин для разработки и размещения документации основных профессиональных образовательных программ
Author: Алексей Н. Сергеев
Version: 1.0.0
Author URI: https://vk.com/alexey_sergeev
*/

defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/inc/mr-init.php';



add_action( 'init', 'mr_init' );

function mr_init()
{
    global $mr;
    $mr = new mif_mr_init();
}



add_action( 'wp', 'mr_download' );

function mr_download()
{
    new mif_mr_download();
}



add_action( 'wp_enqueue_scripts', 'mif_mr_customizer_scripts' );

// 
// JS-методы
// 
function mif_mr_customizer_scripts() 
{

   // jQuery
   wp_enqueue_script( 'mif_mr_jquery', plugins_url( 'lib/jquery/jquery-3.7.1.min.js', __FILE__ ), '', '3.7.1' );
   

   // Sortable (https://www.cssscript.com/fast-html-table-sorting/)
   wp_enqueue_script( 'mif_mr_sortable', plugins_url( 'lib/sortable/sortable.min.js', __FILE__ ), '', '4.1.1' );
//    wp_enqueue_script( 'mif_mr_sortable_plus', plugins_url( 'lib/sortable/sortable.a11y.min.js', __FILE__ ), '', '4.1.1' );
   wp_register_style( 'mif_mr_sortable', plugins_url( 'lib/sortable/sortable-base.min.css', __FILE__ ), '', '4.1.1' );
   wp_enqueue_style( 'mif_mr_sortable' );



   // JS-методы каталога
   wp_enqueue_script( 'mif_mr_js_catalog', plugins_url( 'js/catalog.js', __FILE__ ), '', '1.0.0' );

}




add_action( 'wp_enqueue_scripts', 'mif_mr_customizer_styles' );

function mif_mr_customizer_styles() 
{
    // Font Awesome
    
    // wp_register_style( 'font-awesome', plugins_url( 'lib/fontawesome/css/fontawesome.min.css', __FILE__ ) );
    wp_register_style( 'font-awesome', plugins_url( 'lib/fontawesome-7.1/css/all.css', __FILE__ ) );
	wp_enqueue_style( 'font-awesome' );

    // wp_enqueue_script( 'fa-v4-shim', plugins_url( 'lib/fontawesome/js/fa-v4-shims.js', __FILE__ ) );
    // wp_enqueue_script( 'font-awesome-js', plugins_url( 'lib/fontawesome/js/fontawesome-all.js', __FILE__ ), '', '1.1.0' );
    
    // Twitter bootstrap
    
    wp_register_style( 'bootstrap', plugins_url( 'lib/bootstrap/css/bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'bootstrap' );
    wp_enqueue_script( 'bootstrap', plugins_url( 'lib/bootstrap/js/bootstrap.min.js', __FILE__ ) );

    // // Выноски bootstrap
    
    // wp_register_style( 'callout', plugins_url( 'lib/callout.css', __FILE__ ) );
	// wp_enqueue_style( 'callout' );
    
    // Локальные стили

    wp_register_style( 'mr-styles', plugins_url( 'mif-mr-styles.css', __FILE__ ), '', '1.0.0' );
    wp_enqueue_style( 'mr-styles' );

    // jQuery
    // wp_enqueue_script( 'mif_mr_jquery', plugins_url( 'lib/jquery/jquery-3.7.1.min.js', __FILE__ ), '', '3.7.1' );

    // JS-методы
    // wp_enqueue_script( 'mif_mr_js_catalog', plugins_url( 'js/catalog.js', __FILE__ ), '', '1.0.0' );

    // // Плагин сортировки
    // wp_enqueue_script( 'mif_qm_sortable', plugins_url( 'js/qm-sortable.js', __FILE__ ), '', '1.4.4' );

}




if ( ! function_exists( 'p' ) ) {

    function p( $data )
    {
        print_r( '<pre>' );
        print_r( $data );
        print_r( '</pre>' );
    }

}


if ( ! function_exists( 'f' ) ) {
    
        function f( $data )
        {
            file_put_contents( '/tmp/qmlog.txt', date( "D M j G:i:s T Y - " ), FILE_APPEND | LOCK_EX );
            file_put_contents( '/tmp/qmlog.txt', print_r( $data, true ), FILE_APPEND | LOCK_EX );
            file_put_contents( '/tmp/qmlog.txt', "\n", FILE_APPEND | LOCK_EX );
        }
    
}
    

// if ( ! function_exists( 'hooks_list' ) ) {


//     function hooks_list( $hook_name = '' ){
//         global $wp_filter;
//         $wp_hooks = $wp_filter;

//         // для версии 4.4 - переделаем в массив
//         if( is_object( reset($wp_hooks) ) ){
//             foreach( $wp_hooks as & $object ) $object = $object->callbacks;
//             unset($object);
//         }

//         if( $hook_name ){
//             $hooks[ $hook_name ] = $wp_hooks[ $hook_name ];

//             if( ! is_array($hooks[$hook_name]) ){
//                 trigger_error( "Nothing found for '$hook_name' hook", E_USER_WARNING );
//                 return;
//             }
//         }
//         else {
//             $hooks = $wp_hooks;
//             ksort( $wp_hooks );
//         }

//         $out = '';
//         foreach( $hooks as $name => $funcs_data ){
//             ksort( $funcs_data );
//             $out .= "\nхук\t<b>$name</b>\n";
//             foreach( $funcs_data as $priority => $functions ){
//                 $out .= "$priority";
//                 foreach( array_keys($functions) as $func_name ) $out .= "\t$func_name\n";
//             }
//         }

//         echo '<'.'pre>'. $out .'</pre'.'>';
//     }
// }


function strim( $st = '' )
{
    // Удаляет двойные пробелы, а также пробелы в начале и в конце строки

    $st = preg_replace( '/\s+/', ' ', $st );
    $st = trim( $st );
    
    return $st;
}


?>
