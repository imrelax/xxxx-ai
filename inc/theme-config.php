<?php
/**
 * 主题配置管理模块
 * 统一管理主题的所有配置项，避免重复的get_option调用
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
 * 主题配置管理类
 */
class XmanThemeConfig {
    
    private static $instance = null;
    private $config_cache = array();
    
    /**
     * 获取单例实例
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 私有构造函数
     */
    private function __construct() {
        // 初始化配置缓存
        $this->loadConfig();
    }
    
    /**
     * 加载所有配置到缓存
     */
    private function loadConfig() {
        // 站点基本信息
        $this->config_cache['site'] = array(
            'name' => get_option('xman_site_name', get_bloginfo('name')),
            'description' => get_option('xman_site_description', get_bloginfo('description')),
            'keywords' => get_option('xman_site_keywords', ''),
            'logo' => get_option('xman_site_logo', ''),
            'favicon' => get_option('xman_site_favicon', ''),
            'logo_icon' => get_option('xman_logo_icon', 'fas fa-code'),
            'start_date' => get_option('xman_site_start_date', '2020-01-01')
        );
        
        // 作者信息
        $this->config_cache['author'] = array(
            'name' => get_option('xman_author_name', 'X-Man'),
            'title' => get_option('xman_author_title', '全栈开发工程师'),
            'avatar' => get_option('xman_author_avatar', ''),
            'bio' => get_option('xman_author_bio', '欢迎来到我的博客！'),
            'email' => get_option('xman_author_email', ''),
            'location' => get_option('xman_author_location', '中国·北京'),
            'weibo' => get_option('xman_author_weibo', ''),
            'github' => get_option('xman_author_github', ''),
            'twitter' => get_option('xman_author_twitter', ''),
            'wechat' => get_option('xman_author_wechat', '')
        );
        
        // 页脚信息
        $this->config_cache['footer'] = array(
            'title' => get_option('xman_footer_title', $this->config_cache['site']['name']),
            'desc' => get_option('xman_footer_desc', $this->config_cache['site']['description']),
            'copyright' => get_option('xman_copyright_text', '版权所有，保留所有权利。'),
            'icp' => get_option('xman_icp_number', ''),
            'code' => get_option('xman_footer_code', '')
        );
        
        // 联系信息
        $this->config_cache['contact'] = array(
            'email' => get_option('xman_contact_email', 'contact@xxxx.im'),
            'phone' => get_option('xman_contact_phone', ''),
            'address' => get_option('xman_contact_address', ''),
            'work_time' => get_option('xman_work_time', '')
        );
        
        // 其他设置
        $this->config_cache['misc'] = array(
            'search_placeholder' => get_option('xman_search_placeholder', '搜索文章...'),
            'analytics_code' => get_option('xman_analytics_code', ''),
            'custom_css' => get_option('xman_custom_css', ''),
            'enable_random_colors' => get_option('xman_enable_random_colors', true),
            'slide_post_ids' => get_option('xman_slide_post_ids', '')
        );
        
        // 加载推荐站点
        $this->config_cache['recommended_sites'] = $this->loadRecommendedSites();
        
        // 加载快速链接
        $this->config_cache['quick_links'] = $this->loadQuickLinks();
        
        // 加载广告代码
        $this->config_cache['ads'] = $this->loadAdsConfig();
    }
    
    /**
     * 加载推荐站点配置
     */
    private function loadRecommendedSites() {
        $sites = array();
        for ($i = 1; $i <= 5; $i++) {
            $title = get_option("xman_recommend_site_{$i}_title", '');
            $url = get_option("xman_recommend_site_{$i}_url", '');
            $icon = get_option("xman_recommend_site_{$i}_icon", 'fas fa-link');
            $desc = get_option("xman_recommend_site_{$i}_desc", '');
            
            if (!empty($title) && !empty($url)) {
                $sites[] = array(
                    'name' => $title,
                    'url' => $url,
                    'desc' => $desc,
                    'icon' => $icon
                );
            }
        }
        return $sites;
    }
    
