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
require_once XMAN_THEME_DIR . '/inc/theme-config.php';
require_once XMAN_THEME_DIR . '/inc/theme-components.php';
require_once XMAN_THEME_DIR . '/inc/theme-setup.php';
require_once XMAN_THEME_DIR . '/inc/theme-functions.php';
require_once XMAN_THEME_DIR . '/inc/theme-utils.php';
require_once XMAN_THEME_DIR . '/inc/theme-admin.php';
require_once XMAN_THEME_DIR . '/inc/theme-ads.php';
require_once XMAN_THEME_DIR . '/inc/theme-sites.php';
require_once XMAN_THEME_DIR . '/inc/theme-software.php';
require_once XMAN_THEME_DIR . '/inc/performance-optimization.php';
require_once XMAN_THEME_DIR . '/inc/image-optimization.php';
require_once XMAN_THEME_DIR . '/inc/theme-functions-optimization.php';
require_once XMAN_THEME_DIR . '/inc/pagespeed-optimization.php';

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
 * 优化WordPress主查询
 */
function xman_optimize_main_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_home()) {
            $query->set('posts_per_page', 12);
        }
    }
}
add_action('pre_get_posts', 'xman_optimize_main_queries');

/**
 * 在后台文章列表页面添加文章类型筛选器
 */
function xman_add_post_type_filter() {
    global $typenow;
    
    // 只在文章列表页面显示
    if ($typenow == 'post') {
        $selected = isset($_GET['post_type_filter']) ? $_GET['post_type_filter'] : '';
        ?>
        <select name="post_type_filter" id="post-type-filter">
            <option value=""><?php _e('所有类型'); ?></option>
            <option value="article" <?php selected($selected, 'article'); ?>><?php _e('文章'); ?></option>
            <option value="software" <?php selected($selected, 'software'); ?>><?php _e('软件'); ?></option>
        </select>
        <?php
    }
}
add_action('restrict_manage_posts', 'xman_add_post_type_filter');

/**
 * 处理文章类型筛选查询
 */
function xman_filter_posts_by_type($query) {
    global $pagenow;
    
    // 只在后台文章列表页面处理
    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type_filter']) && !empty($_GET['post_type_filter'])) {
        $post_type_filter = $_GET['post_type_filter'];
        
        if ($post_type_filter == 'software') {
            // 筛选软件类型的文章
            $query->set('meta_query', array(
                array(
                    'key' => '_post_content_type',
                    'value' => 'software',
                    'compare' => '='
                )
            ));
        } elseif ($post_type_filter == 'article') {
            // 筛选普通文章类型
            $query->set('meta_query', array(
                'relation' => 'OR',
                array(
                    'key' => '_post_content_type',
                    'value' => 'article',
                    'compare' => '='
                ),
                array(
                    'key' => '_post_content_type',
                    'compare' => 'NOT EXISTS'
                )
            ));
        }
    }
}
add_action('pre_get_posts', 'xman_filter_posts_by_type');

/**
 * 在后台文章列表页面添加文章类型列
 */
function xman_add_post_type_column($columns) {
    // 在标题列后面插入文章类型列
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key == 'title') {
            $new_columns['post_type_display'] = '文章类型';
        }
    }
    return $new_columns;
}
add_filter('manage_posts_columns', 'xman_add_post_type_column');

/**
 * 显示文章类型列的内容
 */
function xman_show_post_type_column_content($column, $post_id) {
    if ($column == 'post_type_display') {
        $post_content_type = get_post_meta($post_id, '_post_content_type', true);
        if ($post_content_type == 'software') {
            echo '<span style="color: #0073aa; font-weight: bold;">📱 软件</span>';
        } else {
            echo '<span style="color: #666;">📄 文章</span>';
        }
    }
}
add_action('manage_posts_custom_column', 'xman_show_post_type_column_content', 10, 2);

/**
 * 使文章类型列可排序
 */
