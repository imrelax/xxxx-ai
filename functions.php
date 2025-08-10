<?php
/**
 * X-Man AI Theme functions and definitions
 *
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 定义主题常量
define('XMAN_THEME_VERSION', '1.0.0');
define('XMAN_THEME_DIR', get_template_directory());
define('XMAN_THEME_URI', get_template_directory_uri());

// 引入模块文件
require_once XMAN_THEME_DIR . '/inc/theme-setup.php';
require_once XMAN_THEME_DIR . '/inc/theme-functions.php';
require_once XMAN_THEME_DIR . '/inc/theme-utils.php';
require_once XMAN_THEME_DIR . '/inc/theme-admin.php';
require_once XMAN_THEME_DIR . '/inc/theme-ads.php';
require_once XMAN_THEME_DIR . '/inc/theme-sites.php';







/**
 * 创建主题脚本文件
 */
function xman_create_theme_js() {
    $js_dir = get_template_directory() . '/js';
    if (!file_exists($js_dir)) {
        wp_mkdir_p($js_dir);
    }
    
    $js_file = $js_dir . '/theme.js';
    if (!file_exists($js_file)) {
        $js_content = "// X-Man AI主题 JavaScript\ndocument.addEventListener('DOMContentLoaded', function() {\n    console.log('X-Man AI主题已加载');\n});";
        file_put_contents($js_file, $js_content);
    }
}
add_action('after_setup_theme', 'xman_create_theme_js');

/**
 * 自定义网站标题
 */
function xman_custom_document_title_parts($title) {
    $site_name = get_option('xman_site_name', get_bloginfo('name'));
    
    if (is_home() || is_front_page()) {
        $title['title'] = $site_name;
        $site_description = get_option('xman_site_description', get_bloginfo('description'));
        if (!empty($site_description)) {
            $title['tagline'] = $site_description;
        }
    } else {
        $title['site'] = $site_name;
    }
    
    return $title;
}
add_filter('document_title_parts', 'xman_custom_document_title_parts');

/**
 * 自定义网站标题分隔符
 */
function xman_custom_document_title_separator($sep) {
    return ' - ';
}
add_filter('document_title_separator', 'xman_custom_document_title_separator');

/**
 * 在head中输出自定义网站信息
 */
function xman_custom_head_meta() {
    // 确保网站名称使用主题设置
    $site_name = get_option('xman_site_name', get_bloginfo('name'));
    if ($site_name !== get_bloginfo('name')) {
        echo '<meta name="application-name" content="' . esc_attr($site_name) . '">' . "\n";
    }
}
add_action('wp_head', 'xman_custom_head_meta', 1);

/**
 * 移除WordPress默认的一些不需要的功能
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');

/**
 * Markdown支持功能
 */
function xman_add_markdown_meta_box() {
    add_meta_box(
        'xman_markdown_support',
        'Markdown支持',
        'xman_markdown_meta_box_callback',
        'post',
        'side',
        'default'
    );
    add_meta_box(
        'xman_markdown_support',
        'Markdown支持',
        'xman_markdown_meta_box_callback',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'xman_add_markdown_meta_box');

/**
 * Markdown元框回调函数
 */
function xman_markdown_meta_box_callback($post) {
    wp_nonce_field('xman_markdown_meta_box', 'xman_markdown_meta_box_nonce');
    $value = get_post_meta($post->ID, '_xman_enable_markdown', true);
    echo '<label for="xman_enable_markdown">';
    echo '<input type="checkbox" id="xman_enable_markdown" name="xman_enable_markdown" value="1" ' . checked($value, 1, false) . ' />';
    echo ' 启用Markdown解析</label>';
    echo '<p class="description">勾选此选项将在前端使用JavaScript解析文章中的Markdown语法。</p>';
}

/**
 * 保存Markdown设置
 */
function xman_save_markdown_meta_box($post_id) {
    if (!isset($_POST['xman_markdown_meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['xman_markdown_meta_box_nonce'], 'xman_markdown_meta_box')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    
    $enable_markdown = isset($_POST['xman_enable_markdown']) ? 1 : 0;
    update_post_meta($post_id, '_xman_enable_markdown', $enable_markdown);
}
add_action('save_post', 'xman_save_markdown_meta_box');

/**
 * 在文章内容中添加Markdown标识
 */
function xman_add_markdown_data_attribute($content) {
    if (is_single() || is_page()) {
        global $post;
        $enable_markdown = get_post_meta($post->ID, '_xman_enable_markdown', true);
        if ($enable_markdown) {
            // 在内容容器中添加data属性
            add_filter('the_content', function($content) {
                return '<div class="entry-content" data-markdown="true">' . $content . '</div>';
            }, 999);
        }
    }
    return $content;
}
add_filter('the_content', 'xman_add_markdown_data_attribute', 1);

/**
 * 优化WordPress查询
 */
function xman_optimize_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_home()) {
            $query->set('posts_per_page', 12);
        }
    }
}
add_action('pre_get_posts', 'xman_optimize_queries');



/**
 * 添加自定义CSS到头部
 */
function xman_custom_css() {
    $custom_css = get_option('xman_custom_css', '');
    if (!empty($custom_css)) {
        echo '<style type="text/css">' . $custom_css . '</style>';
    }
    
    // 添加随机颜色缩略图样式
    if (get_option('xman_enable_random_colors', true)) {
        echo '<style type="text/css">
        .post-thumbnail.no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 600;
            text-shadow: 0 1px 3px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }
        .post-thumbnail.no-image::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            border-radius: inherit;
        }
        .post-thumbnail.no-image .thumbnail-text {
            position: relative;
            z-index: 1;
            text-align: center;
            line-height: 1.2;
            word-break: break-word;
            max-width: 90%;
            padding: 5px;
        }
        
        /* 严格控制所有缩略图尺寸 */
        .wp-post-image,
        .post-thumbnail img,
        img[class*="object-cover"] {
            max-width: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
        }
        
        /* 文章列表缩略图容器 */
        .md\:w-1\/3 {
            overflow: hidden !important;
        }
        
        .md\:w-1\/3 img,
        .md\:w-1\/3 .post-thumbnail {
            width: 100% !important;
            height: 100% !important;
            min-height: 200px !important;
            max-height: 230px !important;
        }
        
        /* 移动端缩略图 */
        @media (max-width: 768px) {
            .h-48 img,
            .h-48 .post-thumbnail {
                height: 192px !important;
                min-height: 192px !important;
                max-height: 192px !important;
            }
        }
        </style>';
    }
}
add_action('wp_head', 'xman_custom_css');





