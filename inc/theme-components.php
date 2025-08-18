<?php
/**
 * 主题组件模块
 * 提供可复用的HTML组件，避免重复的HTML结构
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
 * 渲染推荐站点列表
 * 
 * @param array $options 配置选项
 * @return string HTML输出
 */
function xman_render_recommended_sites($options = array()) {
    $defaults = array(
        'title' => '推荐站点',
        'show_title' => true,
        'class' => 'recommended-sites',
        'item_class' => 'site-item',
        'show_description' => true,
        'target' => '_blank'
    );
    
    $options = wp_parse_args($options, $defaults);
    $sites = xman_get_recommended_sites();
    
    if (empty($sites)) {
        return '';
    }
    
    ob_start();
    ?>
    <div class="<?php echo esc_attr($options['class']); ?>">
        <?php if ($options['show_title']) : ?>
            <h3 class="text-lg font-bold mb-4 text-gray-900"><?php echo esc_html($options['title']); ?></h3>
        <?php endif; ?>
        <div class="space-y-3">
            <?php foreach ($sites as $site) : ?>
                <div class="<?php echo esc_attr($options['item_class']); ?>">
                    <a href="<?php echo esc_url($site['url']); ?>" 
                       target="<?php echo esc_attr($options['target']); ?>" 
                       class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 group">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center mr-3">
                            <i class="<?php echo esc_attr($site['icon']); ?> text-xl text-gray-600 group-hover:text-blue-600 transition-colors"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                                <?php echo esc_html($site['name']); ?>
                            </h4>
                            <?php if ($options['show_description'] && !empty($site['desc'])) : ?>
                                <p class="text-xs text-gray-500 mt-1 truncate">
                                    <?php echo esc_html($site['desc']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-external-link-alt text-gray-400 text-xs group-hover:text-blue-500 transition-colors"></i>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * 渲染快速链接
 * 
 * @param array $options 配置选项
 * @return string HTML输出
 */
function xman_render_quick_links($options = array()) {
    $defaults = array(
        'title' => '快速导航',
        'show_title' => true,
        'class' => 'quick-links',
        'grid_cols' => 2,
        'show_description' => true,
        'default_colors' => array(
            array('bg_color' => 'bg-blue-500', 'hover_color' => 'hover:bg-blue-600'),
            array('bg_color' => 'bg-green-500', 'hover_color' => 'hover:bg-green-600'),
            array('bg_color' => 'bg-purple-500', 'hover_color' => 'hover:bg-purple-600'),
            array('bg_color' => 'bg-orange-500', 'hover_color' => 'hover:bg-orange-600')
        )
    );
    
    $options = wp_parse_args($options, $defaults);
    $links = xman_get_quick_links();
    
    // 如果没有配置的快速链接，使用默认分类
    if (empty($links)) {
        $categories = get_categories(array('number' => 4, 'orderby' => 'count', 'order' => 'DESC'));
        foreach ($categories as $index => $category) {
            $color_index = $index % 4;
            $links[] = array(
                'title' => $category->name,
                'url' => get_category_link($category->term_id),
                'icon' => 'fas fa-folder',
                'desc' => $category->count . ' 篇文章',
                'bg_color' => $options['default_colors'][$color_index]['bg_color'],
                'hover_color' => $options['default_colors'][$color_index]['hover_color']
            );
        }
        
        // 如果分类不足4个，用默认链接补充
        if (count($links) < 4) {
            $default_links = array(
                array('title' => '前端开发', 'url' => '#', 'icon' => 'fas fa-code', 'desc' => '0 篇文章'),
                array('title' => '后端开发', 'url' => '#', 'icon' => 'fas fa-server', 'desc' => '0 篇文章'),
                array('title' => '移动开发', 'url' => '#', 'icon' => 'fas fa-mobile-alt', 'desc' => '0 篇文章'),
                array('title' => '数据库', 'url' => '#', 'icon' => 'fas fa-database', 'desc' => '0 篇文章')
            );
            
            for ($i = count($links); $i < 4; $i++) {
                $color_index = $i % 4;
                $links[] = array_merge($default_links[$i], array(
                    'bg_color' => $options['default_colors'][$color_index]['bg_color'],
                    'hover_color' => $options['default_colors'][$color_index]['hover_color']
                ));
            }
        }
    }
    
    if (empty($links)) {
        return '';
    }
    
    ob_start();
    ?>
    <div class="<?php echo esc_attr($options['class']); ?>">
        <?php if ($options['show_title']) : ?>
            <h3 class="text-lg font-bold mb-4 text-gray-900"><?php echo esc_html($options['title']); ?></h3>
        <?php endif; ?>
        <div class="grid grid-cols-<?php echo intval($options['grid_cols']); ?> gap-4">
            <?php foreach ($links as $link) : 
                $bg_color = isset($link['bg_color']) ? $link['bg_color'] : 'bg-blue-500';
                $hover_color = isset($link['hover_color']) ? $link['hover_color'] : 'hover:bg-blue-600';
            ?>
                <a href="<?php echo esc_url($link['url']); ?>" 
                   class="<?php echo esc_attr($bg_color . ' ' . $hover_color); ?> text-white p-4 rounded-lg transition-all duration-200 hover:shadow-lg transform hover:-translate-y-1 group">
                    <div class="flex items-center mb-2">
                        <i class="<?php echo esc_attr($link['icon']); ?> text-lg mr-3"></i>
                        <h4 class="font-medium text-sm"><?php echo esc_html($link['title']); ?></h4>
                    </div>
                    <?php if ($options['show_description'] && !empty($link['desc'])) : ?>
                        <p class="text-xs opacity-90"><?php echo esc_html($link['desc']); ?></p>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * 渲染作者信息卡片
 * 
 * @param array $options 配置选项
 * @return string HTML输出
 */
function xman_render_author_card($options = array()) {
    $defaults = array(
        'class' => 'author-card',
        'show_avatar' => true,
        'show_bio' => true,
        'show_social' => true,
        'show_stats' => true,
        'avatar_size' => 80
    );
    
    $options = wp_parse_args($options, $defaults);
    $author = xman_config()->getAuthor();
    
    ob_start();
    ?>
    <div class="<?php echo esc_attr($options['class']); ?> bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <!-- 站长信息头部 -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-4">
            <div class="flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                <h3 class="text-lg font-bold">站长信息</h3>
            </div>
        </div>
        
        <div class="p-6 text-center">
            <?php if ($options['show_avatar'] && !empty($author['avatar'])) : ?>
                <div class="relative mb-4">
                    <img src="<?php echo esc_url($author['avatar']); ?>" 
                         alt="<?php echo esc_attr($author['name']); ?>" 
                         class="w-20 h-20 rounded-full mx-auto border-4 border-white shadow-lg">
                    <div class="absolute inset-0 w-20 h-20 rounded-full mx-auto bg-gradient-to-tr from-blue-400 to-purple-500 opacity-20"></div>
                </div>
            <?php endif; ?>
            
            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo esc_html($author['name']); ?></h3>
            
            <?php if (!empty($author['title'])) : ?>
                <p class="text-sm text-blue-600 font-medium mb-3 px-3 py-1 bg-blue-50 rounded-full inline-block"><?php echo esc_html($author['title']); ?></p>
            <?php endif; ?>
            
            <?php if ($options['show_bio'] && !empty($author['bio'])) : ?>
                <p class="text-sm text-gray-600 mb-4 leading-relaxed px-2"><?php echo esc_html($author['bio']); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($author['location'])) : ?>
                <div class="flex items-center justify-center text-sm text-gray-500 mb-4 bg-gray-50 rounded-lg py-2 px-3 mx-4">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                    <?php echo esc_html($author['location']); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($options['show_social']) : ?>
                <div class="flex justify-center space-x-3 mb-4">
                    <?php if (!empty($author['github'])) : ?>
                        <a href="<?php echo esc_url($author['github']); ?>" target="_blank" 
                           class="w-10 h-10 bg-gradient-to-r from-gray-600 to-gray-800 text-white rounded-full flex items-center justify-center hover:shadow-lg transform hover:scale-110 transition-all duration-200" 
                           rel="noopener">
                            <i class="fab fa-github text-lg"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($author['weibo'])) : ?>
                        <a href="<?php echo esc_url($author['weibo']); ?>" target="_blank" 
                           class="w-10 h-10 bg-gradient-to-r from-red-400 to-red-600 text-white rounded-full flex items-center justify-center hover:shadow-lg transform hover:scale-110 transition-all duration-200" 
                           rel="noopener">
                            <i class="fab fa-weibo text-lg"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($author['twitter'])) : ?>
                        <a href="<?php echo esc_url($author['twitter']); ?>" target="_blank" 
                           class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 text-white rounded-full flex items-center justify-center hover:shadow-lg transform hover:scale-110 transition-all duration-200" 
                           rel="noopener">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($author['email'])) : ?>
                        <a href="mailto:<?php echo esc_attr($author['email']); ?>" 
                           class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 text-white rounded-full flex items-center justify-center hover:shadow-lg transform hover:scale-110 transition-all duration-200">
                            <i class="fas fa-envelope text-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($options['show_stats']) : ?>
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div class="text-center bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg py-3 px-2">
                        <div class="text-xl font-bold text-blue-600 mb-1"><?php echo wp_count_posts()->publish; ?></div>
                        <div class="text-xs text-blue-500 font-medium flex items-center justify-center">
                            <i class="fas fa-file-alt mr-1"></i>
                            文章
                        </div>
                    </div>
                    <div class="text-center bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg py-3 px-2">
                        <div class="text-xl font-bold text-purple-600 mb-1"><?php echo wp_count_comments()->approved; ?></div>
                        <div class="text-xs text-purple-500 font-medium flex items-center justify-center">
                            <i class="fas fa-comments mr-1"></i>
                            评论
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * 渲染广告位
 * 
 * @param int $position 广告位置 (1-5)
 * @param array $options 配置选项
 * @return string HTML输出
 */