function xman_make_post_type_column_sortable($columns) {
    $columns['post_type_display'] = 'post_type_meta';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'xman_make_post_type_column_sortable');

/**
 * 处理文章类型列的排序
 */
function xman_sort_post_type_column($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    if ($query->get('orderby') == 'post_type_meta') {
        $query->set('meta_key', '_post_content_type');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'xman_sort_post_type_column');

/**
 * 添加自定义CSS到头部
 */
function xman_custom_css() {
    $custom_css = get_option('xman_custom_css', '');
    if (!empty($custom_css)) {
        // 安全输出自定义CSS，过滤恶意代码
        $safe_css = wp_strip_all_tags($custom_css);
        echo '<style type="text/css">' . esc_html($safe_css) . '</style>';
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
        
        /* 严格控制所有缩略图尺寸 - 只针对真正的图片元素 */
        .wp-post-image,
        .post-thumbnail img,
        img[class*="object-cover"] {
            max-width: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
        }
        
        /* 占位图div样式保护 */
        .post-thumbnail div {
            object-fit: unset !important;
            object-position: unset !important;
        }
        
        /* 文章列表缩略图容器 */
        .md\:w-1\/3 {
            overflow: hidden !important;
        }
        
        .md\:w-1\/3 img {
            width: 100% !important;
            height: 100% !important;
            min-height: 200px !important;
            max-height: 230px !important;
            object-fit: cover !important;
        }
        
        .md\:w-1\/3 .post-thumbnail {
            width: 100% !important;
            height: 100% !important;
            min-height: 200px !important;
            max-height: 230px !important;
        }
        
        /* 移动端缩略图 */
        @media (max-width: 768px) {
            .h-48 img {
                height: 192px !important;
                min-height: 192px !important;
                max-height: 192px !important;
                object-fit: cover !important;
            }
            
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
        
        // 检查是否有子菜单 - 使用更可靠的检测方法
        $has_children = false;
        
        // 方法1: 检查WordPress自动添加的类
        if (in_array('menu-item-has-children', $classes)) {
            $has_children = true;
        }
        
        // 方法2: 检查walker传递的has_children属性
        if (isset($args->has_children) && $args->has_children) {
            $has_children = true;
        }
        
        // 方法3: 手动检查是否有子菜单项（最可靠的方法）
        if (!$has_children && isset($GLOBALS['wp_nav_menu_items'])) {
            foreach ($GLOBALS['wp_nav_menu_items'] as $menu_item) {
                if ($menu_item->menu_item_parent == $item->ID) {
                    $has_children = true;
                    break;
                }
            }
        }
        
        // 如果确实有子菜单但类中没有menu-item-has-children，则添加它
        if ($has_children && !in_array('menu-item-has-children', $classes)) {
            $classes[] = 'menu-item-has-children';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        if ($depth === 0) {
            $output .= $indent . '<li' . $id . ' class="relative menu-item-' . $item->ID . '">';
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
        
        // 检查是否有子菜单
        $has_children = false;
        if (in_array('menu-item-has-children', $classes)) {
            $has_children = true;
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        if ($depth === 0) {
            $output .= $indent . '<li' . $id . ' class="mobile-menu-item-' . $item->ID . '">';
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
                $item_output .= '<a' . $attributes . ' class="mobile-dropdown-trigger block px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200 font-medium flex items-center justify-between">';
                $item_output .= '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
                $item_output .= '<i class="fas fa-chevron-down text-xs transition-transform duration-200"></i>';
                $item_output .= '</a>';
                $item_output .= isset($args->after) ? $args->after ?? '' : '';
            } else {
                $item_output = isset($args->before) ? $args->before ?? '' : '';
                $item_output .= '<a' . $attributes . ' class="block px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200 font-medium">';
                $item_output .= apply_filters('the_title', $item->title, $item->ID);
                $item_output .= '</a>';
                $item_output .= isset($args->after) ? $args->after ?? '' : '';
            }
        } else {
            $item_output = isset($args->before) ? $args->before ?? '' : '';
            $item_output .= '<a' . $attributes . ' class="block px-6 py-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors duration-200">';
            $item_output .= apply_filters('the_title', $item->title, $item->ID);
            $item_output .= '</a>';
            $item_output .= isset($args->after) ? $args->after ?? '' : '';
        }
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    // 开始输出子菜单
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        if ($depth === 0) {
            $output .= "\n$indent<ul class=\"mobile-sub-menu bg-gray-50 border-l-2 border-blue-200 ml-4 hidden\">\n";
        } else {
            $output .= "\n$indent<ul class=\"mobile-sub-menu\">\n";
        }
    }
    
    // 结束输出子菜单
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}

/**
 * 确保管理员用户具有unfiltered_html权限
 * 这样可以在广告位和统计代码中使用JavaScript
 */
function xman_ai_add_unfiltered_html_capability() {
    $role = get_role('administrator');
    if ($role && !$role->has_cap('unfiltered_html')) {
        $role->add_cap('unfiltered_html');
    }
}
add_action('init', 'xman_ai_add_unfiltered_html_capability');

/**
 * 为编辑者角色也添加unfiltered_html权限（可选）
 * 如果需要编辑者也能使用JavaScript代码，取消下面的注释
 */
/*
function xman_ai_add_editor_unfiltered_html_capability() {
    $role = get_role('editor');
    if ($role && !$role->has_cap('unfiltered_html')) {
        $role->add_cap('unfiltered_html');
    }
}
add_action('init', 'xman_ai_add_editor_unfiltered_html_capability');
*/





/**
 * 添加文章类型选择元框
 */
function xman_ai_add_post_type_meta_box() {
    add_meta_box(
        'post_type_selector',
        '文章类型',
        'xman_ai_post_type_selector_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'xman_ai_add_post_type_meta_box');

/**
 * 文章类型选择元框回调函数
 */
function xman_ai_post_type_selector_callback($post) {
    // 添加nonce字段用于安全验证
    wp_nonce_field('xman_ai_post_type_meta_box', 'xman_ai_post_type_meta_box_nonce');
    
    // 获取当前文章类型
    $post_content_type = get_post_meta($post->ID, '_post_content_type', true);
    if (empty($post_content_type)) {
        $post_content_type = 'article'; // 默认为文章
        // 为新文章设置默认值
        if ($post->post_status == 'auto-draft') {
            update_post_meta($post->ID, '_post_content_type', 'article');
        }
    }
    ?>
    <div id="post-type-selector">
        <p>
            <label>
                <input type="radio" name="post_content_type" value="article" <?php checked($post_content_type, 'article'); ?>>
                <span class="dashicons dashicons-edit"></span> 文章
            </label>
        </p>
        <p>
            <label>
                <input type="radio" name="post_content_type" value="software" <?php checked($post_content_type, 'software'); ?>>
                <span class="dashicons dashicons-download"></span> 软件
            </label>
        </p>
        <p class="description">选择要发布的内容类型</p>
    </div>
    
    <style>
    #post-type-selector label {
        display: flex;
        align-items: center;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 5px;
        cursor: pointer;
        transition: all 0.2s;
    }
    #post-type-selector label:hover {
        background-color: #f0f0f1;
        border-color: #0073aa;
    }
    #post-type-selector input[type="radio"]:checked + .dashicons {
        color: #0073aa;
    }
    #post-type-selector .dashicons {
        margin-right: 8px;
        margin-left: 4px;
    }
    </style>
    <?php
}

/**
 * 保存文章类型选择
 */
function xman_ai_save_post_type_meta_box_data($post_id) {
    // 验证nonce
    if (!isset($_POST['xman_ai_post_type_meta_box_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['xman_ai_post_type_meta_box_nonce'], 'xman_ai_post_type_meta_box')) {
        return;
    }
    
    // 检查用户权限
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // 检查是否为自动保存
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // 保存文章类型
    if (isset($_POST['post_content_type'])) {
        update_post_meta($post_id, '_post_content_type', sanitize_text_field($_POST['post_content_type']));
    }
}
add_action('save_post', 'xman_ai_save_post_type_meta_box_data');

/**
 * 添加管理后台JavaScript来控制软件元框的显示/隐藏
 */
function xman_ai_admin_post_type_script() {
    global $pagenow, $post_type;
    
    if (($pagenow == 'post.php' || $pagenow == 'post-new.php') && $post_type == 'post') {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // 初始化显示状态
            function toggleSoftwareMetaBoxes() {
                var selectedType = $('input[name="post_content_type"]:checked').val();
                
                if (selectedType === 'software') {
                    $('#post_software_details, #post_software_downloads').show();
                } else {
                    $('#post_software_details, #post_software_downloads').hide();
                }
            }
            
            // 确保默认选中文章类型
            if (!$('input[name="post_content_type"]:checked').length) {
                $('input[name="post_content_type"][value="article"]').prop('checked', true);
            }
            
            // 页面加载时初始化
            setTimeout(function() {
                toggleSoftwareMetaBoxes();
            }, 100);
            
            // 监听单选按钮变化
            $(document).on('change', 'input[name="post_content_type"]', function() {
                toggleSoftwareMetaBoxes();
            });
        });
        </script>
        <?php
    }
}
add_action('admin_footer', 'xman_ai_admin_post_type_script');

/**
 * 获取文章的内容类型
 */
function xman_ai_get_post_content_type($post_id) {
    $content_type = get_post_meta($post_id, '_post_content_type', true);
    return empty($content_type) ? 'article' : $content_type;
}

/**
 * 检查文章是否为软件类型
 */
function xman_ai_is_software_post($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return xman_ai_get_post_content_type($post_id) === 'software';
}

// ==================== 专题功能 ====================

/**
 * 注册专题自定义文章类型
 */
function xman_register_topic_post_type() {
    $labels = array(
        'name'                  => '专题',
        'singular_name'         => '专题',
        'menu_name'             => '专题',
        'name_admin_bar'        => '专题',
        'archives'              => '专题归档',
        'attributes'            => '专题属性',
        'parent_item_colon'     => '父专题:',
        'all_items'             => '所有专题',
        'add_new_item'          => '添加新专题',
        'add_new'               => '添加新专题',
        'new_item'              => '新专题',
        'edit_item'             => '编辑专题',
        'update_item'           => '更新专题',
        'view_item'             => '查看专题',
        'view_items'            => '查看专题',
        'search_items'          => '搜索专题',
        'not_found'             => '未找到专题',
        'not_found_in_trash'    => '回收站中未找到专题',
        'featured_image'        => '专题封面',
        'set_featured_image'    => '设置专题封面',
        'remove_featured_image' => '移除专题封面',
        'use_featured_image'    => '使用作为专题封面',
        'insert_into_item'      => '插入到专题',
        'uploaded_to_this_item' => '上传到此专题',
        'items_list'            => '专题列表',
        'items_list_navigation' => '专题列表导航',
        'filter_items_list'     => '筛选专题列表',
    );
    
    $args = array(
        'label'                 => '专题',
        'description'           => '专题管理',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'topic'),
    );
    
    register_post_type('topic', $args);
}
add_action('init', 'xman_register_topic_post_type', 0);

/**
 * 注册专题分类法
 */
function xman_register_topic_taxonomy() {
    $labels = array(
        'name'              => '专题分类',
        'singular_name'     => '专题分类',
        'search_items'      => '搜索专题分类',
        'all_items'         => '所有专题分类',
        'parent_item'       => '父级专题分类',
        'parent_item_colon' => '父级专题分类：',
        'edit_item'         => '编辑专题分类',
        'update_item'       => '更新专题分类',
        'add_new_item'      => '添加新专题分类',
        'new_item_name'     => '新专题分类名称',
        'menu_name'         => '专题分类',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'topic'),
        'public'            => true,
        'show_in_rest'      => true,
    );

    register_taxonomy('topic', array('post', 'software'), $args);
}
add_action('init', 'xman_register_topic_taxonomy', 0);

