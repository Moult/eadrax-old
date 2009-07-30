<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Output images in JPEG format
 */
define('SI_IMAGE_JPEG', 1);
/**
 * Output images in PNG format
 */
define('SI_IMAGE_PNG',  2);
/**
 * Output images in GIF format
 * Must have GD >= 2.0.28!
 */
define('SI_IMAGE_GIF',  3);

/***************
 * 目錄路徑設定
 ***************/  
//TTF字型的目錄位置
$config['ttf_file'] = MODPATH."securimage/resource/elephant.ttf"; 
 
//自訂文字碼清單
$config['wordlist_file'] = MODPATH."securimage/resource/words/words.txt";  
 
//使用的GD字型
$config['gd_font_file'] = MODPATH."securimage/resource/gdfonts/bubblebath.gdf";  
 
//語言檔的路徑 
$config['audio_path'] = MODPATH."securimage/resource/audio";
 
/***************
 * 圖片設定
 ***************/
//圖片的寬度
$config['image_width'] = 135;
 
//圖片的高度
$config['image_height'] = 45;
 
//圖片的類型
//可選用：SI_IMAGE_PNG, SI_IMAGE_JPG, SI_IMAGE_GIF
$config['image_type'] = SI_IMAGE_PNG;
 
/***************
 * 文字設定
 ***************/
//文字碼的長度
$config['code_length'] = 4;
 
//可選用的文字碼字元(小寫會自動被轉為大寫)
$config['charset'] = 'ABCDEFGHKLMNPRSTUVWYZ23456789';
 
//啟動自訂文字碼清單模式(取代亂數文字碼的方法)
$config['use_wordlist'] = false;
 
//啟用GD字型來替代TTF字型
$config['use_gd_font'] = true;
 
//GD字型的大小
$config['gd_font_size'] = 20;
 
//字型大小
$config['font_size'] = 24;
 
//傾斜的最小角度
$config['text_angle_minimum'] = -20;
 
//傾斜的最大角度
$config['text_angle_maximum'] = 20;
 
//文字碼產生的X軸位置
$config['text_x_start'] = 8;
 
//字距的最小程度
$config['text_minimum_distance'] = 30;
 
//字距的最大程度
$config['text_maximum_distance'] = 30;
 
//背景的顏色
$config['image_bg_color']="#e3daed";
 
//文字碼的顏色
$config['text_color']="#000000";
 
//啟用混合的文字顏色
$config['use_multi_text'] = true;
 
//交差使用的文字顏色
$config['multi_text_color']="#0a68dd,#f65c47,#8d32fd";
 
//使用清晰的文字
$config['use_transparent_text'] = true;
 
//清晰的程度
$config['text_transparency_percentage'] = 15;
 
/***************
 * 格線設定
 ***************/
//產生網格線
$config['draw_lines'] = true;
 
//網格線顏色
$config['line_color']="#FFFFFF";
 
//背景網格大小
$config['line_distance'] = 10;
 
//背景網格線的粗線
$config['line_thickness'] = 1;
 
//在背景網格上增加交差線
$config['draw_angled_lines'] = true;
 
//背景網格線是否蓋過文字
$config['draw_lines_over_text'] = true;
 
//是否在文字上增加橫線
$config['arc_linethrough'] = false;
 
//橫線的顏色
$config['arc_line_colors']="#8080ff";