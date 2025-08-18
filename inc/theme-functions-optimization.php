<?php
/**
 * 主题函数优化模块
 * 简化复杂的函数逻辑，减少不必要的计算
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 优化的面包屑导航
 */
function xman_optimized_breadcrumb() {
    if (is_home()) {
        return;
    }
    
    // 缓存面包屑结构
    $cache_key = 'xman_breadcrumb_' . get_queried_object_id() . '_' . (is_single() ? 'single' : (is_category() ? 'category' : (is_tag() ? 'tag' : (is_page() ? 'page' : 'other'))));
    $cached_breadcrumb = wp_cache_get($cache_key, 'xman_breadcrumbs');
    
    if ($cached_breadcrumb !== false) {
        echo $cached_breadcrumb;
        return;
    }
    
    $breadcrumb_items = array();
    
    // 首页链接
    $breadcrumb_items[] = array(
        'url' => home_url(),
        'text' => '首页',
        'icon' => 'fas fa-home'
    );
    
    // 根据页面类型添加面包屑项
    if (is_category()) {
        $breadcrumb_items[] = array(
            'text' => single_cat_title('', false),
            'icon' => 'fas fa-folder',
            'color' => 'text-blue-500'
        );
    } elseif (is_tag()) {
        $breadcrumb_items[] = array(
            'text' => single_tag_title('', false),
            'icon' => 'fas fa-tag',
            'color' => 'text-purple-500'
        );
    } elseif (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $breadcrumb_items[] = array(
                'url' => get_category_link($categories[0]->term_id),
                'text' => esc_html($categories[0]->name),
                'icon' => 'fas fa-folder',
                'color' => 'text-blue-500'
            );
        }
        $breadcrumb_items[] = array(
            'text' => wp_trim_words(get_the_title(), 30, '...'),
            'icon' => 'fas fa-file-alt',
            'color' => 'text-green-500'
        );
    } elseif (is_page()) {
        $page_info = xman_get_page_breadcrumb_info();
        $breadcrumb_items[] = array(
            'text' => get_the_title(),
            'icon' => $page_info['icon'],
            'color' => $page_info['color']
        );
    } elseif (is_search()) {
        $breadcrumb_items[] = array(
            'text' => '搜索结果',
            'icon' => 'fas fa-search',
            'color' => 'text-orange-500'
        );
    } elseif (is_archive()) {
        $breadcrumb_items[] = array(
            'text' => '归档',
            'icon' => 'fas fa-archive',
            'color' => 'text-indigo-500'
        );
    }
    
    // 生成HTML
    $breadcrumb_html = xman_render_breadcrumb($breadcrumb_items);
    
    // 缓存结果
    wp_cache_set($cache_key, $breadcrumb_html, 'xman_breadcrumbs', 1800);
    
    echo $breadcrumb_html;
}

/**
 * 获取页面面包屑信息
 */
function xman_get_page_breadcrumb_info() {
    static $page_icons = null;
    
    if ($page_icons === null) {
        $page_icons = array(
            'collections' => array('icon' => 'fas fa-layer-group', 'color' => 'text-purple-500'),
            'topics' => array('icon' => 'fas fa-layer-group', 'color' => 'text-purple-500'),
            'software' => array('icon' => 'fas fa-laptop-code', 'color' => 'text-blue-500'),
            'default' => array('icon' => 'fas fa-file', 'color' => 'text-green-500')
        );
    }
    
    $page_slug = get_post_field('post_name');
    $page_template = get_page_template_slug();
    
    // 检查页面模板
    if (strpos($page_template, 'page-collections.php') !== false || $page_slug === 'collections') {
        return $page_icons['collections'];
    } elseif (strpos($page_template, 'page-topics.php') !== false || $page_slug === 'topics') {
        return $page_icons['topics'];
    } elseif (strpos($page_template, 'page-software.php') !== false || $page_slug === 'software') {
        return $page_icons['software'];
    }
    
    return $page_icons['default'];
}

// 面包屑渲染函数已移至 theme-components.php 中的组件模块

/**
 * 优化的评论头像生成
 */
function xman_optimized_comment_avatar($comment, $size = 40) {
    $cache_key = 'xman_comment_avatar_' . $comment->comment_ID . '_' . $size;
    $cached_avatar = wp_cache_get($cache_key, 'xman_avatars');
    
    if ($cached_avatar !== false) {
        return $cached_avatar;
    }
    
    $avatar = get_avatar($comment, $size, '', '', array('class' => 'rounded-full flex-shrink-0 ring-2 ring-blue-100 shadow-sm'));
    
    // 如果没有Gravatar头像，生成自定义头像
    if (strpos($avatar, 'gravatar.com') === false || strpos($avatar, 'd=mm') !== false) {
        $author_name = get_comment_author($comment);
        $initial = mb_substr($author_name, 0, 1, 'UTF-8');
        
        $gradient_class = xman_get_comment_gradient_class($comment->comment_ID);
        
        // 映射尺寸到有效的Tailwind类名
        $size_map = array(
            40 => 'w-10 h-10',
            50 => 'w-12 h-12', 
            60 => 'w-16 h-16',
            80 => 'w-20 h-20',
            100 => 'w-24 h-24'
        );
        
        // 找到最接近的尺寸
        $tailwind_size = 'w-10 h-10'; // 默认值
        foreach ($size_map as $map_size => $class) {
            if ($size <= $map_size) {
                $tailwind_size = $class;
                break;
            }
        }
        
        $avatar = sprintf(
            '<div class="%s rounded-full flex items-center justify-center text-white font-bold text-lg %s ring-2 ring-blue-100 shadow-sm">%s</div>',
            $tailwind_size,
            $gradient_class,
            esc_html($initial)
        );
    }
    
    // 缓存结果
    wp_cache_set($cache_key, $avatar, 'xman_avatars', 3600);
    
    return $avatar;
}

