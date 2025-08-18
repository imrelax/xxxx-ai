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
        // 对于广告代码，我们需要允许JavaScript执行
        // 使用更宽松的过滤策略，允许script标签和常见的广告属性
        $allowed_html = wp_kses_allowed_html('post');
        $allowed_html['script'] = array(
            'type' => array(),
            'src' => array(),
            'async' => array(),
            'defer' => array(),
            'crossorigin' => array(),
            'integrity' => array(),
            'data-ad-client' => array(),
            'data-ad-slot' => array(),
            'data-ad-format' => array(),
            'data-full-width-responsive' => array(),
        );
        $allowed_html['ins'] = array(
            'class' => array(),
            'style' => array(),
            'data-ad-client' => array(),
            'data-ad-slot' => array(),
            'data-ad-format' => array(),
            'data-full-width-responsive' => array(),
        );
        echo wp_kses($ad_code, $allowed_html);
        echo '</div>';
    }
}

/**
 * 显示侧边栏广告位1 - 站长信息下方
 * 严格限制只能在主侧边栏显示
 */
function xman_show_sidebar_ad1() {
    // 检查是否在正确的侧边栏上下文中调用
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
    $is_main_sidebar_context = false;
    
    foreach ($backtrace as $trace) {
        if (isset($trace['file'])) {
            // 只允许在主侧边栏文件中调用
            if (strpos($trace['file'], 'sidebar.php') !== false && 
                strpos($trace['file'], 'single-topic.php') === false) {
                $is_main_sidebar_context = true;
                break;
            }
        }
    }
    
    // 只有在主侧边栏上下文中才显示广告
    if ($is_main_sidebar_context) {
        xman_show_ad(1, 'sidebar-ad mb-6');
    }
}

/**
 * 显示侧边栏广告位2 - 热门文章间
 * 严格限制只能在侧边栏显示
 */
function xman_show_sidebar_ad2() {
    // 检查是否在侧边栏上下文中调用
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
    $is_sidebar_context = false;
    
    foreach ($backtrace as $trace) {
        if (isset($trace['file']) && strpos($trace['file'], 'sidebar.php') !== false) {
            $is_sidebar_context = true;
            break;
        }
    }
    
    // 只有在侧边栏上下文中才显示广告
    if ($is_sidebar_context) {
        xman_show_ad(2, 'sidebar-ad mb-6');
    }
}

/**
 * 显示文章内容上方广告位3
 * 严格限制只能在文章内容区域显示
 */
function xman_show_content_top_ad() {
    // 检查是否在正确的内容上下文中调用
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
    $is_content_context = false;
    
    foreach ($backtrace as $trace) {
        if (isset($trace['file'])) {
            // 只允许在内容相关文件中调用
            if (strpos($trace['file'], 'single.php') !== false || 
                strpos($trace['file'], 'page.php') !== false ||
                strpos($trace['file'], 'content.php') !== false ||
                strpos($trace['file'], 'single-topic.php') !== false) {
                $is_content_context = true;
                break;
            }
        }
    }
    
    if ($is_content_context) {
        xman_show_ad(3, 'content-ad mb-6');
    }
}

/**
 * 显示文章内容下方广告位4
 * 严格限制只能在文章内容区域显示
 */
function xman_show_content_bottom_ad() {
    // 检查是否在正确的内容上下文中调用
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
    $is_content_context = false;
    
    foreach ($backtrace as $trace) {
        if (isset($trace['file'])) {
            // 只允许在内容相关文件中调用
            if (strpos($trace['file'], 'single.php') !== false || 
                strpos($trace['file'], 'page.php') !== false ||
                strpos($trace['file'], 'content.php') !== false ||
                strpos($trace['file'], 'single-topic.php') !== false) {
                $is_content_context = true;
                break;
            }
        }
    }
    
    if ($is_content_context) {
        xman_show_ad(4, 'content-ad mt-6');
    }
}

/**
 * 显示首页文章列表间广告位5
 * 严格限制只能在首页和分类页显示
 */
