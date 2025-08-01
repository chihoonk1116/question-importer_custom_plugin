<?php
/**
 * Plugin Name: Question Importer
 * Description: Import easily question from CSV format to custom question post
 * Version: 1.0
 * Author: Chihoon Kim
 */

if( ! defined( 'ABSPATH')) exit; //Exit if accessed directly

class CVSConverterToPost{
    function __construct(){
        add_action('admin_menu', [$this, 'theMenu']);
        // add_action('admin_init', [$this, 'theSetting']);
    }

    function theMenu(){
        add_menu_page(
            'Question Importer',
            'Question Importer',
            'manage_options',
            'question-importer',
            [$this, 'qi_main_page'],
            'dashicons-update',
            100
        );

        // add_submenu_page(
        //     'question-importer',           // 부모 슬러그
        //     'Converter',           // 페이지 타이틀
        //     'Converter',           // 메뉴 타이틀
        //     'manage_options',         // 권한
        //     'converter',    // 슬러그
        //     [$this, 'converter_page']// 콜백
        // );
    }

    function qi_main_page(){?>
        <div class="wrap">
            <h1>Question Importer From CSV file</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" name="csv_file" accept=".csv" required>
                <input class="button button-primary" type="submit" name="qi_upload_csv" value="Upload CSV file">
            </form>
        </div>
    <?php

    if(isset($_POST['qi_upload_csv']) && !empty($_FILES['csv_file']['tmp_name'])){
        require_once plugin_dir_path(__FILE__) . 'inc/importer.php';
        qi_process_csv($_FILES['csv_file']['tmp_name']);
    }
    }

   
}

$cvsConverterToPost = new CVSConverterToPost();