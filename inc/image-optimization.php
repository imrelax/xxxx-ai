<?php
/**
 * 图片优化模块
 * 优化图片加载和缩略图生成
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 优化的缩略图生成
 */
function xman_optimized_get_thumbnail($post_id = null, $size = 'medium', $class = '') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // 缓存键
    $cache_key = 'xman_thumbnail_' . $post_id . '_' . $size;
    $cached_thumbnail = wp_cache_get($cache_key, 'xman_thumbnails');
    
    if ($cached_thumbnail !== false) {
        return $cached_thumbnail;
    }
    
    $thumbnail_html = '';
    
    // 1. 尝试获取特色图片
    if (has_post_thumbnail($post_id)) {
        $thumbnail_html = get_the_post_thumbnail($post_id, $size, array(
            'class' => 'post-thumbnail lazy-load ' . $class,
            'loading' => 'lazy',
            'data-src' => get_the_post_thumbnail_url($post_id, $size)
        ));
    } else {
        // 2. 尝试从文章内容中获取第一张图片
        $first_image = xman_optimized_get_first_image($post_id);
        if ($first_image) {
            $thumbnail_html = sprintf(
                '<img class="post-thumbnail lazy-load %s" src="data:image/svg+xml,%%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 300 200\'%%3E%%3C/svg%%3E" data-src="%s" alt="%s" loading="lazy">',
                esc_attr($class),
                esc_url($first_image),
                esc_attr(get_the_title($post_id))
            );
        } else {
            // 3. 生成占位图
            $thumbnail_html = xman_optimized_generate_placeholder($post_id, $size, $class);
        }
    }
    
    // 缓存结果
    wp_cache_set($cache_key, $thumbnail_html, 'xman_thumbnails', 3600);
    
    return $thumbnail_html;
}

/**
 * 优化的从内容获取第一张图片
 */
function xman_optimized_get_first_image($post_id) {
    $cache_key = 'xman_first_image_' . $post_id;
    $cached_image = wp_cache_get($cache_key, 'xman_images');
    
    if ($cached_image !== false) {
        return $cached_image;
    }
    
    $post_content = get_post_field('post_content', $post_id);
    $first_image = '';
    
    // 使用正则表达式匹配图片
    if (preg_match('/<img[^>]+src=["\']([^"\'>]+)["\'][^>]*>/i', $post_content, $matches)) {
        $first_image = $matches[1];
        
        // 确保是完整的URL
        if (!filter_var($first_image, FILTER_VALIDATE_URL)) {
            $first_image = home_url($first_image);
        }
    }
    
    // 缓存结果
    wp_cache_set($cache_key, $first_image, 'xman_images', 3600);
    
    return $first_image;
}

/**
 * 优化的占位图生成
 */
function xman_optimized_generate_placeholder($post_id, $size = 'medium', $class = '') {
    $cache_key = 'xman_placeholder_' . $post_id . '_' . $size;
    $cached_placeholder = wp_cache_get($cache_key, 'xman_placeholders');
    
    if ($cached_placeholder !== false) {
        return $cached_placeholder;
    }
    
    // 获取文章标题
    $title = get_the_title($post_id);
    $display_title = wp_trim_words($title, 8); // 限制标题长度
    if (empty($display_title)) {
        $display_title = '暂无标题';
    }
    
    // 生成随机颜色（基于文章ID，确保一致性）
    $colors = xman_optimized_get_random_colors($post_id);
    
    // 根据尺寸设置占位图大小
    $dimensions = xman_get_placeholder_dimensions($size);
    
    // 生成CSS占位图（更兼容的方式）
    // 移除可能冲突的CSS类，只保留基本的定位类
    $safe_class = preg_replace('/\b(w-full|h-full|object-cover)\b/', '', $class);
    $safe_class = trim(preg_replace('/\s+/', ' ', $safe_class));
    
    $placeholder_html = sprintf(
        '<div class="post-thumbnail %s" style="background: linear-gradient(135deg, %s 0%%, %s 100%%) !important; width: 100%% !important; height: 200px !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: %dpx !important; border-radius: 0.5rem; text-align: center !important; line-height: 1.2 !important; padding: 10px !important; color: white !important; font-weight: bold !important; position: relative !important; overflow: hidden !important; object-fit: unset !important;">%s</div>',
        esc_attr($safe_class),
        esc_attr($colors['start']),
        esc_attr($colors['end']),
        min(16, $dimensions['font_size'] / 2), // 调整字体大小以适应完整标题
        esc_html($display_title)
    );
    
    // 缓存结果
    wp_cache_set($cache_key, $placeholder_html, 'xman_placeholders', 3600);
    
    return $placeholder_html;
}

/**
 * 获取占位图尺寸
 */
function xman_get_placeholder_dimensions($size) {
    $dimensions = array(
        'thumbnail' => array('width' => 150, 'height' => 150, 'font_size' => 48),
        'medium' => array('width' => 300, 'height' => 200, 'font_size' => 72),
        'large' => array('width' => 600, 'height' => 400, 'font_size' => 120),
        'full' => array('width' => 800, 'height' => 600, 'font_size' => 150)
    );
    
    return isset($dimensions[$size]) ? $dimensions[$size] : $dimensions['medium'];
}

