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
 * 文章浏览量统计（优化版本）
 */
function xman_set_post_views($postID) {
    xman_optimized_set_post_views($postID);
}

/**
 * 获取文章浏览量（优化版本）
 */
function xman_get_post_views($postID) {
    return xman_optimized_get_post_views($postID);
}

/**
 * 获取随机缩略图颜色
 */
function xman_get_random_thumbnail_color($post_id = null) {
    $colors = array(
        '#E74C3C', // 深红色
        '#3498DB', // 深蓝色
        '#2ECC71', // 深绿色
        '#9B59B6', // 深紫色
        '#F39C12', // 深橙色
        '#1ABC9C', // 深青色
        '#34495E', // 深灰蓝
        '#E67E22', // 深橘色
        '#8E44AD', // 深紫罗兰
        '#2980B9', // 深天蓝
        '#27AE60', // 深翠绿
        '#D35400', // 深橙红
        '#7F8C8D', // 深灰色
        '#C0392B', // 深砖红
        '#16A085', // 深海绿
        '#2C3E50', // 深蓝灰
        '#8E44AD', // 深紫色
        '#D68910', // 深金色
        '#922B21', // 深酒红
        '#1B4F72'  // 深海蓝
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
 * 从文章内容中提取第一张图片URL
 */
function xman_extract_first_image_from_content($post_id) {
    $post = get_post($post_id);
    if (!$post || !$post->post_content) {
        return false;
    }
    
    // 匹配img标签中的src属性
    preg_match('/<img[^>]+src=["\']([^"\'>]+)["\'][^>]*>/i', $post->post_content, $matches);
    if (!empty($matches[1])) {
        $image_url = $matches[1];
        // 确保是完整的URL
        if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
            // 如果是相对路径，转换为绝对路径
            if (strpos($image_url, '/') === 0) {
                $image_url = home_url($image_url);
            } else {
                $image_url = home_url('/' . $image_url);
            }
        }
        return $image_url;
    }
    
    return false;
}

/**
 * 获取幻灯片图片（优化版本）
 */
function xman_get_slider_image($post_id, $size = 'hero-slider') {
    if (has_post_thumbnail($post_id)) {
        // 生成响应式图片
        $thumbnail_id = get_post_thumbnail_id($post_id);
        $img_src = wp_get_attachment_image_src($thumbnail_id, $size);
        $img_src_large = wp_get_attachment_image_src($thumbnail_id, 'hero-slider-large');
        
        $srcset = '';
        if ($img_src && $img_src_large) {
            $srcset = sprintf('srcset="%s 1200w, %s 1600w" sizes="(max-width: 1200px) 100vw, 1200px"', 
                esc_url($img_src[0]), esc_url($img_src_large[0]));
        }
        
        return sprintf('<img src="%s" %s class="w-full h-full object-cover transition-transform duration-700 hover:scale-105" alt="%s" loading="lazy" onerror="this.style.display=\'none\'; this.parentNode.style.background=\'linear-gradient(135deg, #667eea 0%%, #764ba2 100%%)\'">',
            esc_url($img_src[0]),
            $srcset,
            esc_attr(get_the_title($post_id))
        );
    } else {
        // 尝试从文章内容中获取第一张图片
        $first_image = xman_extract_first_image_from_content($post_id);
        
        if ($first_image) {
            return '<img src="' . esc_url($first_image) . '" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105" alt="' . esc_attr(get_the_title($post_id)) . '" loading="lazy" onerror="this.style.display=\'none\'; this.parentNode.style.background=\'linear-gradient(135deg, #667eea 0%%, #764ba2 100%%)\'">'; 
        }
        
        // 使用默认渐变背景（不显示文字，避免与幻灯片文字冲突）
        $color = xman_get_random_thumbnail_color($post_id);
        $gradient_color = xman_adjust_brightness($color, -20);
        
        return sprintf(
            '<div class="w-full h-full" style="background: linear-gradient(135deg, %s 0%%, %s 100%%); position: relative;"></div>',
            esc_attr($color),
            esc_attr($gradient_color)
        );
    }
}

/**
 * 获取文章缩略图（优化版本）
 */
function xman_get_post_thumbnail($post_id = null, $size = 'medium', $class = '') {
    return xman_optimized_get_thumbnail($post_id, $size, $class);
}

/**
 * 获取主题自定义设置
 */
/**
 * 面包屑导航（使用优化版本）
 */
function xman_breadcrumb() {
    xman_optimized_breadcrumb();
}

/**
 * 获取AI主题设置中的幻灯片文章ID
 */
function xman_ai_get_slide_post_ids() {
    $slide_posts = get_option('xman_slide_post_ids', '');
    if (!empty($slide_posts)) {
        // 将逗号分隔的ID字符串转换为数组
        $ids = explode(',', $slide_posts);
        $ids = array_map('trim', $ids);
        $ids = array_filter($ids, 'is_numeric');
        $ids = array_map('intval', $ids);
        return $ids;
    }
    
    // 如果没有指定文章ID，则获取置顶文章
    $sticky_posts = get_option('sticky_posts');
    if (!empty($sticky_posts)) {
        return array_map('intval', $sticky_posts);
    }
    
    return array();
}

/**
 * 获取幻灯片文章（优化版本）
 */
function xman_get_slide_posts($limit = 5) {
    return xman_optimized_get_slide_posts($limit);
}

/**
 * 获取相关文章（优化版本）
 */
function xman_get_related_posts($post_id, $number = 4) {
    $posts = xman_optimized_get_related_posts($post_id, $number);
    
    // 为了保持兼容性，返回WP_Query对象
    $query = new WP_Query();
    $query->posts = $posts;
    $query->post_count = count($posts);
    $query->found_posts = count($posts);
    $query->max_num_pages = 1;
    
    return $query;
}

/**
 * 获取评论楼层号
 */
function xman_get_comment_floor_number($comment) {
    global $wp_query;
    
    // 获取当前文章的所有已批准评论，按时间排序（包括AI评论和普通评论）
    $comments = get_comments(array(
        'post_id' => $comment->comment_post_ID,
        'status' => 'approve',
        'orderby' => 'comment_date',
        'order' => 'ASC',
        'type' => array('comment', 'ai_comment') // 包括普通评论和AI评论
    ));
    
    $floor_number = 1;
    foreach ($comments as $c) {
        if ($c->comment_ID == $comment->comment_ID) {
            return $floor_number;
        }
        $floor_number++;
    }
    
    return $floor_number;
}

/**
 * 自定义评论列表显示
 */
function xman_comment_list($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);
    
    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag; ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID(); ?>" class="comment-body bg-gradient-to-br from-white to-gray-50 rounded-2xl border border-gray-200 p-4 mb-4 shadow-md hover:shadow-lg transition-all duration-300 hover:border-blue-200">
    <?php endif; ?>
    
    <div class="comment-author vcard">
        <div class="comment-meta commentmetadata mb-2">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <cite class="fn font-semibold text-gray-900 text-lg flex items-center">
                    <?php 
                    // 使用优化后的头像生成功能，缩小30%
                    if ($args['avatar_size'] != 0) {
                        $smaller_size = round($args['avatar_size'] * 0.7); // 缩小30%
                        echo xman_optimized_comment_avatar($comment, $smaller_size);
                    }
                    ?>
                    <span class="ml-3"><?php echo get_comment_author_link(); ?></span>
                </cite>
                <span class="text-gray-500 text-sm flex items-center">
                    <i class="fas fa-clock text-gray-400 mr-1"></i>
                    <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>" class="hover:text-blue-600 transition-colors">
                        <?php printf('%1$s %2$s', get_comment_date(), get_comment_time()); ?>
                    </a>
                    <?php edit_comment_link('<i class="fas fa-edit ml-2"></i>编辑', ' | ', ''); ?>
                </span>
            </div>
        </div>
    </div>
    
    <?php if ($comment->comment_approved == '0') : ?>
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 px-4 py-3 rounded-lg mb-4">
            <div class="flex items-center">
                <i class="fas fa-hourglass-half text-yellow-600 mr-2"></i>
                <em class="text-yellow-700 font-medium">您的评论正在等待审核</em>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="comment-content text-gray-700 leading-relaxed text-base mb-3 prose prose-sm max-w-none">
        <?php comment_text(); ?>
    </div>
    
    <div class="reply flex items-center justify-between pt-2 border-t border-gray-100">
        <div class="flex items-center space-x-4">
            <?php comment_reply_link(array_merge($args, array(
                'add_below' => $add_below, 
                'depth' => $depth, 
                'max_depth' => $args['max_depth'],
                'reply_text' => '<i class="fas fa-reply mr-1"></i>回复',
                'class' => 'inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md'
            ))); ?>
        </div>
        <div class="flex items-center text-gray-400 text-sm">
            <i class="fas fa-hashtag mr-1"></i>
            <span><?php echo xman_get_comment_floor_number($comment); ?></span>
        </div>
    </div>
    
    <?php if ('div' != $args['style']) : ?>
        </div>
    <?php endif; ?>
    <?php
}