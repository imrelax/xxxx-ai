<?php
/**
 * 主题工具函数
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
 * 获取主题自定义设置
 */
function xman_get_option($option_name, $default = '') {
    return get_option($option_name, $default);
}

/**
 * 获取站长信息（使用配置管理模块）
 */
function xman_get_author_info($field = '') {
    $author_info = xman_get_author_config();
    
    if ($field && isset($author_info[$field])) {
        return $author_info[$field];
    }
    
    return $author_info;
}

/**
 * 获取网站Logo
 */
function xman_get_logo() {
    $logo = get_option('xman_site_logo');
    $site_name = get_option('xman_site_name', get_bloginfo('name'));
    if ($logo) {
        return '<img src="' . esc_url($logo) . '" alt="' . esc_attr($site_name) . '" class="h-8">';
    }
    return '<span class="text-xl font-bold">' . esc_html($site_name) . '</span>';
}

// 搜索框占位符和页脚信息函数已移至 theme-config.php 中的配置管理模块

/**
 * 格式化文章发布时间
 */
function xman_time_ago($time) {
    $time_diff = time() - $time;
    
    if ($time_diff < 60) {
        return '刚刚';
    } elseif ($time_diff < 3600) {
        return floor($time_diff / 60) . '分钟前';
    } elseif ($time_diff < 86400) {
        return floor($time_diff / 3600) . '小时前';
    } elseif ($time_diff < 2592000) {
        return floor($time_diff / 86400) . '天前';
    } else {
        return date('Y-m-d', $time);
    }
}

/**
 * 获取文章阅读时间估算
 */
function xman_get_reading_time($content) {
    $word_count = str_word_count(strip_tags($content));
    $chinese_count = preg_match_all('/[\x{4e00}-\x{9fff}]/u', $content);
    
    // 中文按字符计算，英文按单词计算
    $total_words = $word_count + $chinese_count;
    $reading_time = ceil($total_words / 200); // 假设每分钟阅读200字
    
    return max(1, $reading_time); // 最少1分钟
}

/**
 * 获取文章标签HTML
 */
function xman_get_post_tags($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $tags = get_the_tags($post_id);
    if (!$tags) {
        return '';
    }
    
    $tag_html = '';
    foreach ($tags as $tag) {
        $tag_html .= '<a href="' . get_tag_link($tag->term_id) . '" class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-2 mb-1 hover:bg-blue-200 transition-colors">' . esc_html($tag->name) . '</a>';
    }
    
    return $tag_html;
}

/**
 * 获取文章分类HTML
 */
function xman_get_post_categories($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    if (!$categories) {
        return '';
    }
    
    $cat_html = '';
    foreach ($categories as $category) {
        $cat_html .= '<a href="' . get_category_link($category->term_id) . '" class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mr-2 mb-1 hover:bg-green-200 transition-colors">' . esc_html($category->name) . '</a>';
    }
    
    return $cat_html;
}

/**
 * 获取社交分享链接
 */
function xman_get_social_share_links($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_url = get_permalink($post_id);
    $post_title = get_the_title($post_id);
    
    $shares = array(
        'weibo' => array(
            'url' => 'https://service.weibo.com/share/share.php?url=' . urlencode($post_url) . '&title=' . urlencode($post_title),
            'icon' => 'fab fa-weibo',
            'name' => '微博'
        ),
        'wechat' => array(
            'url' => 'javascript:void(0)',
            'icon' => 'fab fa-weixin',
            'name' => '微信',
            'onclick' => 'xman_show_qr_code("' . $post_url . '")'
        ),
        'qq' => array(
            'url' => 'https://connect.qq.com/widget/shareqq/index.html?url=' . urlencode($post_url) . '&title=' . urlencode($post_title),
            'icon' => 'fab fa-qq',
            'name' => 'QQ'
        )
    );
    
    return $shares;
}

/**
 * 生成二维码URL
 */
function xman_get_qr_code_url($text, $size = 200) {
    return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($text);
}

/**
 * 检查是否为移动设备
 */
function xman_is_mobile() {
    return wp_is_mobile();
}

/**
 * 获取文章摘要（支持自动截取）
 */
function xman_get_excerpt($post_id = null, $length = 150) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    if (!$post) {
        return '';
    }
    
    // 如果有手动摘要，优先使用
    if ($post->post_excerpt) {
        return wp_trim_words($post->post_excerpt, $length, '...');
    }
    
    // 否则从内容中截取
    $content = strip_tags($post->post_content);
    return wp_trim_words($content, $length, '...');
}

/**
 * 获取文章第一张图片
 */
function xman_get_first_image($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    if (!$post) {
        return '';
    }
    
    // 先检查特色图像
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, 'medium');
    }
    
    // 从内容中提取第一张图片
    preg_match('/<img[^>]+src=["\']([^"\'>]+)["\'][^>]*>/i', $post->post_content, $matches);
    
    if (!empty($matches[1])) {
        return $matches[1];
    }
    
    return '';
}

/**
 * 安全输出HTML
 */
function xman_kses($content, $allowed_tags = null) {
    if ($allowed_tags === null) {
        $allowed_tags = array(
            'a' => array('href' => array(), 'title' => array(), 'class' => array()),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
            'span' => array('class' => array()),
            'div' => array('class' => array()),
            'p' => array('class' => array()),
            'img' => array('src' => array(), 'alt' => array(), 'class' => array()),
        );
    }
    
    return wp_kses($content, $allowed_tags);
}