/**
 * 获取评论渐变类名
 */
function xman_get_comment_gradient_class($comment_id) {
    static $gradients = null;
    
    if ($gradients === null) {
        $gradients = array(
            'bg-gradient-to-br from-red-400 to-red-600',
            'bg-gradient-to-br from-blue-400 to-blue-600',
            'bg-gradient-to-br from-green-400 to-green-600',
            'bg-gradient-to-br from-purple-400 to-purple-600',
            'bg-gradient-to-br from-pink-400 to-pink-600',
            'bg-gradient-to-br from-indigo-400 to-indigo-600',
            'bg-gradient-to-br from-yellow-400 to-yellow-600',
            'bg-gradient-to-br from-teal-400 to-teal-600',
            'bg-gradient-to-br from-orange-400 to-orange-600',
            'bg-gradient-to-br from-cyan-400 to-cyan-600'
        );
    }
    
    return $gradients[$comment_id % count($gradients)];
}

/**
 * 优化的随机颜色生成（替换原有函数）
 */
function xman_optimized_get_random_thumbnail_color($post_id) {
    static $colors = null;
    
    if ($colors === null) {
        $colors = array(
            '#667eea', '#764ba2', '#f093fb', '#f5576c',
            '#4facfe', '#00f2fe', '#43e97b', '#38f9d7',
            '#fa709a', '#fee140', '#a8edea', '#fed6e3',
            '#ffecd2', '#fcb69f', '#ff8a80', '#ffb74d'
        );
    }
    
    return $colors[$post_id % count($colors)];
}

/**
 * 优化的亮度调整函数
 */
function xman_optimized_adjust_brightness($hex, $percent) {
    // 简化的亮度调整，使用预计算的值
    static $brightness_cache = array();
    
    $cache_key = $hex . '_' . $percent;
    if (isset($brightness_cache[$cache_key])) {
        return $brightness_cache[$cache_key];
    }
    
    // 移除#号
    $hex = str_replace('#', '', $hex);
    
    // 转换为RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // 调整亮度
    $r = max(0, min(255, $r + ($r * $percent / 100)));
    $g = max(0, min(255, $g + ($g * $percent / 100)));
    $b = max(0, min(255, $b + ($b * $percent / 100)));
    
    // 转换回十六进制
    $result = '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT) . 
                    str_pad(dechex($g), 2, '0', STR_PAD_LEFT) . 
                    str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    
    $brightness_cache[$cache_key] = $result;
    
    return $result;
}

/**
 * 清除面包屑缓存
 */
function xman_clear_breadcrumb_cache($post_id) {
    // 清除相关的面包屑缓存
    wp_cache_delete('xman_breadcrumb_' . $post_id . '_single', 'xman_breadcrumbs');
    wp_cache_delete('xman_breadcrumb_' . $post_id . '_page', 'xman_breadcrumbs');
    
    // 如果是分类或标签页面，也清除相关缓存
    $post = get_post($post_id);
    if ($post) {
        $categories = get_the_category($post_id);
        foreach ($categories as $category) {
            wp_cache_delete('xman_breadcrumb_' . $category->term_id . '_category', 'xman_breadcrumbs');
        }
        
        $tags = get_the_tags($post_id);
        if ($tags) {
            foreach ($tags as $tag) {
                wp_cache_delete('xman_breadcrumb_' . $tag->term_id . '_tag', 'xman_breadcrumbs');
            }
        }
    }
}
add_action('save_post', 'xman_clear_breadcrumb_cache');
add_action('delete_post', 'xman_clear_breadcrumb_cache');

/**
 * 清除评论头像缓存
 */
function xman_clear_comment_avatar_cache($comment_id) {
    wp_cache_delete('xman_comment_avatar_' . $comment_id . '_40', 'xman_avatars');
    wp_cache_delete('xman_comment_avatar_' . $comment_id . '_32', 'xman_avatars');
    wp_cache_delete('xman_comment_avatar_' . $comment_id . '_64', 'xman_avatars');
}
add_action('wp_insert_comment', 'xman_clear_comment_avatar_cache');
add_action('edit_comment', 'xman_clear_comment_avatar_cache');
add_action('delete_comment', 'xman_clear_comment_avatar_cache');

/**
 * 优化WordPress查询
 */
function xman_optimize_wordpress_queries() {
    // 移除不必要的查询
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    
    // 禁用自动保存
    add_action('wp_print_scripts', function() {
        wp_deregister_script('autosave');
    });
    
    // 限制文章修订版本
    if (!defined('WP_POST_REVISIONS')) {
        define('WP_POST_REVISIONS', 3);
    }
}
add_action('init', 'xman_optimize_wordpress_queries');

/**
 * 数据库查询优化
 */
function xman_optimize_database_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        // 在首页限制查询字段
        if ($query->is_home()) {
            $query->set('no_found_rows', true);
        }
        
        // 在归档页面优化查询
        if ($query->is_archive()) {
            $query->set('no_found_rows', true);
        }
    }
}
add_action('pre_get_posts', 'xman_optimize_database_queries');