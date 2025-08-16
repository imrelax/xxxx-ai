<?php
/**
 * X-Man AI主题 - 侧边栏模板
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */
?>

<div class="sidebar-container space-y-8">
    <?php 
    // 检查是否设置了站长信息
    $author_name = get_option('xman_author_name', '');
    $author_title = get_option('xman_author_title', '');
    $author_bio = get_option('xman_author_bio', '');
    $author_email = get_option('xman_author_email', '');
    $author_location = get_option('xman_author_location', '');
    $author_avatar = get_option('xman_author_avatar', '');
    
    // 如果有任何一个站长信息字段不为空，则显示站长信息小部件
    $has_author_info = !empty($author_name) || !empty($author_title) || !empty($author_bio) || !empty($author_email) || !empty($author_location) || !empty($author_avatar);
    
    if ($has_author_info) : 
    ?>
    <!-- 站长信息小部件 -->
    <div class="widget-container bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-4">
            <div class="flex items-center">
                <i class="fas fa-user mr-2"></i>
                <h3 class="text-lg font-bold">站长信息</h3>
            </div>
        </div>
        <div class="p-6">
        <div class="text-center">
            <?php if (!empty($author_avatar)) : ?>
            <div class="mb-4">
                <img src="<?php echo esc_url($author_avatar); ?>" alt="站长头像" class="w-20 h-20 rounded-full mx-auto border-4 border-blue-100">
            </div>
            <?php endif; ?>
            <div>
                <?php if (!empty($author_name)) : ?>
                <h4 class="text-xl font-bold text-gray-900 mb-1"><?php echo esc_html($author_name); ?></h4>
                <?php endif; ?>
                <?php if (!empty($author_title)) : ?>
                <p class="text-blue-600 font-medium mb-3"><?php echo esc_html($author_title); ?></p>
                <?php endif; ?>
                <?php if (!empty($author_bio)) : ?>
                <p class="text-gray-600 text-sm mb-4"><?php echo esc_html($author_bio); ?></p>
                <?php endif; ?>
                
                <!-- 联系方式 -->
                <?php if (!empty($author_email) || !empty($author_location)) : ?>
                <div class="space-y-3 mb-6">
                    <?php if (!empty($author_email)) : ?>
                    <div class="flex items-center justify-center text-sm text-gray-600 bg-gray-50 rounded-lg py-2 px-4">
                        <i class="fas fa-envelope text-blue-500 mr-3"></i>
                        <a href="mailto:<?php echo esc_attr($author_email); ?>" class="hover:text-blue-600 transition-colors font-medium">
                            <?php echo esc_html($author_email); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($author_location)) : ?>
                    <div class="flex items-center justify-center text-sm text-gray-600 bg-gray-50 rounded-lg py-2 px-4">
                        <i class="fas fa-map-marker-alt text-red-500 mr-3"></i>
                        <span class="font-medium"><?php echo esc_html($author_location); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- 社交媒体 -->
                <?php 
                $author_github = get_option('xman_author_github', '');
                $author_weibo = get_option('xman_author_weibo', '');
                $author_twitter = get_option('xman_author_twitter', '');
                $author_wechat = get_option('xman_author_wechat', '');
                $has_social = !empty($author_github) || !empty($author_weibo) || !empty($author_twitter) || !empty($author_wechat);
                
                if ($has_social) : 
                ?>
                <div class="flex justify-center space-x-4 mb-6">
                    <?php if (!empty($author_github)) : ?>
                    <a href="<?php echo esc_url($author_github); ?>" target="_blank" class="social-icon w-10 h-10 bg-gray-800 hover:bg-gray-900 text-white rounded-full flex items-center justify-center transition-colors">
                        <i class="fab fa-github"></i>
                    </a>
                    <?php endif; ?>
                        
                    <?php if (!empty($author_weibo)) : ?>
                    <a href="<?php echo esc_url($author_weibo); ?>" target="_blank" class="social-icon w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors">
                        <i class="fab fa-weibo"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($author_twitter)) : ?>
                    <a href="<?php echo esc_url($author_twitter); ?>" target="_blank" class="social-icon w-10 h-10 bg-blue-400 hover:bg-blue-500 text-white rounded-full flex items-center justify-center transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($author_wechat)) : ?>
                    <div class="relative group">
                        <div class="social-icon w-10 h-10 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center transition-colors cursor-pointer">
                            <i class="fab fa-weixin"></i>
                        </div>
                        <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 bg-white p-3 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-10">
                            <img src="<?php echo esc_url($author_wechat); ?>" alt="微信二维码" class="w-24 h-24">
                            <p class="text-xs text-center mt-2 text-gray-600">扫码加微信</p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- RSS订阅始终显示 -->
                    <a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>" target="_blank" class="social-icon w-10 h-10 bg-orange-500 hover:bg-orange-600 text-white rounded-full flex items-center justify-center transition-colors">
                        <i class="fas fa-rss"></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- AD1 广告位 - 站长信息下方 -->
    <?php if (xman_has_ad(1)) : ?>
    <div class="widget-container">
        <?php xman_show_sidebar_ad1(); ?>
    </div>
    <?php endif; ?>

    <!-- 日历小部件 -->
    <div class="widget-container bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300" style="margin-top: 40px;">
        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 text-white px-6 py-4">
            <div class="flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>
                <h3 class="text-lg font-bold">日历</h3>
            </div>
        </div>
        <div class="p-6">
            <div id="calendar-widget">
                <?php
                // 获取当前年月
                $current_year = date('Y');
                $current_month = date('n');
                $current_day = date('j');
                
                // 获取月份信息
                $first_day = mktime(0, 0, 0, $current_month, 1, $current_year);
                $days_in_month = date('t', $first_day);
                $start_day = date('w', $first_day); // 0=Sunday, 1=Monday, etc.
                
                // 月份名称
                $month_names = array(
                    1 => '一月', 2 => '二月', 3 => '三月', 4 => '四月',
                    5 => '五月', 6 => '六月', 7 => '七月', 8 => '八月',
                    9 => '九月', 10 => '十月', 11 => '十一月', 12 => '十二月'
                );
                
                // 星期名称
                $week_days = array('日', '一', '二', '三', '四', '五', '六');
                ?>
                
                <!-- 日历头部 -->
                <div class="text-center mb-4">
                    <h4 class="text-lg font-bold text-gray-900"><?php echo esc_html($current_year); ?>年 <?php echo esc_html($month_names[$current_month]); ?></h4>
                </div>
                
                <!-- 日历表格 -->
                <div class="calendar-grid">
                    <!-- 星期标题 -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <?php foreach ($week_days as $day): ?>
                            <div class="text-center text-sm font-medium text-gray-600 py-2"><?php echo esc_html($day); ?></div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- 日期网格 -->
                    <div class="grid grid-cols-7 gap-1">
                        <?php
                        // 填充月初空白
                        for ($i = 0; $i < $start_day; $i++) {
                            echo '<div class="h-8"></div>';
                        }
                        
                        // 填充日期
                        for ($day = 1; $day <= $days_in_month; $day++) {
                            $is_today = ($day == $current_day);
                            $date_string = sprintf('%04d-%02d-%02d', $current_year, $current_month, $day);
                            
                            // 查询当天发布的文章数量
                            $posts_count = get_posts(array(
                                'post_type' => 'post',
                                'post_status' => 'publish',
                                'date_query' => array(
                                    array(
                                        'year' => $current_year,
                                        'month' => $current_month,
                                        'day' => $day,
                                    ),
                                ),
                                'fields' => 'ids'
                            ));
                            $post_count = count($posts_count);
                            
                            $today_class = $is_today ? 'bg-blue-500 text-white' : 'hover:bg-gray-100';
                            $has_posts_class = $post_count > 0 ? 'font-bold' : '';
                            ?>
                            <div class="h-8 flex flex-col items-center justify-center text-sm <?php echo esc_attr($today_class); ?> <?php echo esc_attr($has_posts_class); ?> rounded transition-colors cursor-pointer relative group">
                                <span><?php echo esc_html($day); ?></span>
                                <?php if ($post_count > 0): ?>
                                    <div class="absolute -bottom-1 w-1 h-1 bg-green-500 rounded-full"></div>
                                    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10 whitespace-nowrap">
                                        <?php echo esc_html($post_count); ?> 篇文章
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 标签云小部件 -->
    <div class="widget-container bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300" style="margin-top: 40px;">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 text-white px-6 py-4">
            <div class="flex items-center">
                <i class="fas fa-tags mr-2"></i>
                <h3 class="text-lg font-bold">热门标签</h3>
            </div>
        </div>
        <div class="p-6">
        <div class="flex flex-wrap gap-2">
            <?php
            $tags = get_tags(array('number' => 12, 'orderby' => 'count', 'order' => 'DESC'));
            if ($tags) {
                foreach ($tags as $tag) {
                    echo '<a href="' . get_tag_link($tag->term_id) . '" class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-800 text-sm rounded-full transition-colors duration-200"><i class="fas fa-hashtag mr-1 text-xs"></i>' . $tag->name . '</a>';
                }
            } else {
                // 默认标签
                $default_tags = array(
                    'JavaScript', 'Vue.js', 'React', 'Node.js',
                    'TypeScript', 'CSS3', 'Docker', 'MongoDB',
                    'DevOps', '架构设计', '前端工程化', '性能优化'
                );
                foreach ($default_tags as $tag) {
                    echo '<a href="#" class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-800 text-sm rounded-full transition-colors duration-200"><i class="fas fa-hashtag mr-1 text-xs"></i>' . $tag . '</a>';
                }
            }
            ?>
        </div>
        </div>
    </div>

    <!-- 热门文章小部件 -->
    <div class="widget-container bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300" style="margin-top: 40px;">
        <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-4">
            <div class="flex items-center">
                <i class="fas fa-fire mr-2"></i>
                <h3 class="text-lg font-bold">热门文章</h3>
            </div>
        </div>
        <div class="p-6">
        <div class="space-y-2">
            <?php
            $popular_posts = get_posts(array(
                'posts_per_page' => 8,
                'meta_key' => 'post_views',
                'orderby' => 'meta_value_num',
                'order' => 'DESC'
            ));
            
            if ($popular_posts) {
                $index = 1;
                foreach ($popular_posts as $post) {
                    setup_postdata($post);
                    $views = get_post_meta(get_the_ID(), 'post_views', true) ?: rand(500, 1500);
                    
                    // 根据排名设置背景色深浅（1最深，8最浅）
                    $bg_colors = array(
                        1 => 'bg-red-600',
                        2 => 'bg-red-500', 
                        3 => 'bg-orange-500',
                        4 => 'bg-orange-400',
                        5 => 'bg-yellow-500',
                        6 => 'bg-yellow-400',
                        7 => 'bg-green-400',
                        8 => 'bg-green-300'
                    );
                    $bg_color = $bg_colors[$index] ?? 'bg-gray-400';
                    ?>
                    <article class="group py-2 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-start space-x-2">
                            <div class="<?php echo esc_attr($bg_color); ?> text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">
                                <?php echo esc_html($index); ?>
                            </div>
                            <div class="flex-1">
                                 <h4 class="text-sm font-medium text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors leading-snug">
                                     <a href="<?php the_permalink(); ?>" class="block"><?php echo get_the_title(); ?></a>
                                 </h4>
                             </div>
                        </div>
                    </article>
                    <?php
                    $index++;
                }
                wp_reset_postdata();
            } else {
                echo '<p class="text-gray-500 text-center py-4">暂无热门文章</p>';
            }
            ?>
        </div>
        </div>
    </div>

    <!-- 最新回复小工具 -->
    <div class="widget-container bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300" style="margin-top: 40px;">
        <div class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-6 py-4">
            <div class="flex items-center">
                <i class="fas fa-comments mr-2"></i>
                <h3 class="text-lg font-bold">最新回复</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <?php
                // 获取最新的5条评论
                $recent_comments = get_comments(array(
                    'status' => 'approve',
                    'number' => 5,
                    'type' => 'comment'
                ));
                
                if ($recent_comments) {
                    foreach ($recent_comments as $comment) {
                        $comment_post = get_post($comment->comment_post_ID);
                        $comment_content = wp_trim_words($comment->comment_content, 15, '...');
                        $comment_date = human_time_diff(strtotime($comment->comment_date), current_time('timestamp')) . '前';
                        ?>
                        <div class="group border-b border-gray-100 last:border-b-0 pb-4 last:pb-0">
                            <!-- 文章标题 -->
                            <div class="mb-2">
                                <a href="<?php echo esc_url(get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID); ?>" 
                                   class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors block" 
                                   title="<?php echo esc_attr($comment_post->post_title); ?>">
                                    <i class="fas fa-file-alt mr-1 text-gray-400"></i>
                                    <?php echo esc_html(wp_trim_words($comment_post->post_title, 10, '...')); ?>
                                </a>
                            </div>
                            
                            <!-- 评论内容 -->
                            <div class="text-sm text-gray-600 leading-relaxed ml-6">
                                <i class="fas fa-reply mr-2 text-gray-400"></i>
                                <?php echo esc_html($comment_content); ?>
                            </div>
                            
                            <!-- 评论者和时间 -->
                            <div class="flex items-center space-x-2 mt-2 text-xs text-gray-500">
                                <span><?php echo esc_html($comment->comment_author); ?></span>
                                <span>•</span>
                                <span><?php echo esc_html($comment_date); ?></span>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="text-center py-8">';
                    echo '<i class="fas fa-comment-slash text-4xl text-gray-300 mb-3"></i>';
                    echo '<p class="text-gray-500">暂无评论</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- AD2 广告位 - 热门文章和站长推荐之间 -->
    <?php if (xman_has_ad(2)) : ?>
    <div class="widget-container" style="margin-top: 40px;">
        <?php xman_show_sidebar_ad2(); ?>
    </div>
    <?php endif; ?>

    <?php
    // 从主题设置获取推荐站点
    $tool_items = array();
    $default_bg_classes = array('bg-gray-800', 'bg-orange-500', 'bg-green-600', 'bg-pink-500', 'bg-indigo-600');
    $default_hover_classes = array('hover:bg-gray-900', 'hover:bg-orange-600', 'hover:bg-green-700', 'hover:bg-pink-600', 'hover:bg-indigo-700');
    
    for ($i = 1; $i <= 5; $i++) {
        $title = get_option("xman_recommend_site_{$i}_title", '');
        $url = get_option("xman_recommend_site_{$i}_url", '');
        $icon = get_option("xman_recommend_site_{$i}_icon", 'fas fa-link');
        $desc = get_option("xman_recommend_site_{$i}_desc", '');
        
        // 只有当标题和URL都不为空时才添加
        if (!empty($title) && !empty($url)) {
            $color_index = (count($tool_items)) % 5;
            $tool_items[] = array(
                'icon' => $icon,
                'title' => $title,
                'desc' => $desc,
                'url' => $url,
                'bg_class' => $default_bg_classes[$color_index],
                'hover_class' => $default_hover_classes[$color_index]
            );
        }
    }
    
    // 只有当有推荐站点时才显示整个小部件
    if (!empty($tool_items)) :
    ?>
    <!-- 站长推荐小部件 -->
    <div class="widget-container bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300" style="margin-top: 40px;">
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-6 py-4">
            <div class="flex items-center">
                <i class="fas fa-star mr-2"></i>
                <h3 class="text-lg font-bold">站长推荐</h3>
            </div>
        </div>
        <div class="p-6">
        <div class="space-y-3">
            <?php foreach ($tool_items as $item) :
            ?>
                <a href="<?php echo esc_url($item['url']); ?>" target="_blank" class="block">
                    <div class="flex items-center p-3 <?php echo esc_attr($item['bg_class']); ?> rounded-lg <?php echo esc_attr($item['hover_class']); ?> transition-colors group">
                        <div class="w-10 h-10 text-white flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-white">
                                <?php echo esc_html($item['title']); ?>
                            </h4>
                            <p class="text-sm text-white opacity-90"><?php echo esc_html($item['desc']); ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- 网站统计小部件 -->
    <div class="widget-container bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300" style="margin-top: 40px;">
        <div class="bg-gradient-to-r from-orange-500 to-yellow-600 text-white px-6 py-4">
            <div class="flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                <h3 class="text-lg font-bold">网站统计</h3>
            </div>
        </div>
        <div class="p-6">
        <div class="grid grid-cols-1 gap-4">
            <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                <div class="w-12 h-12 bg-blue-500 text-white rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo wp_count_posts()->publish; ?></div>
                    <div class="text-sm text-gray-600">文章总数</div>
                </div>
            </div>

            <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg">
                <div class="w-12 h-12 bg-green-500 text-white rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-tags"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo wp_count_terms('post_tag'); ?></div>
                    <div class="text-sm text-gray-600">标签总数</div>
                </div>
            </div>
            
            <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg">
                <div class="w-12 h-12 bg-purple-500 text-white rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <?php 
                    $start_date = get_option('xman_site_start_date', '2020-01-01');
                    $start_timestamp = strtotime($start_date);
                    $current_timestamp = time();
                    $running_days = floor(($current_timestamp - $start_timestamp) / (24 * 60 * 60));
                    ?>
                    <div class="text-2xl font-bold text-gray-900"><?php echo esc_html($running_days); ?></div>
                    <div class="text-sm text-gray-600">运行天数</div>
                </div>
            </div>
        </div>
    </div>
</div>