<?php
/**
 * 广告位显示函数
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
 * 显示广告位
 * 
 * @param int $position 广告位位置 (1-5)
 * @param string $class 额外的CSS类名
 * @return void
 */
function xman_show_ad($position, $class = '') {
    $ad_code = xman_ai_get_ad_code($position);
    
    if ($ad_code) {
        $wrapper_class = 'xman-ad xman-ad-' . $position;
        if ($class) {
            $wrapper_class .= ' ' . $class;
        }
        
        echo '<div class="' . esc_attr($wrapper_class) . '">';
        echo $ad_code; // 广告代码已经在后台经过wp_kses_post处理
        echo '</div>';
    }
}

/**
 * 显示侧边栏广告位1 - 站长信息下方
 */
function xman_show_sidebar_ad1() {
    xman_show_ad(1, 'sidebar-ad mb-6');
}

/**
 * 显示侧边栏广告位2 - 热门文章间
 */
function xman_show_sidebar_ad2() {
    xman_show_ad(2, 'sidebar-ad mb-6');
}

/**
 * 显示文章内容上方广告位3
 */
function xman_show_content_top_ad() {
    xman_show_ad(3, 'content-ad mb-6');
}

/**
 * 显示文章内容下方广告位4
 */
function xman_show_content_bottom_ad() {
    xman_show_ad(4, 'content-ad mt-6');
}

/**
 * 显示首页文章列表间广告位5
 */
function xman_show_home_list_ad() {
    xman_show_ad(5, 'home-ad my-6');
}

/**
 * 检查广告位是否有内容
 * 
 * @param int $position 广告位位置
 * @return bool
 */
function xman_has_ad($position) {
    $ad_code = xman_ai_get_ad_code($position);
    return !empty(trim($ad_code));
}

/**
 * 获取所有广告位状态
 * 
 * @return array
 */
function xman_get_ads_status() {
    $status = array();
    
    for ($i = 1; $i <= 5; $i++) {
        $status['ad' . $i] = xman_has_ad($i);
    }
    
    return $status;
}

/**
 * 在文章内容中自动插入广告
 * 
 * @param string $content 文章内容
 * @return string
 */
function xman_auto_insert_ads($content) {
    // 只在单篇文章页面插入广告
    if (!is_single()) {
        return $content;
    }
    
    // 获取广告代码
    $ad3_code = xman_ai_get_ad_code(3); // 文章上方
    $ad4_code = xman_ai_get_ad_code(4); // 文章下方
    
    // 在文章开头插入广告3
    if ($ad3_code) {
        $content = '<div class="xman-ad xman-ad-3 content-ad mb-6">' . $ad3_code . '</div>' . $content;
    }
    
    // 在文章结尾插入广告4
    if ($ad4_code) {
        $content = $content . '<div class="xman-ad xman-ad-4 content-ad mt-6">' . $ad4_code . '</div>';
    }
    
    return $content;
}
// 注释掉自动插入，改为在模板中手动调用
// add_filter('the_content', 'xman_auto_insert_ads');

/**
 * 广告位样式
 */
function xman_ads_styles() {
    ?>
    <style>
    .xman-ad {
        text-align: center;
        margin: 20px 0;
        overflow: hidden;
        box-sizing: border-box;
    }
    
    .xman-ad-1, .xman-ad-2 {
        max-width: 300px;
        margin: 0 auto;
        width: 100%;
    }
    
    .xman-ad-3, .xman-ad-4 {
        width: 100%;
        margin: 0 auto;
    }
    
    .xman-ad-5 {
        width: 100%;
        margin: 20px auto;
    }
    
    .sidebar-ad {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 0;
        width: 100%;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
        overflow: hidden;
        box-sizing: border-box;
    }
    
    .sidebar-ad * {
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .content-ad {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
    }
    
    .content-ad-top {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 0;
        margin: 0;
        padding: 16px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        position: relative;
    }
    
    .content-ad-top::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
    }
    
    .content-ad-header {
        width: 100%;
        margin: 0;
        padding: 0;
        background: none;
        border: none;
        border-radius: 0;
        text-align: center;
    }
    
    .content-ad-header * {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        box-sizing: border-box;
    }
    
    .content-ad-related {
        width: 100%;
        margin: 0;
        padding: 0;
        background: none;
        border: none;
        border-radius: 0;
        text-align: center;
    }
    
    .content-ad-related * {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        box-sizing: border-box;
    }
    
    .home-ad {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
    }
    
    @media (max-width: 768px) {
        .xman-ad-3, .xman-ad-4 {
            max-width: 100%;
        }
        
        .content-ad, .home-ad, .sidebar-ad {
            padding: 10px;
        }
        
        .content-ad-top {
            padding: 12px;
            margin: 0;
        }
        
        .content-ad-top::before {
            height: 1px;
        }
        
        .content-ad-header {
            width: 100%;
            margin: 0 auto;
        }
        
        .content-ad-related {
            width: 100%;
            margin: 0 auto;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'xman_ads_styles');