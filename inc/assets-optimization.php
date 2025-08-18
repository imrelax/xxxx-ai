<?php
/**
 * 资源优化模块
 * 优化CSS和JavaScript的加载方式
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 优化的样式和脚本加载
 */
function xman_optimized_scripts() {
    // 移除默认的jQuery，使用更轻量的版本
    wp_deregister_script('jquery');
    wp_register_script('jquery', XMAN_THEME_URI . '/assets/js/jquery.min.js', array(), '3.6.0', true);
    wp_enqueue_script('jquery');
    
    // 直接使用主题样式表（不使用合并压缩）
    wp_enqueue_style('xman-theme-style', XMAN_THEME_URI . '/assets/css/style.css', array(), XMAN_THEME_VERSION);
    
    // 加载Marked.js库
    wp_enqueue_script('marked-js', XMAN_THEME_URI . '/assets/js/marked.min.js', array(), '4.3.0', true);
    
    // 直接使用主题脚本（不使用合并压缩）
    wp_enqueue_script('xman-theme-script', XMAN_THEME_URI . '/assets/js/theme.js', array('jquery', 'marked-js'), XMAN_THEME_VERSION, true);
    
    // 传递必要的数据给JavaScript
    wp_localize_script('xman-theme-script', 'xman_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('xman_nonce')
    ));
}

// 已移除资源合并功能，直接使用原始CSS文件

// 已移除JavaScript合并功能，直接使用原始JS文件

// 已移除所有资源合并和压缩相关函数

/**
 * 禁用不必要的WordPress功能以提升性能
 */
function xman_disable_unnecessary_features() {
    // 移除WordPress版本信息
    remove_action('wp_head', 'wp_generator');
    
    // 移除RSD链接
    remove_action('wp_head', 'rsd_link');
    
    // 移除Windows Live Writer链接
    remove_action('wp_head', 'wlwmanifest_link');
    
    // 移除短链接
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // 移除相邻文章链接
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    
    // 移除REST API链接
    remove_action('wp_head', 'rest_output_link_wp_head');
    
    // 移除oEmbed链接
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // 禁用emoji脚本
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    
    // 禁用Gutenberg块编辑器样式（如果不使用）
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
}
add_action('init', 'xman_disable_unnecessary_features');

/**
 * 延迟加载非关键CSS
 */
function xman_defer_non_critical_css() {
    ?>
    <script>
    // 延迟加载非关键CSS
    function loadCSS(href) {
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;
        document.head.appendChild(link);
    }
    
    // 页面加载完成后加载非关键CSS
    window.addEventListener('load', function() {
        // 这里可以添加非关键CSS文件
    });
    </script>
    <?php
}
add_action('wp_head', 'xman_defer_non_critical_css', 20);

/**
 * 预加载关键资源（修复预加载警告）
 */
function xman_preload_critical_resources() {
    // 移除预加载，直接使用正常的link标签
    // 这样可以避免预加载警告，因为资源会立即被使用
    
    // 预加载关键字体（如果有）
    // echo '<link rel="preload" href="' . XMAN_THEME_URI . '/assets/fonts/font.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
}
add_action('wp_head', 'xman_preload_critical_resources', 1);

/**
 * 添加DNS预取和预连接
 */
function xman_add_resource_hints() {
    // 预连接到常用的CDN
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'xman_add_resource_hints', 1);

// 已禁用资源合并功能，直接使用原始CSS文件
// add_action('after_switch_theme', 'xman_generate_combined_assets');

// 已禁用开发模式下的自动重新生成
// if (defined('WP_DEBUG') && WP_DEBUG) {
//     add_action('wp_loaded', 'xman_generate_combined_assets');
// }