function xman_show_home_list_ad() {
    // 检查是否在正确的列表页上下文中调用
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
    $is_list_context = false;
    
    foreach ($backtrace as $trace) {
        if (isset($trace['file'])) {
            // 只允许在首页和分类页文件中调用
            if (strpos($trace['file'], 'index.php') !== false || 
                strpos($trace['file'], 'category.php') !== false ||
                strpos($trace['file'], 'archive.php') !== false) {
                $is_list_context = true;
                break;
            }
        }
    }
    
    if ($is_list_context) {
        xman_show_ad(5, 'home-ad my-6');
    }
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
 * 广告位样式 - 每个广告位独立控制
 */
function xman_ads_styles() {
    ?>
    <style>
    
    /* 侧边栏广告位 AD1 - 宽度300px，高度自适应 */
    .xman-ad-1 {
        width: 300px !important;
        max-width: 300px !important;
        min-width: 300px !important;
        height: auto !important;
        min-height: 100px !important;
        max-height: none !important;
        margin: 0 auto 20px !important;
        background: #f8f9fa !important;
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        padding: 10px !important;
        box-sizing: border-box !important;
        text-align: center !important;
        position: relative !important;
        overflow: visible !important;
        display: block !important;
    }
    
    .xman-ad-1 ins.adsbygoogle {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        max-width: 100% !important;
        overflow: visible !important;
        z-index: 1001;
    }
    
    .xman-ad-1 iframe {
        max-width: 100% !important;
        width: 100% !important;
        height: auto !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        z-index: 1001 !important;
    }
    
    .xman-ad-1 img {
        max-width: 100% !important;
        height: auto !important;
        width: auto !important;
        object-fit: contain !important;
        display: block !important;
    }
    
    /* 侧边栏广告位 AD2 - 宽度300px，高度自适应 */
    .xman-ad-2 {
        width: 300px !important;
        max-width: 300px !important;
        min-width: 300px !important;
        height: auto !important;
        min-height: 100px !important;
        max-height: none !important;
        margin: 0 auto 20px !important;
        background: #f8f9fa !important;
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        padding: 10px !important;
        box-sizing: border-box !important;
        text-align: center !important;
        position: relative !important;
        overflow: visible !important;
        display: block !important;
    }
    
    .xman-ad-2 ins.adsbygoogle {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        max-width: 100% !important;
        overflow: visible !important;
        z-index: 1001;
    }
    
    .xman-ad-2 iframe {
        max-width: 100% !important;
        width: 100% !important;
        height: auto !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        z-index: 1001 !important;
    }
    
    .xman-ad-2 img {
        max-width: 100% !important;
        height: auto !important;
        width: auto !important;
        object-fit: contain !important;
        display: block !important;
    }
    
    /* 文章内容广告位 AD3 - 全宽显示，文章上方 */
    .xman-ad-3 {
        width: 100vw;
        max-width: 100vw;
        height: auto;
        min-height: 20px;
        margin: 0 0 -10px 0;
        margin-left: calc(-50vw + 50%);
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0;
        padding: 0;
        box-sizing: border-box;
        position: static;
        overflow: hidden;
        z-index: 1;
        text-align: center;
    }
    
    .xman-ad-3 ins.adsbygoogle {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        max-width: 100% !important;
        overflow: hidden !important;
        z-index: 1;
    }
    
    .xman-ad-3 iframe {
        max-width: 100% !important;
        width: 100% !important;
        height: auto !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
        z-index: 1 !important;
    }
    
    .xman-ad-3 img {
        max-width: 100% !important;
        height: auto !important;
        width: auto !important;
        object-fit: contain !important;
        display: block !important;
        margin: 0 auto !important;
    }
    
    /* 文章内容广告位 AD4 - 全宽显示，文章下方 */
    .xman-ad-4 {
        width: 100vw;
        max-width: 100vw;
        height: auto;
        min-height: 20px;
        margin: 0;
        margin-left: calc(-50vw + 50%);
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0;
        padding: 0;
        box-sizing: border-box;
        position: static;
        overflow: hidden;
        z-index: 1;
        text-align: center;
    }
    
    .xman-ad-4 ins.adsbygoogle {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        max-width: 100% !important;
        overflow: hidden !important;
        z-index: 1;
    }
    
    .xman-ad-4 iframe {
        max-width: 100% !important;
        width: 100% !important;
        height: auto !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
        z-index: 1 !important;
    }
    
    .xman-ad-4 img {
        max-width: 100% !important;
        height: auto !important;
        width: auto !important;
        object-fit: contain !important;
        display: block !important;
        margin: 0 auto !important;
    }
    
    /* 首页文章列表间广告位 AD5 - 不限制宽度，高度120px */
    .xman-ad-5 {
        width: 100%;
        height: auto;
        min-height: 120px;
        margin: 30px auto;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        box-sizing: border-box;
        position: relative;
        overflow: visible;
        text-align: center;
    }
    
    .xman-ad-5 ins.adsbygoogle {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        max-width: 100% !important;
        overflow: visible !important;
        z-index: 1001;
    }
    
    .xman-ad-5 iframe {
        max-width: 100% !important;
        width: 100% !important;
        height: auto !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        z-index: 1001 !important;
    }
    
    .xman-ad-5 img {
        max-width: 100% !important;
        height: auto !important;
        width: auto !important;
        object-fit: contain !important;
        display: block !important;
        margin: 0 auto !important;
    }
    
    .sidebar-ad {
        background: transparent;
        border: none;
        border-radius: 8px;
        padding: 0;
        margin-bottom: 0;
        width: 100%;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
        overflow: visible;
        box-sizing: border-box;
    }
    
    /* 移动端优化 - 独立控制每个广告位 */
    @media (max-width: 768px) {
        .xman-ad-1 {
            max-width: 100% !important;
            margin: 0 auto 15px !important;
            padding: 8px !important;
            background: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            border-radius: 8px !important;
        }
        
        .xman-ad-2 {
            width: 100% !important;
            max-width: 100% !important;
            min-width: auto !important;
            padding: 8px !important;
            background: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            border-radius: 8px !important;
        }
        
        .xman-ad-3 {
            width: 100vw;
            margin: 0 0 -10px 0;
            margin-left: calc(-50vw + 50%);
            padding: 0;
            border-radius: 0;
            min-height: 20px;
        }
        
        .xman-ad-4 {
            width: 100vw;
            margin: 0;
            margin-left: calc(-50vw + 50%);
            padding: 0;
            border-radius: 0;
            min-height: 20px;
        }
        
        .xman-ad-5 {
            margin: 20px auto;
            padding: 10px;
            min-height: 100px;
        }
    }
    
    /* 超小屏幕优化 */
    @media (max-width: 480px) {
        .xman-ad-1 {
            padding: 6px !important;
            background: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            border-radius: 8px !important;
        }
        
        .xman-ad-2 {
            padding: 6px !important;
            background: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            border-radius: 8px !important;
        }
        
        .xman-ad-3,
        .xman-ad-4,
        .xman-ad-5 {
            padding: 6px;
        }
        
        .xman-ad-3 {
            min-height: 20px;
        }
        
        .xman-ad-4 {
            min-height: 20px;
        }
        
        .xman-ad-4 {
            min-height: 80px;
        }
        
        .xman-ad-5 {
            min-height: 90px;
        }
    }
    
    /* 侧边栏广告容器样式 */
    .sidebar-ad {
        border-radius: 8px;
        padding: 0;
        margin-bottom: 0;
        width: 100%;
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
        overflow: visible;
        box-sizing: border-box;
        text-align: center;
        position: relative;
    }
    
    .sidebar-ad ins.adsbygoogle {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        max-width: 100% !important;
        overflow: visible !important;
        z-index: 1001;
    }
    
    .sidebar-ad iframe {
        max-width: 100% !important;
        width: 100% !important;
        height: auto !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        z-index: 1001 !important;
    }
    
    .sidebar-ad img {
        max-width: 100% !important;
        height: auto !important;
        width: auto !important;
        object-fit: contain !important;
        display: block !important;
        margin: 0 auto !important;
    }
    </style>
    <?php
}
add_action('wp_head', 'xman_ads_styles');