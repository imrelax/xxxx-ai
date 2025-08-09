<?php
/**
 * 主题功能函数
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
 * 文章浏览量统计
 */
function xman_set_post_views($postID) {
    $count_key = 'post_views';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

/**
 * 获取文章浏览量
 */
function xman_get_post_views($postID) {
    $count_key = 'post_views';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return '0';
    }
    return $count;
}

/**
 * 获取随机缩略图颜色
 */
function xman_get_random_thumbnail_color($post_id = null) {
    $colors = array(
        '#FF6B6B', // 珊瑚红
        '#4ECDC4', // 青绿色
        '#45B7D1', // 天蓝色
        '#96CEB4', // 薄荷绿
        '#FFEAA7', // 柠檬黄
        '#DDA0DD', // 梅花紫
        '#98D8C8', // 薄荷蓝
        '#F7DC6F', // 香槟金
        '#BB8FCE', // 淡紫色
        '#85C1E9', // 浅蓝色
        '#F8C471', // 桃橙色
        '#82E0AA', // 浅绿色
        '#F1948A', // 浅红色
        '#85C1E9', // 天空蓝
        '#D7BDE2', // 薰衣草紫
        '#A9DFBF', // 浅薄荷绿
        '#F9E79F', // 浅黄色
        '#AED6F1', // 粉蓝色
        '#FADBD8', // 粉红色
        '#D5DBDB'  // 浅灰色
    );
    
    if ($post_id) {
        // 基于文章ID生成固定颜色，确保同一文章总是显示相同颜色
        $index = $post_id % count($colors);
        return $colors[$index];
    }
    
    return $colors[array_rand($colors)];
}

/**
 * 调整颜色亮度
 */
function xman_adjust_brightness($hex, $percent) {
    // 移除 # 号
    $hex = str_replace('#', '', $hex);
    
    // 转换为 RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // 调整亮度
    $r = max(0, min(255, $r + ($r * $percent / 100)));
    $g = max(0, min(255, $g + ($g * $percent / 100)));
    $b = max(0, min(255, $b + ($b * $percent / 100)));
    
    // 转换回十六进制
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

/**
 * 获取幻灯片图片
 */
function xman_get_slider_image($post_id, $size = 'hero-slider') {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, array('class' => 'w-full h-full object-cover'));
    } else {
        // 尝试从文章内容中获取第一张图片
        $content = get_post_field('post_content', $post_id);
        preg_match('/<img[^>]+src="([^"]+)"[^>]*>/i', $content, $matches);
        
        if (!empty($matches[1])) {
            return '<img src="' . esc_url($matches[1]) . '" class="w-full h-full object-cover" alt="' . esc_attr(get_the_title($post_id)) . '">';
        } else {
            // 使用默认占位图
            $color = xman_get_random_thumbnail_color($post_id);
            $title = get_the_title($post_id);
            
            // 生成渐变色
            $base_color = $color;
            $gradient_color = xman_adjust_brightness($color, 25);
            
            // 获取文章的标签并随机选择一个
            $post_tags = get_the_tags($post_id);
            $display_text = '';
            
            if ($post_tags && !is_wp_error($post_tags)) {
                // 随机选择一个标签
                $random_tag = $post_tags[array_rand($post_tags)];
                $display_text = $random_tag->name;
            } else {
                // 如果没有标签，使用文章标题的前两个字符
                $display_text = mb_substr($title, 0, 2, 'UTF-8');
                if (empty($display_text)) {
                    $display_text = '文章';
                }
            }
            
            return sprintf(
                '<div class="w-full h-full flex items-center justify-center text-white text-6xl font-bold" style="background: linear-gradient(135deg, %s 0%%, %s 100%%); border-radius: 12px;"><span>%s</span></div>',
                esc_attr($base_color),
                esc_attr($gradient_color),
                esc_html($display_text)
            );
        }
    }
}

/**
 * 获取文章缩略图
 */
