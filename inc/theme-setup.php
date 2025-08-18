<?php
/**
 * 主题设置和初始化
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 主题设置
 */
function xman_theme_setup() {
    // 添加主题支持
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // 启用评论功能
    add_theme_support('comments');
    add_post_type_support('post', 'comments');
    add_post_type_support('page', 'comments');
    add_theme_support('custom-logo');
    add_theme_support('customize-selective-refresh-widgets');
    
    // 注册导航菜单
    register_nav_menus(array(
        'primary' => '主导航菜单',
        'footer' => '页脚菜单',
    ));
    
    // 设置缩略图尺寸
    set_post_thumbnail_size(300, 200, true);
    add_image_size('hero-slider', 1200, 600, true); // 优化幻灯片图片尺寸
    add_image_size('hero-slider-large', 1600, 800, true); // 大屏幕幻灯片图片
    add_image_size('widget-thumb', 60, 60, true);
}
add_action('after_setup_theme', 'xman_theme_setup');

/**
 * 注册侧边栏
 */
function xman_widgets_init() {
    register_sidebar(array(
        'name'          => '主侧边栏',
        'id'            => 'sidebar-1',
        'description'   => '显示在博客页面右侧的小部件区域',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="widget-header"><h3>',
        'after_title'   => '</h3></div><div class="widget-content">',
    ));
}
add_action('widgets_init', 'xman_widgets_init');

// 引入资源优化模块
require_once XMAN_THEME_DIR . '/inc/assets-optimization.php';

/**
 * 加载样式和脚本（优化版本）
 */
function xman_scripts() {
    // 使用优化的资源加载
    xman_optimized_scripts();
}
add_action('wp_enqueue_scripts', 'xman_scripts');

/**
 * 自定义摘要长度
 */
function xman_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'xman_excerpt_length');

/**
 * 自定义摘要结尾
 */
function xman_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'xman_excerpt_more');