/**
 * 从文章内容中获取第一张图片
 */
function xman_get_first_image_from_content($content) {
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
    if ($output && isset($matches[1][0])) {
        return $matches[1][0];
    }
    return false;
}



/**
 * 主题激活时的操作
 */
function xman_theme_activation() {
    // 设置默认选项
    if (!get_option('posts_per_page')) {
        update_option('posts_per_page', 12);
    }
    
    // 刷新重写规则
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'xman_theme_activation');

/**
 * 安全增强
 */
// 隐藏WordPress版本
function xman_remove_version() {
    return '';
}
add_filter('the_generator', 'xman_remove_version');

// 移除头部多余信息
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

/**
 * 自定义导航菜单Walker类 - 桌面端
 */
class Custom_Nav_Walker extends Walker_Nav_Menu {
    
    // 开始输出菜单项
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // 检查是否有子菜单
        $has_children = in_array('menu-item-has-children', $classes);
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        if ($depth === 0) {
            $output .= $indent . '<li' . $id . $class_names .' class="relative">';
        } else {
            $output .= $indent . '<li' . $id . $class_names .'>';
        }
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        if ($depth === 0) {
            if ($has_children) {
                $item_output = isset($args->before) ? $args->before ?? '' : '';
                $item_output .= '<a' . $attributes . ' class="dropdown-trigger px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 font-medium relative group flex items-center">';
                $item_output .= (isset($args->link_before) ? $args->link_before ?? '' : '') . '<span class="relative z-10">' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
                $item_output .= '<i class="fas fa-chevron-down ml-2 text-xs transition-transform duration-200 group-hover:rotate-180"></i>';
                $item_output .= '<span class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg opacity-0 group-hover:opacity-10 transition-opacity duration-200"></span>';
                $item_output .= (isset($args->link_after) ? $args->link_after ?? '' : '') . '</a>';
                $item_output .= isset($args->after) ? $args->after ?? '' : '';
            } else {
                $item_output = isset($args->before) ? $args->before ?? '' : '';
                $item_output .= '<a' . $attributes . ' class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 font-medium relative group">';
                $item_output .= (isset($args->link_before) ? $args->link_before ?? '' : '') . '<span class="relative z-10">' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
                $item_output .= '<span class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg opacity-0 group-hover:opacity-10 transition-opacity duration-200"></span>';
                $item_output .= (isset($args->link_after) ? $args->link_after ?? '' : '') . '</a>';
                $item_output .= isset($args->after) ? $args->after ?? '' : '';
            }
        } else {
            $item_output = isset($args->before) ? $args->before ?? '' : '';
            $item_output .= '<a' . $attributes . ' class="block px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">';
            $item_output .= (isset($args->link_before) ? $args->link_before ?? '' : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after ?? '' : '');
            $item_output .= '</a>';
            $item_output .= isset($args->after) ? $args->after ?? '' : '';
        }
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    // 开始输出子菜单
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        if ($depth === 0) {
            $output .= "\n$indent<ul class=\"sub-menu absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible translate-y-1 transition-all duration-200 z-50\" style=\"display: none;\">\n";
        } else {
            $output .= "\n$indent<ul class=\"sub-menu\">\n";
        }
    }
    
    // 结束输出子菜单
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}

/**
 * 自定义导航菜单Walker类 - 移动端
 */
class Custom_Mobile_Nav_Walker extends Walker_Nav_Menu {
    
    // 开始输出菜单项
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        // 为不同深度的菜单项添加不同的图标
        $icon = 'fas fa-circle';
        if ($depth === 0) {
            $icon = 'fas fa-folder';
        } elseif ($depth === 1) {
            $icon = 'fas fa-file';
        }
        
        $item_output = isset($args->before) ? $args->before ?? '' : '';
        $item_output .= '<a' . $attributes . ' class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200 font-medium group" style="padding-left: ' . (1 + $depth) * 1 . 'rem;">';
        $item_output .= '<i class="' . $icon . ' w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors"></i>';
        $item_output .= (isset($args->link_before) ? $args->link_before ?? '' : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after ?? '' : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after ?? '' : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

?>