function xman_get_post_thumbnail($post_id, $size = 'medium', $class = '') {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, array('class' => $class));
    } else {
        // 尝试从文章内容中获取第一张图片
        $content = get_post_field('post_content', $post_id);
        preg_match('/<img[^>]+src="([^"]+)"[^>]*>/i', $content, $matches);
        
        if (!empty($matches[1])) {
            // 为从内容中提取的图片添加严格的尺寸控制
            $style_attr = '';
            if ($size === 'medium') {
                $style_attr = 'style="max-width: 400px; max-height: 230px; width: 100%; height: 100%; object-fit: cover;"';
            } elseif ($size === 'thumbnail') {
                $style_attr = 'style="max-width: 150px; max-height: 150px; width: 100%; height: 100%; object-fit: cover;"';
            } elseif ($size === 'hero-slider') {
                $style_attr = 'style="max-width: 800px; max-height: 400px; width: 100%; height: 100%; object-fit: cover;"';
            } else {
                $style_attr = 'style="max-width: 300px; max-height: 200px; width: 100%; height: 100%; object-fit: cover;"';
            }
            return '<img src="' . esc_url($matches[1]) . '" class="' . esc_attr($class) . '" ' . $style_attr . ' alt="' . esc_attr(get_the_title($post_id)) . '">';
        } else {
            // 使用默认占位图
            $color = xman_get_random_thumbnail_color($post_id);
            $title = get_the_title($post_id);
            
            // 生成渐变色
            $base_color = $color;
            $gradient_color = xman_adjust_brightness($color, 25);
            
            // 获取文章的标签并随机选择一个
            $post_tags = get_the_tags($post_id);
            $display_text = '';
            
            if ($post_tags && !is_wp_error($post_tags)) {
                // 随机选择一个标签
                $random_tag = $post_tags[array_rand($post_tags)];
                $display_text = $random_tag->name;
            } else {
                // 如果没有标签，使用文章标题的前两个字符
                $display_text = mb_substr($title, 0, 2, 'UTF-8');
                if (empty($display_text)) {
                    $display_text = '文章';
                }
            }
            
            $dimensions = '';
            $style_dimensions = '';
            $font_size = '18px';
            if ($size === 'thumbnail') {
                $dimensions = 'width="150" height="150"';
                $style_dimensions = 'width:150px;height:150px;';
                $font_size = '36px';
            } elseif ($size === 'medium') {
                $dimensions = 'width="400" height="230"';
                $style_dimensions = 'width:100%;height:100%;';
                $font_size = '48px';
            } elseif ($size === 'hero-slider') {
                $style_dimensions = 'width:100%;height:100%;';
                $font_size = '96px';
            } else {
                $style_dimensions = 'width:150px;height:150px;';
                $font_size = '36px';
            }
            
            return sprintf(
                '<div class="post-thumbnail no-image %s" style="background: linear-gradient(135deg, %s 0%%, %s 100%%); %s border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: %s; font-weight: 600; text-align: center; padding: 10px; box-sizing: border-box;"><span class="thumbnail-text">%s</span></div>',
                esc_attr($class),
                esc_attr($base_color),
                esc_attr($gradient_color),
                $style_dimensions,
                $font_size,
                esc_html($display_text)
            );
        }
    }
}

/**
 * 获取主题自定义设置
 */
/**
 * 面包屑导航
 */