/**
 * 优化的随机颜色生成
 */
function xman_optimized_get_random_colors($post_id) {
    // 预定义的渐变色组合
    $color_schemes = array(
        array('start' => '#667eea', 'end' => '#764ba2'),
        array('start' => '#f093fb', 'end' => '#f5576c'),
        array('start' => '#4facfe', 'end' => '#00f2fe'),
        array('start' => '#43e97b', 'end' => '#38f9d7'),
        array('start' => '#fa709a', 'end' => '#fee140'),
        array('start' => '#a8edea', 'end' => '#fed6e3'),
        array('start' => '#ffecd2', 'end' => '#fcb69f'),
        array('start' => '#ff8a80', 'end' => '#ffb74d'),
        array('start' => '#81c784', 'end' => '#aed581'),
        array('start' => '#64b5f6', 'end' => '#42a5f5')
    );
    
    // 基于文章ID选择颜色方案，确保一致性
    $index = $post_id % count($color_schemes);
    return $color_schemes[$index];
}

/**
 * 图片懒加载脚本
 */
function xman_add_lazy_load_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 现代浏览器使用 Intersection Observer
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.getAttribute('data-src');
                        if (src) {
                            img.src = src;
                            img.classList.remove('lazy-load');
                            img.classList.add('lazy-loaded');
                            observer.unobserve(img);
                        }
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            // 降级处理：使用滚动事件
            function loadVisibleImages() {
                const images = document.querySelectorAll('img[data-src]');
                images.forEach(img => {
                    const rect = img.getBoundingClientRect();
                    if (rect.top < window.innerHeight + 50) {
                        const src = img.getAttribute('data-src');
                        if (src) {
                            img.src = src;
                            img.classList.remove('lazy-load');
                            img.classList.add('lazy-loaded');
                            img.removeAttribute('data-src');
                        }
                    }
                });
            }
            
            window.addEventListener('scroll', loadVisibleImages);
            window.addEventListener('resize', loadVisibleImages);
            loadVisibleImages(); // 初始加载
        }
    });
    </script>
    <style>
    .lazy-load {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .lazy-loaded {
        opacity: 1;
    }
    .placeholder-image {
        background-color: #f5f5f5;
    }
    </style>
    <?php
}
add_action('wp_head', 'xman_add_lazy_load_script');

/**
 * 优化图片上传处理
 */
function xman_optimize_image_upload($metadata, $attachment_id) {
    if (!isset($metadata['sizes'])) {
        return $metadata;
    }
    
    // 清除相关缓存
    $post_id = wp_get_post_parent_id($attachment_id);
    if ($post_id) {
        wp_cache_delete('xman_thumbnail_' . $post_id . '_medium', 'xman_thumbnails');
        wp_cache_delete('xman_thumbnail_' . $post_id . '_large', 'xman_thumbnails');
        wp_cache_delete('xman_first_image_' . $post_id, 'xman_images');
    }
    
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'xman_optimize_image_upload', 10, 2);

/**
 * 清除文章更新时的图片缓存
 */
function xman_clear_image_cache_on_post_update($post_id) {
    // 清除缩略图缓存
    wp_cache_delete('xman_thumbnail_' . $post_id . '_thumbnail', 'xman_thumbnails');
    wp_cache_delete('xman_thumbnail_' . $post_id . '_medium', 'xman_thumbnails');
    wp_cache_delete('xman_thumbnail_' . $post_id . '_large', 'xman_thumbnails');
    wp_cache_delete('xman_thumbnail_' . $post_id . '_full', 'xman_thumbnails');
    
    // 清除第一张图片缓存
    wp_cache_delete('xman_first_image_' . $post_id, 'xman_images');
    
    // 清除占位图缓存
    wp_cache_delete('xman_placeholder_' . $post_id . '_thumbnail', 'xman_placeholders');
    wp_cache_delete('xman_placeholder_' . $post_id . '_medium', 'xman_placeholders');
    wp_cache_delete('xman_placeholder_' . $post_id . '_large', 'xman_placeholders');
    wp_cache_delete('xman_placeholder_' . $post_id . '_full', 'xman_placeholders');
}
add_action('save_post', 'xman_clear_image_cache_on_post_update');

/**
 * 添加图片尺寸优化
 */
function xman_add_custom_image_sizes() {
    // 添加自定义图片尺寸
    add_image_size('xman-thumbnail', 300, 200, true);
    add_image_size('xman-medium', 600, 400, true);
    add_image_size('xman-large', 1200, 800, true);
}
add_action('after_setup_theme', 'xman_add_custom_image_sizes');

/**
 * 禁用不必要的图片尺寸生成
 */
function xman_disable_unused_image_sizes($sizes) {
    // 移除不常用的图片尺寸以节省存储空间
    unset($sizes['medium_large']); // 768px
    unset($sizes['1536x1536']);    // 1536px
    unset($sizes['2048x2048']);    // 2048px
    
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'xman_disable_unused_image_sizes');