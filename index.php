<?php
/**
 * X-Man AI主题 - 主模板文件
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

<main class="max-w-1500 mx-auto flex flex-col lg:flex-row gap-8 py-8 px-4 sm:px-6 lg:px-8">
        <!-- 主要内容区域 -->
        <div class="flex-1 lg:w-2/3">
            <?php if (is_home() && !is_paged()) : ?>
                <!-- 首页幻灯片 -->
                <section class="relative mb-12 rounded-2xl overflow-hidden shadow-2xl">
                    <div class="relative" style="height: 450px;">
                        <?php
                        // 获取最新文章作为幻灯片
                        $featured_posts = get_posts(array(
                            'posts_per_page' => 5,
                            'post_status' => 'publish',
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        $slide_index = 0;
                        foreach ($featured_posts as $post) :
                            setup_postdata($post);
                            $slide_index++;
                        ?>
                            <div class="slider-slide absolute inset-0 transition-opacity duration-500 <?php echo $slide_index === 1 ? 'opacity-100' : 'opacity-0'; ?>" data-slide="<?php echo esc_attr($slide_index); ?>">
                                <!-- 背景图片或色块 -->
                                <?php echo xman_get_slider_image($post->ID, 'w-full h-full object-cover'); ?>
                                
                                <!-- 渐变遮罩 -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent z-10"></div>
                                
                                <!-- 内容区域：中间偏下 -->
                                <div class="absolute inset-0 z-20 flex items-end pb-20">
                                    <div class="px-8 md:px-12 lg:px-16 max-w-3xl">
                                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight"><?php the_title(); ?></h2>
                                        <p class="text-lg md:text-xl text-gray-200 mb-6 leading-relaxed"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                    </div>
                                </div>
                                
                                <!-- 阅读更多按钮：右下角 -->
                                <div class="absolute bottom-8 right-8 z-30">
                                    <a href="<?php the_permalink(); ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-lg">
                                        阅读更多 <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                        
                        <!-- 导航按钮 -->
                        <div class="absolute inset-y-0 left-4 flex items-center z-30">
                            <button class="p-3 bg-white/20 hover:bg-white/30 text-white rounded-full transition-colors duration-200 backdrop-blur-sm" id="prevSlide">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        </div>
                        <div class="absolute inset-y-0 right-4 flex items-center z-30">
                            <button class="p-3 bg-white/20 hover:bg-white/30 text-white rounded-full transition-colors duration-200 backdrop-blur-sm" id="nextSlide">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        
                        <!-- 指示点 -->
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-30">
                            <?php for ($i = 1; $i <= count($featured_posts); $i++) : ?>
                                <button class="w-6 h-6 rounded-full transition-colors duration-200 <?php echo $i === 1 ? 'bg-white' : 'bg-white/50'; ?>" data-slide="<?php echo esc_attr($i); ?>"></button>
                            <?php endfor; ?>
                        </div>
                    </div>
                </section>
                
                <!-- 快捷链接 -->
                <section class="mb-12">
                    <div class="grid grid-cols-5 gap-3">
                        <?php
                        // 从主题设置获取快捷链接
                        $quick_links = array();
                        $default_colors = array(
                            array('bg_color' => 'bg-blue-500', 'hover_color' => 'hover:bg-blue-600'),
                            array('bg_color' => 'bg-green-500', 'hover_color' => 'hover:bg-green-600'),
                            array('bg_color' => 'bg-purple-500', 'hover_color' => 'hover:bg-purple-600'),
                            array('bg_color' => 'bg-orange-500', 'hover_color' => 'hover:bg-orange-600'),
                            array('bg_color' => 'bg-red-500', 'hover_color' => 'hover:bg-red-600')
                        );
                        
                        for ($i = 1; $i <= 5; $i++) {
                            $title = get_option("xman_quick_link_{$i}_title", '');
                            $url = get_option("xman_quick_link_{$i}_url", '#');
                            $icon = get_option("xman_quick_link_{$i}_icon", 'fas fa-link');
                            $desc = get_option("xman_quick_link_{$i}_desc", '');
                            
                            if (!empty($title)) {
                                $color_index = count($quick_links) % 5;
                                $quick_links[] = array(
                                    'title' => $title,
                                    'url' => $url,
                                    'icon' => $icon,
                                    'desc' => $desc,
                                    'bg_color' => $default_colors[$color_index]['bg_color'],
                                    'hover_color' => $default_colors[$color_index]['hover_color']
                                );
                            }
                        }
                        
                        // 如果没有设置，使用文章分类数据
                        if (empty($quick_links)) {
                            $categories = get_categories(array('number' => 5, 'orderby' => 'count', 'order' => 'DESC'));
                            $category_colors = array(
                                'bg-blue-500' => 'hover:bg-blue-600',
                                'bg-green-500' => 'hover:bg-green-600',
                                'bg-purple-500' => 'hover:bg-purple-600',
                                'bg-orange-500' => 'hover:bg-orange-600',
                                'bg-red-500' => 'hover:bg-red-600'
                            );
                            $category_icons = array('fas fa-code', 'fas fa-laptop', 'fas fa-mobile-alt', 'fas fa-database', 'fas fa-cogs');
                            
                            $quick_links = array();
                            $color_index = 0;
                            foreach ($categories as $category) {
                                $bg_color = array_keys($category_colors)[$color_index % 5];
                                $hover_color = $category_colors[$bg_color];
                                $icon = $category_icons[$color_index % 5];
                                
                                $quick_links[] = array(
                                    'title' => $category->name,
                                    'url' => get_category_link($category->term_id),
                                    'icon' => $icon,
                                    'desc' => $category->count . ' 篇文章',
                                    'bg_color' => $bg_color,
                                    'hover_color' => $hover_color
                                );
                                $color_index++;
                                if (count($quick_links) >= 5) break;
                            }
                            
                            // 如果分类不足5个，用默认分类补充
                            if (count($quick_links) < 5) {
                                $default_categories = array(
                                    array('title' => '前端开发', 'url' => '#', 'icon' => 'fas fa-code', 'desc' => '0 篇文章', 'bg_color' => 'bg-blue-500', 'hover_color' => 'hover:bg-blue-600'),
                                    array('title' => '后端开发', 'url' => '#', 'icon' => 'fas fa-server', 'desc' => '0 篇文章', 'bg_color' => 'bg-green-500', 'hover_color' => 'hover:bg-green-600'),
                                    array('title' => '移动开发', 'url' => '#', 'icon' => 'fas fa-mobile-alt', 'desc' => '0 篇文章', 'bg_color' => 'bg-purple-500', 'hover_color' => 'hover:bg-purple-600'),
                                    array('title' => '数据库', 'url' => '#', 'icon' => 'fas fa-database', 'desc' => '0 篇文章', 'bg_color' => 'bg-orange-500', 'hover_color' => 'hover:bg-orange-600'),
                                    array('title' => '运维部署', 'url' => '#', 'icon' => 'fas fa-cogs', 'desc' => '0 篇文章', 'bg_color' => 'bg-red-500', 'hover_color' => 'hover:bg-red-600')
                                );
                                
                                for ($i = count($quick_links); $i < 5; $i++) {
                                    $quick_links[] = $default_categories[$i];
                                }
                            }
                        }
                        
                        foreach ($quick_links as $link) :
                            $bg_color = isset($link['bg_color']) ? $link['bg_color'] : 'bg-blue-500';
                            $hover_color = isset($link['hover_color']) ? $link['hover_color'] : 'hover:bg-blue-600';
                        ?>
                            <a href="<?php echo esc_url($link['url']); ?>" class="group <?php echo esc_attr($bg_color); ?> <?php echo esc_attr($hover_color); ?> rounded-lg p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1" target="_blank">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-16 h-16 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                        <i class="<?php echo esc_attr($link['icon']); ?> text-white text-2xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-white mb-1 text-sm"><?php echo esc_html($link['title']); ?></h3>
                                    <p class="text-xs text-white text-opacity-80"><?php echo esc_html($link['desc']); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <!-- 置顶文章 -->
                <?php
                $sticky_posts = get_option('sticky_posts');
                if (!empty($sticky_posts)) :
                    // 随机选择一个置顶文章
                    shuffle($sticky_posts);
                    $sticky_query = new WP_Query(array(
                        'post__in' => array($sticky_posts[0]),
                        'posts_per_page' => 1,
                        'ignore_sticky_posts' => 1
                    ));
                    
                    if ($sticky_query->have_posts()) :
                        $sticky_query->the_post();
                ?>
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-thumbtack text-blue-500 mr-3"></i>
                        置顶推荐
                    </h2>
                    <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 relative border border-gray-100 hover:border-blue-200">
                        <!-- 置顶图标 -->
                        <div class="absolute top-4 left-4 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold z-10 flex items-center">
                            <i class="fas fa-thumbtack mr-1"></i>
                            置顶
                        </div>
                        <div class="md:flex md:h-auto">
                            <div class="md:w-1/3 md:flex md:items-stretch">
                                <a href="<?php the_permalink(); ?>" class="block w-full">
                                    <?php echo xman_get_post_thumbnail(get_the_ID(), 'medium', 'w-full h-48 md:h-full object-cover'); ?>
                                </a>
                            </div>
                            
                            <div class="md:w-2/3 p-6">
                                <h3 class="text-2xl font-bold text-gray-900 mb-1 hover:text-blue-600 transition-colors duration-200">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <div class="text-gray-700 mb-4 leading-relaxed whitespace-pre-line">
                                    <?php 
                                    $excerpt = get_the_excerpt();
                                    if (empty($excerpt)) {
                                        $excerpt = wp_trim_words(get_the_content(), 100, '...');
                                    }
                                    echo wp_trim_words($excerpt, 100, '...');
                                    ?>
                                </div>
                                
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <?php
                                    $tags = get_the_tags();
                                    if ($tags) :
                                        foreach (array_slice($tags, 0, 3) as $tag) :
                                    ?>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full"><?php echo esc_html($tag->name); ?></span>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center text-sm text-gray-600 flex-wrap gap-x-4">
                                        <span class="flex items-center">
                                            <i class="fas fa-user mr-1"></i>
                                            <?php echo get_the_author(); ?>
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            <?php echo get_the_date('Y年m月d日'); ?>
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-folder mr-1"></i>
                                            <?php 
                                            $categories = get_the_category();
                                            if (!empty($categories)) {
                                                echo esc_html($categories[0]->name);
                                            }
                                            ?>
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            <?php echo get_post_meta(get_the_ID(), 'post_views', true) ?: rand(100, 1000); ?>
                                        </span>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <i class="fas fa-arrow-right mr-2"></i> 继续阅读
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>
                <?php
                    wp_reset_postdata();
                    endif;
                endif;
                ?>
            <?php endif; ?>
            
            <!-- 文章列表 -->
            <section class="mb-12">
                <?php 
                // 如果是首页且未分页，排除幻灯片中的文章
                if (is_home() && !is_paged()) {
                    $featured_post_ids = array();
                    foreach ($featured_posts as $featured_post) {
                        $featured_post_ids[] = $featured_post->ID;
                    }
                    
                    // 重新查询，排除幻灯片文章
                    $main_query = new WP_Query(array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => get_option('posts_per_page'),
                        'paged' => get_query_var('paged'),
                        'post__not_in' => $featured_post_ids,
                        'ignore_sticky_posts' => 1
                    ));
                    $posts_query = $main_query;
                } else {
                    global $wp_query;
                    $posts_query = $wp_query;
                }
                
                if ($posts_query->have_posts()) : ?>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-newspaper text-blue-500 mr-3"></i> 最新文章
                    </h2>
                    <div class="space-y-6">
                        <?php 
                        $post_count = 0;
                        while ($posts_query->have_posts()) : $posts_query->the_post(); 
                        $post_count++;
                        ?>
                            <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200 hover:transform hover:scale-[1.02]">
                                <div class="md:flex md:h-auto">
                                    <div class="md:w-1/3 md:flex md:items-stretch">
                                        <a href="<?php the_permalink(); ?>" class="block w-full">
                                            <?php echo xman_get_post_thumbnail(get_the_ID(), 'medium', 'w-full h-48 md:h-full object-cover'); ?>
                                        </a>
                                    </div>
                                    <div class="md:w-2/3 p-6">
                                        <h3 class="text-2xl font-bold text-gray-900 mb-1 hover:text-blue-600 transition-colors duration-200">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        
                                        <div class="text-gray-700 mb-4 leading-relaxed whitespace-pre-line">
                                            <?php 
                                            $excerpt = get_the_excerpt();
                                            if (empty($excerpt)) {
                                                $excerpt = wp_trim_words(get_the_content(), 100, '...');
                                            }
                                            echo wp_trim_words($excerpt, 100, '...');
                                            ?>
                                        </div>
                                        
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            <?php 
                                            $tags = get_the_tags();
                                            if ($tags) {
                                                $tag_count = 0;
                                                foreach ($tags as $tag) {
                                                    if ($tag_count >= 3) break; // 最多显示3个标签
                                                    echo '<span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex items-center"><i class="fas fa-tag mr-1"></i>' . esc_html($tag->name) . '</span>';
                                                    $tag_count++;
                                                }
                                            }
                                            ?>
                                        </div>
                                        
                                        <div class="flex items-center justify-between flex-wrap gap-2">
                                            <div class="flex items-center text-sm text-gray-600 flex-wrap gap-x-4">
                                                <span class="flex items-center">
                                                    <i class="fas fa-user mr-1"></i>
                                                    <?php echo get_the_author(); ?>
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    <?php echo get_the_date('Y年m月d日'); ?>
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-folder mr-1"></i>
                                                    <?php 
                                                    $categories = get_the_category();
                                                    if (!empty($categories)) {
                                                        echo esc_html($categories[0]->name);
                                                    }
                                                    ?>
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    <?php echo get_post_meta(get_the_ID(), 'post_views', true) ?: rand(100, 1000); ?>
                                                </span>
                                            </div>
                                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                <i class="fas fa-arrow-right mr-2"></i> 继续阅读
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            
                            <?php 
                            // 在第3篇文章后插入广告位
                            if ($post_count == 3 && xman_has_ad(5)) : 
                                xman_show_home_list_ad();
                            endif; 
                            ?>
                        <?php endwhile; 
                        if (is_home() && !is_paged()) {
                            wp_reset_postdata();
                        }
                        ?>
                    </div>
                    
                    <!-- 分页导航 -->
                    <div class="mt-8">
                        <?php
                        $pagination = paginate_links(array(
                            'prev_text' => '<i class="fas fa-chevron-left mr-2"></i>上一页',
                            'next_text' => '下一页<i class="fas fa-chevron-right ml-2"></i>',
                            'type' => 'array'
                        ));
                        
                        if ($pagination) {
                            echo '<nav class="flex justify-center">';
                            echo '<ul class="flex items-center space-x-2">';
                            foreach ($pagination as $page) {
                                echo '<li>' . str_replace(
                                    array('page-numbers', 'current'),
                                    array('px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200 border border-gray-300 text-gray-700 hover:bg-gray-50', 'px-4 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white border border-blue-600'),
                                    $page
                                ) . '</li>';
                            }
                            echo '</ul>';
                            echo '</nav>';
                        }
                        ?>
                    </div>
                <?php else : ?>
                    <div class="text-center py-12">
                        <i class="fas fa-file-alt text-gray-300 text-6xl mb-4"></i>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">暂无文章</h2>
                        <p class="text-gray-600">还没有发布任何文章。</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
        
        <!-- 侧边栏 -->
        <aside class="w-full lg:w-1/3">
            <?php get_sidebar(); ?>
        </aside>
    </main>

<?php get_footer(); ?>