    /**
     * 加载快速链接配置
     */
    private function loadQuickLinks() {
        $links = array();
        for ($i = 1; $i <= 4; $i++) {
            $title = get_option("xman_quick_link_{$i}_title", '');
            $url = get_option("xman_quick_link_{$i}_url", '#');
            $icon = get_option("xman_quick_link_{$i}_icon", 'fas fa-link');
            $desc = get_option("xman_quick_link_{$i}_desc", '');
            
            if (!empty($title)) {
                $links[] = array(
                    'title' => $title,
                    'url' => $url,
                    'icon' => $icon,
                    'desc' => $desc
                );
            }
        }
        return $links;
    }
    
    /**
     * 加载广告配置
     */
    private function loadAdsConfig() {
        $ads = array();
        for ($i = 1; $i <= 5; $i++) {
            $ads[$i] = get_option("xman_ad{$i}_code", '');
        }
        return $ads;
    }
    
    /**
     * 获取配置项
     * 
     * @param string $section 配置分组
     * @param string $key 配置键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get($section, $key = null, $default = null) {
        if ($key === null) {
            return isset($this->config_cache[$section]) ? $this->config_cache[$section] : $default;
        }
        
        return isset($this->config_cache[$section][$key]) ? $this->config_cache[$section][$key] : $default;
    }
    
    /**
     * 获取站点配置
     */
    public function getSite($key = null, $default = null) {
        return $this->get('site', $key, $default);
    }
    
    /**
     * 获取作者配置
     */
    public function getAuthor($key = null, $default = null) {
        return $this->get('author', $key, $default);
    }
    
    /**
     * 获取页脚配置
     */
    public function getFooter($key = null, $default = null) {
        return $this->get('footer', $key, $default);
    }
    
    /**
     * 获取联系信息
     */
    public function getContact($key = null, $default = null) {
        return $this->get('contact', $key, $default);
    }
    
    /**
     * 获取其他设置
     */
    public function getMisc($key = null, $default = null) {
        return $this->get('misc', $key, $default);
    }
    
    /**
     * 获取推荐站点
     */
    public function getRecommendedSites() {
        return $this->get('recommended_sites', null, array());
    }
    
    /**
     * 获取快速链接
     */
    public function getQuickLinks() {
        return $this->get('quick_links', null, array());
    }
    
    /**
     * 获取广告代码
     */
    public function getAd($position) {
        $ads = $this->get('ads', null, array());
        return isset($ads[$position]) ? $ads[$position] : '';
    }
    
    /**
     * 刷新配置缓存
     */
    public function refresh() {
        $this->config_cache = array();
        $this->loadConfig();
    }
}

/**
 * 获取主题配置实例
 * 
 * @return XmanThemeConfig
 */
function xman_config() {
    return XmanThemeConfig::getInstance();
}

/**
 * 便捷函数：获取站点配置
 */
function xman_get_site_config($key = null, $default = null) {
    return xman_config()->getSite($key, $default);
}

/**
 * 便捷函数：获取作者配置
 */
function xman_get_author_config($key = null, $default = null) {
    return xman_config()->getAuthor($key, $default);
}

/**
 * 便捷函数：获取页脚配置
 */
function xman_get_footer_config($key = null, $default = null) {
    return xman_config()->getFooter($key, $default);
}

/**
 * 便捷函数：获取联系信息
 */
function xman_get_contact_config($key = null, $default = null) {
    return xman_config()->getContact($key, $default);
}

/**
 * 便捷函数：获取推荐站点
 */
function xman_get_recommended_sites() {
    return xman_config()->getRecommendedSites();
}

/**
 * 便捷函数：获取快速链接
 */
function xman_get_quick_links() {
    return xman_config()->getQuickLinks();
}

/**
 * 便捷函数：获取广告代码
 */
function xman_get_ad_code($position) {
    return xman_config()->getAd($position);
}

/**
 * 便捷函数：获取搜索占位符
 */
function xman_get_search_placeholder() {
    return xman_config()->getMisc('search_placeholder');
}

/**
 * 便捷函数：获取分析代码
 */
function xman_get_analytics_code() {
    return xman_config()->getMisc('analytics_code');
}