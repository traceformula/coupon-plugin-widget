<?php
/**
 * Plugin Name: Coupon plugin 
 * Plugin URI: N/A
 * Description: Coupon plugin 
 * Version: 1.1
 * Author: Titan
 * Author URI: N/A
 * License: N/A
 */
?>

<?php
    $xmlConfigFile = plugins_url('coupon-plugin-widget/config.xml');
    $xml=simplexml_load_file($xmlConfigFile) or die("Error: Cannot create object");
    $GLOBALS['fileUrl'] = $xml ->downloadUrl;
    $GLOBALS['fileName'] = $xml ->fileName;
    $GLOBALS['scheduleTime'] = $xml ->scheduleTime;
?>

<?php
add_filter( 'cron_schedules', 'cron_add_custom_time' );
function cron_add_custom_time( $schedules ) {
    $schedule_time = intval($GLOBALS['scheduleTime']);
    $schedules['customScheduleTime'] = array(
        'interval' => $schedule_time,
        'display' => __( 'Custom schedule time' )
    );
    return $schedules;
}

if ( ! wp_next_scheduled( 'download_xlsx_schedule' ) ) {
    wp_schedule_event(time(), 'customScheduleTime', 'download_xlsx_schedule');
}
 
add_action( 'download_xlsx_schedule', 'download_xlsx_schedule_function' );
function download_xlsx_schedule_function() {
    $filePath = wp_upload_dir()['path'] .'\\'. $GLOBALS['fileName'];
    $fileUrl =  $GLOBALS['fileUrl'];
    download_xlsx_file( $filePath, $fileUrl);
}

//Download XLSX file
function download_xlsx_file($filePath, $fileUrl){
    if( file_exists ($filePath) ){
        $newName = $GLOBALS['fileName'].'_'. date("Ymd_h-i-sa"). ".xlsx";
        $newPath = wp_upload_dir()['path'] . "\\" . $newName;
        rename($filePath, $newPath);
        file_put_contents($filePath, fopen($fileUrl, 'r'));
    }else{
        file_put_contents($filePath, fopen($fileUrl, 'r'));
    }
}

register_deactivation_hook( __FILE__, 'prefix_deactivation' );
/**
 * On deactivation, remove all functions from the scheduled action hook.
 */
function prefix_deactivation() {
    wp_clear_scheduled_hook( 'download_xlsx_schedule' );
}
?>

<?php
include 'PHPExcel\Classes\PHPExcel\IOFactory.php';
require_once('Coupon_Widget.php');

function add_widget_resource() {
    wp_register_script('my-script', plugins_url('coupon-plugin-widget/resource/coupon-widget-script.js'), array('jquery'),'1.0', true );
    wp_register_style( 'my-css', plugins_url('coupon-plugin-widget/resource/coupon-widget-style.css') );

    wp_enqueue_script('my-script');
    wp_enqueue_style('my-css');
}
add_action( 'init', 'add_widget_resource' );

add_action( 'widgets_init', 'create_coupon_plugin_widget' );
function create_coupon_plugin_widget() {
     register_widget('Coupon_Widget');
}

?>