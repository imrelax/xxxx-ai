<?php
/**
 * 性能优化模块
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
 * 缓存管理类
 */
class XMan_Cache_Manager {
    
    private static $instance = null;
    private $cache_group = 'xman_theme';
    private $cache_expiry = 3600; // 1小时
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 获取缓存
     */
    public function get($key) {
        return wp_cache_get($key, $this->cache_group);
    }
    
    /**
     * 设置缓存
     */
    public function set($key, $data, $expiry = null) {
        $expiry = $expiry ?: $this->cache_expiry;
        return wp_cache_set($key, $data, $this->cache_group, $expiry);
    }
    
    /**
     * 删除缓存
     */
    public function delete($key) {
        return wp_cache_delete($key, $this->cache_group);
    }
    
    /**
     * 清空所有缓存
     */
    public function flush() {
        return wp_cache_flush();
    }
}

/**
 * 优化文章浏览量统计
 */
function xman_optimized_set_post_views($post_id) {
    $cache = XMan_Cache_Manager::get_instance();
    $cache_key = 'post_views_' . $post_id;
    
    // 从缓存获取当前浏览量
    $count = $cache->get($cache_key);
    
    if ($count === false) {
        // 缓存不存在，从数据库获取
        $count = get_post_meta($post_id, 'post_views', true);
        if ($count == '') {
            $count = 0;
        }
    }
    
    $count++;
    
    // 更新缓存
    $cache->set($cache_key, $count, 300); // 5分钟缓存
    
    // 每10次浏览更新一次数据库，减少数据库写入
    if ($count % 10 == 0) {
        update_post_meta($post_id, 'post_views', $count);
    }
}

/**
 * 优化获取文章浏览量
 */
function xman_optimized_get_post_views($post_id) {
    $cache = XMan_Cache_Manager::get_instance();
    $cache_key = 'post_views_' . $post_id;
    
    // 先从缓存获取
    $count = $cache->get($cache_key);
    
    if ($count === false) {
        // 缓存不存在，从数据库获取
        $count = get_post_meta($post_id, 'post_views', true);
        if ($count == '') {
            $count = 0;
            add_post_meta($post_id, 'post_views', '0');
        }
        
        // 设置缓存
        $cache->set($cache_key, $count);
    }
    
    return $count;
}

/**
 * 优化相关文章查询
 */
function xman_optimized_get_related_posts($post_id, $number = 4) {
    $cache = XMan_Cache_Manager::get_instance();
    $cache_key = 'related_posts_' . $post_id . '_' . $number;
    
    // 先从缓存获取
    $related_posts = $cache->get($cache_key);
    
    if ($related_posts === false) {
        $categories = wp_get_post_categories($post_id);
        $tags = wp_get_post_tags($post_id);
        
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $number,
            'post__not_in' => array($post_id),
            'post_status' => 'publish',
            'orderby' => 'rand',
            'no_found_rows' => true, // 不计算总数，提升性能
            'update_post_meta_cache' => false, // 不更新meta缓存
            'update_post_term_cache' => false, // 不更新term缓存
            'meta_query' => array(
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                )
            )
        );
        
        if (!empty($categories)) {
            $args['category__in'] = $categories;
        } elseif (!empty($tags)) {
            $tag_ids = array();
            foreach ($tags as $tag) {
                $tag_ids[] = $tag->term_id;
            }
            $args['tag__in'] = $tag_ids;
        }
        
        $query = new WP_Query($args);
        $related_posts = $query->posts;
        
        // 缓存结果
        $cache->set($cache_key, $related_posts, 1800); // 30分钟缓存
    }
    
    return $related_posts;
}

/**
 * 优化专题文章获取
 */
function xman_optimized_get_topic_posts($topic_id) {
    $cache = XMan_Cache_Manager::get_instance();
    $cache_key = 'topic_posts_' . $topic_id;
    
    // 先从缓存获取
    $posts = $cache->get($cache_key);
    
    if ($posts === false) {
        $post_ids_string = get_post_meta($topic_id, '_topic_articles', true);
        if (empty($post_ids_string)) {
            $posts = array();
        } else {
            $post_ids = array_map('intval', explode(',', $post_ids_string));
            $post_ids = array_filter($post_ids);
            
            if (!empty($post_ids)) {
                $args = array(
                    'post_type' => 'post',
                    'post__in' => $post_ids,
                    'orderby' => 'post__in',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'no_found_rows' => true,
                    'update_post_meta_cache' => false,
                    'update_post_term_cache' => false,
                );
                
                $query = new WP_Query($args);
                $posts = $query->posts;
            } else {
                $posts = array();
            }
        }
        
        // 缓存结果
        $cache->set($cache_key, $posts, 1800); // 30分钟缓存
    }
    
    return $posts;
}

/**
 * 优化幻灯片文章获取
 */
function xman_optimized_get_slide_posts($limit = 5) {
    $cache = XMan_Cache_Manager::get_instance();
    $cache_key = 'slide_posts_' . $limit;
    
    // 先从缓存获取
    $posts = $cache->get($cache_key);
    
    if ($posts === false) {
        $slide_ids = xman_ai_get_slide_post_ids();
        
        if (!empty($slide_ids)) {
            $args = array(
                'post_type' => 'post',
                'post__in' => $slide_ids,
                'orderby' => 'post__in',
                'posts_per_page' => $limit,
                'post_status' => 'publish',
                'no_found_rows' => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            );
        } else {
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => $limit,
                'meta_key' => 'post_views',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'post_status' => 'publish',
                'no_found_rows' => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            );
        }
        
        $posts = get_posts($args);
        
        // 缓存结果
        $cache->set($cache_key, $posts, 600); // 10分钟缓存
    }
    
    return $posts;
}

/**
 * 清除相关缓存当文章更新时
 */
function xman_clear_post_cache($post_id) {
    $cache = XMan_Cache_Manager::get_instance();
    
    // 清除文章浏览量缓存
    $cache->delete('post_views_' . $post_id);
    
    // 清除相关文章缓存
    for ($i = 1; $i <= 10; $i++) {
        $cache->delete('related_posts_' . $post_id . '_' . $i);
    }
    
    // 清除专题缓存
    $cache->delete('topic_posts_' . $post_id);
    
    // 清除幻灯片缓存
    for ($i = 1; $i <= 10; $i++) {
        $cache->delete('slide_posts_' . $i);
    }
}
add_action('save_post', 'xman_clear_post_cache');
add_action('delete_post', 'xman_clear_post_cache');

// 优化数据库查询功能已移至theme-functions-optimization.php

/**
 * 限制文章修订版本数量
 */
if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 3);
}

/**
 * 自动清理垃圾评论
 */
function xman_auto_delete_spam_comments() {
    global $wpdb;
    
    // 删除30天前的垃圾评论
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam' AND comment_date < %s",
            date('Y-m-d H:i:s', strtotime('-30 days'))
        )
    );
}

// 每天运行一次清理
if (!wp_next_scheduled('xman_cleanup_spam_comments')) {
    wp_schedule_event(time(), 'daily', 'xman_cleanup_spam_comments');
}
add_action('xman_cleanup_spam_comments', 'xman_auto_delete_spam_comments');

?>