function xman_breadcrumb() {
    if (!is_home()) {
        echo '<nav class="breadcrumb mb-6 bg-white rounded-lg shadow-sm border border-gray-100 px-4 py-3">';
        echo '<ol class="flex items-center space-x-2 text-sm text-gray-600">';
        echo '<li><a href="' . home_url() . '" class="flex items-center hover:text-blue-600 transition-colors"><i class="fas fa-home mr-1"></i>首页</a></li>';
        
        if (is_category()) {
            echo '<li><i class="fas fa-chevron-right mx-2 text-gray-400"></i></li>';
            echo '<li class="flex items-center text-gray-900 font-medium"><i class="fas fa-folder mr-1 text-blue-500"></i>' . single_cat_title('', false) . '</li>';
        } elseif (is_tag()) {
            echo '<li><i class="fas fa-chevron-right mx-2 text-gray-400"></i></li>';
            echo '<li class="flex items-center text-gray-900 font-medium"><i class="fas fa-tag mr-1 text-purple-500"></i>' . single_tag_title('', false) . '</li>';
        } elseif (is_single()) {
            echo '<li><i class="fas fa-chevron-right mx-2 text-gray-400"></i></li>';
            $categories = get_the_category();
            if (!empty($categories)) {
                echo '<li><a href="' . get_category_link($categories[0]->term_id) . '" class="flex items-center hover:text-blue-600 transition-colors"><i class="fas fa-folder mr-1 text-blue-500"></i>' . esc_html($categories[0]->name) . '</a></li>';
                echo '<li><i class="fas fa-chevron-right mx-2 text-gray-400"></i></li>';
            }
            echo '<li class="flex items-center text-gray-900 font-medium"><i class="fas fa-file-alt mr-1 text-green-500"></i>' . wp_trim_words(get_the_title(), 30, '...') . '</li>';
        } elseif (is_page()) {
            echo '<li><i class="fas fa-chevron-right mx-2 text-gray-400"></i></li>';
            echo '<li class="flex items-center text-gray-900 font-medium"><i class="fas fa-file mr-1 text-green-500"></i>' . get_the_title() . '</li>';
        } elseif (is_search()) {
            echo '<li><i class="fas fa-chevron-right mx-2 text-gray-400"></i></li>';
            echo '<li class="flex items-center text-gray-900 font-medium"><i class="fas fa-search mr-1 text-orange-500"></i>搜索结果</li>';
        } elseif (is_archive()) {
            echo '<li><i class="fas fa-chevron-right mx-2 text-gray-400"></i></li>';
            echo '<li class="flex items-center text-gray-900 font-medium"><i class="fas fa-archive mr-1 text-indigo-500"></i>归档</li>';
        }
        
        echo '</ol>';
        echo '</nav>';
    }
}

/**
 * 获取AI主题设置中的幻灯片文章ID
 */
function xman_ai_get_slide_post_ids() {
    $slide_posts = get_option('xman_ai_slide_posts', '');
    if (empty($slide_posts)) {
        return array();
    }
    
    // 将逗号分隔的ID字符串转换为数组
    $ids = explode(',', $slide_posts);
    $ids = array_map('trim', $ids);
    $ids = array_filter($ids, 'is_numeric');
    $ids = array_map('intval', $ids);
    
    return $ids;
}

/**
 * 获取幻灯片文章
 */
function xman_get_slide_posts($limit = 5) {
    // 获取指定的幻灯片文章ID
    $slide_ids = xman_ai_get_slide_post_ids();
    
    if (!empty($slide_ids)) {
        // 如果设置了指定ID，则获取这些文章
        $args = array(
            'post_type' => 'post',
            'post__in' => $slide_ids,
            'orderby' => 'post__in',
            'posts_per_page' => $limit,
            'post_status' => 'publish'
        );
    } else {
        // 如果没有设置指定ID，则按浏览量获取热门文章
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $limit,
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'post_status' => 'publish'
        );
    }
    
    return get_posts($args);
}

/**
 * 获取相关文章
 */
function xman_get_related_posts($post_id, $number = 4) {
    $categories = wp_get_post_categories($post_id);
    $tags = wp_get_post_tags($post_id);
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $number,
        'post__not_in' => array($post_id),
        'orderby' => 'rand',
        'meta_query' => array(
            'relation' => 'OR',
        )
    );
    
    if (!empty($categories)) {
        $args['category__in'] = $categories;
    }
    
    if (!empty($tags)) {
        $tag_ids = array();
        foreach ($tags as $tag) {
            $tag_ids[] = $tag->term_id;
        }
        $args['tag__in'] = $tag_ids;
    }
    
    return new WP_Query($args);
}