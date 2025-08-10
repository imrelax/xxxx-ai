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
    add_theme_support('custom-logo');
    add_theme_support('customize-selective-refresh-widgets');
    
    // 注册导航菜单
    register_nav_menus(array(
        'primary' => '主导航菜单',
        'footer' => '页脚菜单',
    ));
    
    // 设置缩略图尺寸
    set_post_thumbnail_size(300, 200, true);
    add_image_size('hero-slider', 800, 400, true);
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

/**
 * 加载样式和脚本
 */
function xman_scripts() {
    // 主题样式表
    wp_enqueue_style('xman-style', XMAN_THEME_URI . '/assets/css/style.css', array(), XMAN_THEME_VERSION);
    
    // Tailwind CSS
    wp_enqueue_style('tailwindcss', 'https://cdn.tailwindcss.com', array(), '3.4.0');
    
    // Font Awesome - 使用国内CDN提高访问速度
    wp_enqueue_style('font-awesome', 'https://cdn.bootcdn.net/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
    
    // Marked.js - Markdown解析库
    wp_enqueue_script('marked', 'https://cdn.jsdelivr.net/npm/marked/marked.min.js', array(), '4.3.0', true);
    
    // 主题脚本
    wp_enqueue_script('xman-script', XMAN_THEME_URI . '/assets/js/theme.js', array('jquery', 'marked'), XMAN_THEME_VERSION, true);
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