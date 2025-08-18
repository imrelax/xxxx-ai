<?php
/**
 * X-Man AI主题 - 单篇文章模板
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

<?php 
// 如果是软件类型的文章，显示软件信息模块
if (xman_ai_is_software_post(get_the_ID())) :
    $software_info = xman_ai_get_software_info(get_the_ID());
?>
    <!-- 软件信息展示模块 - 全宽显示 -->
    <div class="w-full pt-8 pb-3">
        <div class="max-w-1500 mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- 软件图标 -->
                <div class="flex-shrink-0">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-2xl overflow-hidden shadow-lg bg-white p-2">
                            <?php the_post_thumbnail('thumbnail', array('class' => 'w-full h-full object-cover rounded-xl')); ?>
                        </div>
                    <?php else : ?>
                        <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                            <i class="fas fa-download text-white text-3xl lg:text-4xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- 软件基本信息 -->
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3"><?php the_title(); ?></h2>
                    
                    <?php if (!empty($software_info['intro'])) : ?>
                        <p class="text-gray-700 mb-4 leading-relaxed"><?php echo esc_html($software_info['intro']); ?></p>
                    <?php endif; ?>
                    
                    <!-- 软件详细信息网格 -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <?php if (!empty($software_info['version'])) : ?>
                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-code-branch text-blue-500 mr-2"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">版本</div>
                                        <div class="font-semibold text-gray-900">v<?php echo esc_html($software_info['version']); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($software_info['size'])) : ?>
                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-hdd text-green-500 mr-2"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">大小</div>
                                        <div class="font-semibold text-gray-900"><?php echo esc_html($software_info['size']); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($software_info['developer'])) : ?>
                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-user-tie text-purple-500 mr-2"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">开发者</div>
                                        <div class="font-semibold text-gray-900"><?php echo esc_html($software_info['developer']); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($software_info['license_label'])) : ?>
                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-certificate text-orange-500 mr-2"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">许可</div>
                                        <div class="font-semibold text-gray-900"><?php echo esc_html($software_info['license_label']); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- 适用设备 -->
                    <?php if (!empty($software_info['devices'])) : ?>
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-mobile-alt text-blue-500 mr-1"></i>适用设备</h4>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($software_info['devices'] as $device) : ?>
                                    <?php if (isset($software_info['device_labels'][$device])) : ?>
                                        <?php 
                                        // 为不同设备类型定义图标
                                        $device_icons = array(
                                            'ios' => 'fab fa-apple',
                                            'android' => 'fab fa-android',
                                            'macos' => 'fab fa-apple',
                                            'windows' => 'fab fa-windows',
                                            'linux' => 'fab fa-linux',
                                            'hongmeng' => 'fas fa-mobile-alt',
                                            'router' => 'fas fa-wifi',
                                            'other' => 'fas fa-desktop'
                                        );
                                        $icon = isset($device_icons[$device]) ? $device_icons[$device] : 'fas fa-desktop';
                                        ?>
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                            <i class="<?php echo esc_attr($icon); ?> mr-1"></i>
                                            <?php echo esc_html($software_info['device_labels'][$device]); ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- 下载地址 -->
                    <?php if (!empty($software_info['downloads'])) : ?>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">下载地址</h4>
                            <div class="flex flex-wrap gap-3">
                                <?php foreach ($software_info['downloads'] as $device => $download) : ?>
                                    <?php if (!empty($download['url'])) : ?>
                                        <?php 
                                        $device_label = isset($software_info['device_labels'][$device]) ? $software_info['device_labels'][$device] : $device;
                                        $button_text = $device_label;
                                        if (!empty($download['note'])) {
                                            $button_text .= ' (' . $download['note'] . ')';
                                        }
                                        ?>
                                        <a href="<?php echo esc_url($download['url']); ?>" target="_blank" rel="nofollow" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                                            <i class="fas fa-download mr-2"></i>
                                            <?php echo esc_html($button_text); ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<main class="max-w-1500 mx-auto flex flex-col lg:flex-row gap-8 py-8 px-4 sm:px-6 lg:px-8">
        <!-- 文章内容区域 -->
        <div class="flex-1 lg:w-2/3">
            <?php 
            // 显示面包屑导航
            xman_breadcrumb();
            ?>
            <?php while (have_posts()) : the_post(); ?>
                
                <article class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Header装饰线 -->
                    <div class="header-decoration-line"></div>
                    <!-- 文章头部 -->
                    <header class="p-8 border-b border-gray-100">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight"><?php the_title(); ?></h1>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6">
                            <span class="flex items-center">
                                <i class="fas fa-user text-blue-500 mr-2"></i>
                                <?php 
                                // 优先显示主题设置的站长信息，如果没有设置则显示系统内置名称
                                $site_author = get_option('xman_author_name', '');
                                if (!empty($site_author)) {
                                    echo esc_html($site_author);
                                } else {
                                    $system_author = get_the_author();
                                    echo esc_html($system_author ? $system_author : 'admin');
                                }
                                ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar text-green-500 mr-2"></i>
                                <?php echo get_the_date('Y年m月d日'); ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-tag text-purple-500 mr-2"></i>
                                <?php 
                                $categories = get_the_category();
                                if (!empty($categories)) {
                                    echo '<a href="' . get_category_link($categories[0]->term_id) . '" class="text-purple-600 hover:text-purple-800">' . esc_html($categories[0]->name) . '</a>';
                                }
                                ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye text-orange-500 mr-2"></i>
                                <?php echo xman_get_post_views(get_the_ID()); ?> 次浏览
                            </span>
                        </div>
                        
                        <!-- AD3 广告位 - 特色图片上方 -->
                        <?php if (xman_has_ad(3)) : ?>
                            <div class="xman-ad xman-ad-3 content-ad-header mb-4">
                                <?php echo xman_ai_get_ad_code(3); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php 
                        // 已禁用特色图片显示 - 根据用户要求不在文章中显示特色图片
                        // if (has_post_thumbnail() && !xman_ai_is_software_post(get_the_ID())) : 
                        ?>
                            <!-- <div class="rounded-lg overflow-hidden mt-8">
                                <?php // the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
                            </div> -->
                        <?php // endif; ?>
                    </header>
                    

                    
                    <!-- 文章内容 -->
                    <div class="p-8 prose prose-lg max-w-none">
                        <?php the_content(); ?>
                        
                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="flex flex-wrap gap-2 mt-8 pt-6 border-t border-gray-200">',
                            'after'  => '</div>',
                            'link_before' => '<span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 transition-colors">',
                            'link_after'  => '</span>',
                        ));
                        ?>
                    </div>
                    
                    <!-- 文章标签 -->
                    <?php if (has_tag()) : ?>
                        <div class="px-8 py-6 border-t border-gray-100">
                            <h4 class="flex items-center text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-tags text-blue-500 mr-2"></i> 文章标签
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                <?php 
                                $tags = get_the_tags();
                                if ($tags) {
                                    foreach ($tags as $tag) {
                                        echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-blue-100 hover:text-blue-800 transition-colors">' . esc_html($tag->name) . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    

                    
                    <!-- 文章导航 -->
                    <nav class="px-8 py-6 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row justify-between gap-4">
                            <?php
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            ?>
                            
                            <?php if ($prev_post) : ?>
                                <div class="flex-1">
                                    <a href="<?php echo get_permalink($prev_post); ?>" class="block p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors group">
                                        <span class="text-sm text-gray-500 flex items-center mb-1">
                                            <i class="fas fa-chevron-left mr-1"></i> 上一篇
                                        </span>
                                        <span class="text-gray-900 font-medium group-hover:text-blue-600 leading-relaxed break-words"><?php echo wp_trim_words($prev_post->post_title, 28, '...'); ?></span>
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="flex-1"></div>
                            <?php endif; ?>
                            
                            <?php if ($next_post) : ?>
                                <div class="flex-1">
                                    <a href="<?php echo get_permalink($next_post); ?>" class="block p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors group text-right">
                                        <span class="text-sm text-gray-500 flex items-center justify-end mb-1">
                                            下一篇 <i class="fas fa-chevron-right ml-1"></i>
                                        </span>
                                        <span class="text-gray-900 font-medium group-hover:text-blue-600 leading-relaxed break-words"><?php echo wp_trim_words($next_post->post_title, 28, '...'); ?></span>
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="flex-1"></div>
                            <?php endif; ?>
                        </div>
                    </nav>
                    
                    <!-- 相关文章 -->
                    <?php
                    $categories = get_the_category();
                    if ($categories) {
                        $category_ids = array();
                        foreach ($categories as $category) {
                            $category_ids[] = $category->term_id;
                        }
                        
                        // 使用优化的相关文章获取函数
                        $related_posts_with_image = xman_optimized_get_related_posts(get_the_ID(), 4);
                        
                        // 如果没有获取到相关文章，使用默认查询（优先有特色图片的）
                        if (empty($related_posts_with_image)) {
                            $related_posts_with_image = get_posts(array(
                                'category__in' => $category_ids,
                                'post__not_in' => array(get_the_ID()),
                                'posts_per_page' => 4,
                                'orderby' => 'rand',
                                'meta_query' => array(
                                    array(
                                        'key' => '_thumbnail_id',
                                        'compare' => 'EXISTS'
                                    )
                                )
                            ));
                            
                            // 如果还是没有，则获取任意相关文章
                            if (empty($related_posts_with_image)) {
                                $related_posts_with_image = get_posts(array(
                                    'category__in' => $category_ids,
                                    'post__not_in' => array(get_the_ID()),
                                    'posts_per_page' => 4,
                                    'orderby' => 'rand'
                                ));
                            }
                        }
                        

                        
                        if ($related_posts_with_image) :
                    ?>
                        <!-- AD4 广告位 - 相关文章上方 -->
                        <?php if (xman_has_ad(4)) : ?>
                            <div class="xman-ad xman-ad-4 content-ad-related px-8 py-4 border-t border-gray-100">
                                <?php echo xman_ai_get_ad_code(4); ?>
                            </div>
                        <?php endif; ?>
                        
                        <section class="px-8 py-6 <?php echo xman_has_ad(4) ? '' : 'border-t border-gray-100'; ?>">
                            <h3 class="related-posts-title flex items-center text-xl font-bold">
                                <i class="fas fa-heart text-red-500 mr-2"></i> 相关文章
                            </h3>
                            
                            <?php if ($related_posts_with_image) : ?>
                            <!-- 带图文章 -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                                <?php 
                                $count = 0;
                                foreach ($related_posts_with_image as $related_post) : 
                                    if ($count >= 4) break; // 限制只显示4篇
                                    $count++;
                                ?>
                                    <article class="rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                        <?php 
                                        // 检查是否有实际图片（只检查特色图片，不包括内容中的图片）
                        $has_actual_image = has_post_thumbnail($related_post->ID);
                                        $container_class = $has_actual_image ? 'aspect-video overflow-hidden relative' : 'aspect-video overflow-hidden relative bg-transparent';
                                        ?>
                                        <div class="<?php echo $container_class; ?>">
                                            <a href="<?php echo get_permalink($related_post->ID); ?>">
                                                <?php 
                                                $thumbnail_html = xman_get_post_thumbnail($related_post->ID, 'medium', 'w-full h-full object-cover hover:scale-105 transition-transform duration-300');
                                                echo $thumbnail_html;
                                                
                                                // 只有当有实际图片时才显示遮罩层和标题
                                                if ($has_actual_image) :
                                                ?>
                                                <div class="absolute inset-0 bg-transparent flex items-center justify-center">
                                                    <div class="p-4 text-center max-w-full">
                                                        <h4 class="font-semibold text-white hover:text-blue-200 transition-colors break-words">
                                                            <?php echo wp_trim_words($related_post->post_title, 20); ?>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <?php else : ?>
                                                <!-- 对于占位图，标题直接显示在占位图内部，不需要额外遮罩 -->
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            

                        </section>
                    <?php 
                        endif;
                    }
                    ?>
                </article>
                
                <!-- 评论区域 -->
                <?php
                // 如果评论开启或者有评论存在，显示评论模板
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
                
            <?php endwhile; ?>
        </div>
        
        <!-- 侧边栏 -->
        <aside class="w-full lg:w-1/3">
            <?php get_sidebar(); ?>
        </aside>
    </main>



<?php get_footer(); ?>