function xman_render_ad($position, $options = array()) {
    $defaults = array(
        'class' => 'ad-container',
        'wrapper' => true,
        'label' => '广告'
    );
    
    $options = wp_parse_args($options, $defaults);
    $ad_code = xman_get_ad_code($position);
    
    if (empty($ad_code)) {
        return '';
    }
    
    ob_start();
    
    if ($options['wrapper']) {
        echo '<div class="' . esc_attr($options['class']) . '">';
        if (!empty($options['label'])) {
            echo '<div class="text-xs text-gray-400 mb-2 text-center">' . esc_html($options['label']) . '</div>';
        }
    }
    
    // 输出广告代码（已经过安全处理）
    echo $ad_code;
    
    if ($options['wrapper']) {
        echo '</div>';
    }
    
    return ob_get_clean();
}

/**
 * 渲染面包屑导航
 * 
 * @param array $options 配置选项
 * @return string HTML输出
 */
function xman_render_breadcrumb($options = array()) {
    if (is_home()) {
        return '';
    }
    
    $defaults = array(
        'class' => 'breadcrumb',
        'separator' => '<i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>',
        'home_text' => '首页',
        'home_icon' => 'fas fa-home'
    );
    
    $options = wp_parse_args($options, $defaults);
    
    $breadcrumb_items = array();
    
    // 首页链接
    $breadcrumb_items[] = array(
        'url' => home_url(),
        'text' => $options['home_text'],
        'icon' => $options['home_icon']
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
            'text' => get_the_title(),
            'icon' => 'fas fa-file-alt',
            'color' => 'text-gray-600'
        );
    } elseif (is_page()) {
        $breadcrumb_items[] = array(
            'text' => get_the_title(),
            'icon' => 'fas fa-file',
            'color' => 'text-gray-600'
        );
    } elseif (is_search()) {
        $breadcrumb_items[] = array(
            'text' => '搜索结果: ' . get_search_query(),
            'icon' => 'fas fa-search',
            'color' => 'text-green-500'
        );
    }
    
    if (count($breadcrumb_items) <= 1) {
        return '';
    }
    
    ob_start();
    ?>
    <div class="breadcrumb-container border-b border-gray-200 pb-2 mb-4">
        <nav class="<?php echo esc_attr($options['class']); ?> flex items-center text-sm">
            <?php foreach ($breadcrumb_items as $index => $item) : ?>
                <?php if ($index > 0) : ?>
                    <?php echo $options['separator']; ?>
                <?php endif; ?>
                
                <?php if (isset($item['url'])) : ?>
                    <a href="<?php echo esc_url($item['url']); ?>" 
                       class="flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="<?php echo esc_attr($item['icon']); ?> mr-1 text-xs"></i>
                        <span><?php echo esc_html($item['text']); ?></span>
                    </a>
                <?php else : ?>
                    <span class="flex items-center <?php echo isset($item['color']) ? esc_attr($item['color']) : 'text-gray-800'; ?>">
                        <i class="<?php echo esc_attr($item['icon']); ?> mr-1 text-xs"></i>
                        <span class="font-medium"><?php echo esc_html($item['text']); ?></span>
                    </span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
    </div>
    <?php
    return ob_get_clean();
}