/**
 * 添加专题元框
 */
function xman_add_topic_meta_boxes() {
    add_meta_box(
        'collection_settings',
        '专题设置',
        'xman_topic_settings_callback',
        'topic',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'xman_add_topic_meta_boxes');

/**
 * 专题设置元框回调函数
 */
function xman_topic_settings_callback($post) {
    // 添加nonce字段
    wp_nonce_field('xman_topic_meta_box', 'xman_topic_meta_box_nonce');
    
    // 获取现有值
    $post_ids = get_post_meta($post->ID, '_topic_articles', true);
    $featured_software_id = get_post_meta($post->ID, '_topic_featured_software', true);
    $pinned_article_id = get_post_meta($post->ID, '_topic_pinned_article', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="topic_pinned_article">置顶文章ID</label>
            </th>
            <td>
                <input type="number" id="topic_pinned_article" name="topic_pinned_article" value="<?php echo esc_attr($pinned_article_id); ?>" class="small-text" />
                <p class="description">可选项。输入文章ID，该文章将在专题中置顶显示。</p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="topic_articles">文章列表ID</label>
            </th>
            <td>
                <input type="text" id="topic_articles" name="topic_articles" value="<?php echo esc_attr($post_ids); ?>" class="regular-text" />
                <p class="description">请输入文章ID，用英文逗号分隔，如：1,2,3。文章将按照此顺序显示。</p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="topic_featured_software">置顶软件ID</label>
            </th>
            <td>
                <input type="number" id="topic_featured_software" name="topic_featured_software" value="<?php echo esc_attr($featured_software_id); ?>" class="small-text" />
                <p class="description">可选项。输入软件类型文章的ID，将在专题顶部显示该软件信息。</p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * 保存专题元数据
 */
function xman_save_topic_meta($post_id) {
    // 检查文章类型
    if (get_post_type($post_id) !== 'topic') {
        return;
    }
    
    // 检查nonce
    if (!isset($_POST['xman_topic_meta_box_nonce']) || !wp_verify_nonce($_POST['xman_topic_meta_box_nonce'], 'xman_topic_meta_box')) {
        return;
    }
    
    // 检查用户权限
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // 检查自动保存
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // 保存置顶文章ID
    if (isset($_POST['topic_pinned_article'])) {
        $pinned_article_id = intval($_POST['topic_pinned_article']);
        if ($pinned_article_id > 0) {
            update_post_meta($post_id, '_topic_pinned_article', $pinned_article_id);
        } else {
            delete_post_meta($post_id, '_topic_pinned_article');
        }
    }
    
    // 保存文章ID列表
    if (isset($_POST['topic_articles'])) {
        $post_ids = sanitize_text_field($_POST['topic_articles']);
        update_post_meta($post_id, '_topic_articles', $post_ids);
    }
    
    // 保存置顶软件ID
    if (isset($_POST['topic_featured_software'])) {
        $software_id = intval($_POST['topic_featured_software']);
        if ($software_id > 0) {
            update_post_meta($post_id, '_topic_featured_software', $software_id);
        } else {
            delete_post_meta($post_id, '_topic_featured_software');
        }
    }
}
add_action('save_post', 'xman_save_topic_meta');

/**
 * 获取专题的文章列表（优化版本）
 */
function xman_get_topic_posts($topic_id) {
    return xman_optimized_get_topic_posts($topic_id);
}

/**
 * 获取专题的置顶软件
 */
function xman_get_topic_featured_software($topic_id) {
    $software_id = get_post_meta($topic_id, '_topic_featured_software', true);
    if (empty($software_id)) {
        return null;
    }
    
    $post = get_post($software_id);
    if ($post && $post->post_status === 'publish' && xman_ai_is_software_post($software_id)) {
        return $post;
    }
    
    return null;
}

/**
 * 获取专题的置顶文章
 */
function xman_get_topic_pinned_article($topic_id) {
    $article_id = get_post_meta($topic_id, '_topic_pinned_article', true);
    if (empty($article_id)) {
        return null;
    }
    
    $post = get_post($article_id);
    if ($post && $post->post_status === 'publish') {
        return $post;
    }
    
    return null;
}

/**
 * AJAX 处理函数：获取文章内容
 */
function xman_get_post_content_ajax() {
    // 验证请求
    if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
        wp_send_json_error('Invalid post ID');
        return;
    }
    
    $post_id = intval($_GET['post_id']);
    $post = get_post($post_id);
    
    // 检查文章是否存在且已发布
    if (!$post || $post->post_status !== 'publish') {
        wp_send_json_error('Post not found or not published');
        return;
    }
    
    // 设置全局 $post 变量
    global $post;
    $original_post = $post;
    $post = get_post($post_id);
    setup_postdata($post);
    
    // 获取处理后的文章内容
    $content = apply_filters('the_content', $post->post_content);
    
    // 恢复原始 $post
    $post = $original_post;
    wp_reset_postdata();
    
    // 返回成功响应
    wp_send_json_success(array(
        'content' => $content
    ));
}

/**
 * AJAX 处理函数：获取包含广告位的文章内容（专题页面使用）
 */
function xman_get_topic_post_content_ajax() {
    // 验证请求
    if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
        wp_send_json_error('Invalid post ID');
        return;
    }
    
    $post_id = intval($_GET['post_id']);
    $post = get_post($post_id);
    
    // 检查文章是否存在且已发布
    if (!$post || $post->post_status !== 'publish') {
        wp_send_json_error('Post not found or not published');
        return;
    }
    
    // 设置全局 $post 变量
    global $post;
    $original_post = $post;
    $post = get_post($post_id);
    setup_postdata($post);
    
    // 获取文章基本信息
    $title = get_the_title();
    $date = get_the_date();
    $views = xman_get_post_views($post_id);
    $content = apply_filters('the_content', $post->post_content);
    $is_software = xman_ai_is_software_post($post_id);
    
    // 获取广告位HTML - 直接调用广告显示函数，绕过上下文检查
    ob_start();
    if (function_exists('xman_has_ad') && xman_has_ad(3)) {
        xman_show_ad(3, 'content-ad mb-6');
    }
    $ad_top = ob_get_clean();
    
    ob_start();
    if (function_exists('xman_has_ad') && xman_has_ad(4)) {
        xman_show_ad(4, 'content-ad mt-6');
    }
    $ad_bottom = ob_get_clean();
    
    // 恢复原始 $post
    $post = $original_post;
    wp_reset_postdata();
    
    // 返回成功响应
    wp_send_json_success(array(
        'title' => $title,
        'date' => $date,
        'views' => $views,
        'content' => $content,
        'is_software' => $is_software,
        'ad_top' => $ad_top,
        'ad_bottom' => $ad_bottom
    ));
}

// 注册 AJAX 处理函数（登录和未登录用户都可以访问）
add_action('wp_ajax_get_post_content', 'xman_get_post_content_ajax');
add_action('wp_ajax_nopriv_get_post_content', 'xman_get_post_content_ajax');
add_action('wp_ajax_get_topic_post_content', 'xman_get_topic_post_content_ajax');
add_action('wp_ajax_nopriv_get_topic_post_content', 'xman_get_topic_post_content_ajax');

?>