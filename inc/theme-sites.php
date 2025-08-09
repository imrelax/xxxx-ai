<?php
/**
 * 推荐站点显示函数
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
 * 显示推荐站点小部件
 * 
 * @param string $title 小部件标题
 * @param int $limit 显示数量限制
 * @return void
 */
function xman_show_recommended_sites($title = '推荐站点', $limit = 10) {
    $sites = xman_ai_get_recommended_sites();
    
    if (empty($sites)) {
        return;
    }
    
    // 限制显示数量
    if ($limit > 0) {
        $sites = array_slice($sites, 0, $limit);
    }
    
    ?>
    <div class="widget recommended-sites mb-6">
        <h3 class="widget-title text-lg font-semibold mb-4 pb-2 border-b border-gray-200">
            <i class="fas fa-link mr-2 text-blue-500"></i><?php echo esc_html($title); ?>
        </h3>
        
        <div class="sites-list space-y-3">
            <?php foreach ($sites as $site): ?>
                <div class="site-item p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="site-info">
                        <h4 class="site-name mb-1">
                            <a href="<?php echo esc_url($site['url']); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                <?php echo esc_html($site['name']); ?>
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        </h4>
                        <?php if (!empty($site['desc'])): ?>
                            <p class="site-desc text-xs text-gray-600 leading-relaxed">
                                <?php echo esc_html($site['desc']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="sites-footer mt-4 pt-3 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center">
                <i class="fas fa-heart text-red-400 mr-1"></i>
                精选优质站点推荐
            </p>
        </div>
    </div>
    <?php
}

/**
 * 获取推荐站点的简化版本（仅链接）
 * 
 * @param int $limit 显示数量限制
 * @return void
 */
function xman_show_simple_sites($limit = 8) {
    $sites = xman_ai_get_recommended_sites();
    
    if (empty($sites)) {
        return;
    }
    
    // 限制显示数量
    if ($limit > 0) {
        $sites = array_slice($sites, 0, $limit);
    }
    
    ?>
    <div class="widget simple-sites mb-6">
        <h3 class="widget-title text-lg font-semibold mb-4 pb-2 border-b border-gray-200">
            <i class="fas fa-star mr-2 text-yellow-500"></i>友情链接
        </h3>
        
        <div class="sites-grid grid grid-cols-2 gap-2">
            <?php foreach ($sites as $site): ?>
                <a href="<?php echo esc_url($site['url']); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="site-link block p-2 text-center bg-white border border-gray-200 rounded hover:border-blue-300 hover:shadow-sm transition-all text-sm text-gray-700 hover:text-blue-600"
                   title="<?php echo esc_attr($site['desc']); ?>">
                    <?php echo esc_html($site['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * 显示随机推荐站点
 * 
 * @param string $title 小部件标题
 * @param int $limit 显示数量
 * @return void
 */
function xman_show_random_sites($title = '随机推荐', $limit = 5) {
    $sites = xman_ai_get_recommended_sites();
    
    if (empty($sites)) {
        return;
    }
    
    // 随机打乱数组
    shuffle($sites);
    
    // 限制显示数量
    if ($limit > 0) {
        $sites = array_slice($sites, 0, $limit);
    }
    
    ?>
    <div class="widget random-sites mb-6">
        <h3 class="widget-title text-lg font-semibold mb-4 pb-2 border-b border-gray-200">
            <i class="fas fa-random mr-2 text-green-500"></i><?php echo esc_html($title); ?>
        </h3>
        
        <div class="sites-list space-y-2">
            <?php foreach ($sites as $site): ?>
                <div class="site-item">
                    <a href="<?php echo esc_url($site['url']); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="site-link flex items-center p-2 bg-gray-50 rounded hover:bg-blue-50 transition-colors group">
                        <i class="fas fa-globe text-gray-400 group-hover:text-blue-500 mr-2 text-sm"></i>
                        <span class="text-sm text-gray-700 group-hover:text-blue-600">
                            <?php echo esc_html($site['name']); ?>
                        </span>
                        <i class="fas fa-external-link-alt ml-auto text-xs text-gray-400 group-hover:text-blue-500"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * 检查是否有推荐站点
 * 
 * @return bool
 */
function xman_has_recommended_sites() {
    $sites = xman_ai_get_recommended_sites();
    return !empty($sites);
}

/**
 * 获取推荐站点数量
 * 
 * @return int
 */
function xman_get_sites_count() {
    $sites = xman_ai_get_recommended_sites();
    return count($sites);
}

/**
 * 按分类获取推荐站点（如果描述中包含特定关键词）
 * 
 * @param string $keyword 关键词
 * @param int $limit 显示数量限制
 * @return array
 */
function xman_get_sites_by_keyword($keyword, $limit = 0) {
    $sites = xman_ai_get_recommended_sites();
    $filtered_sites = array();
    
    foreach ($sites as $site) {
        if (stripos($site['desc'], $keyword) !== false || stripos($site['name'], $keyword) !== false) {
            $filtered_sites[] = $site;
        }
    }
    
    if ($limit > 0) {
        $filtered_sites = array_slice($filtered_sites, 0, $limit);
    }
    
    return $filtered_sites;
}

/**
 * 显示特定类型的推荐站点
 * 
 * @param string $keyword 关键词
 * @param string $title 标题
 * @param int $limit 显示数量限制
 * @return void
 */
function xman_show_sites_by_type($keyword, $title, $limit = 5) {
    $sites = xman_get_sites_by_keyword($keyword, $limit);
    
    if (empty($sites)) {
        return;
    }
    
    ?>
    <div class="widget typed-sites mb-6">
        <h3 class="widget-title text-lg font-semibold mb-4 pb-2 border-b border-gray-200">
            <i class="fas fa-tags mr-2 text-purple-500"></i><?php echo esc_html($title); ?>
        </h3>
        
        <div class="sites-list space-y-2">
            <?php foreach ($sites as $site): ?>
                <div class="site-item p-2 border-l-4 border-purple-300 bg-purple-50">
                    <a href="<?php echo esc_url($site['url']); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="text-purple-700 hover:text-purple-900 font-medium text-sm">
                        <?php echo esc_html($site['name']); ?>
                    </a>
                    <?php if (!empty($site['desc'])): ?>
                        <p class="text-xs text-purple-600 mt-1">
                            <?php echo esc_html($site['desc']); ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}