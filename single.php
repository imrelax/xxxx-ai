<?php
/**
 * X-Man AI主题 - 单篇文章模板
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

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
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="rounded-lg overflow-hidden">
                                <?php the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
                            </div>
                        <?php endif; ?>
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
                        
                        // 获取带图文章（前4个）
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
                        
                        // 获取纯文字文章（10个）
                        $exclude_ids = array(get_the_ID());
                        foreach ($related_posts_with_image as $post_with_image) {
                            $exclude_ids[] = $post_with_image->ID;
                        }
                        
                        $related_posts_text_only = get_posts(array(
                            'category__in' => $category_ids,
                            'post__not_in' => $exclude_ids,
                            'posts_per_page' => 10,
                            'orderby' => 'rand'
                        ));
                        
                        if ($related_posts_with_image || $related_posts_text_only) :
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
                                <?php foreach ($related_posts_with_image as $post) : setup_postdata($post); ?>
                                    <article class="rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                        <div class="aspect-video overflow-hidden relative">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php echo xman_get_post_thumbnail(get_the_ID(), 'medium', 'w-full h-full object-cover hover:scale-105 transition-transform duration-300'); ?>
                                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                                    <div class="p-4 text-center max-w-full">
                                                        <h4 class="font-semibold text-white hover:text-blue-200 transition-colors break-words">
                                                            <?php echo wp_trim_words(get_the_title(), 20); ?>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </article>
                                <?php endforeach; wp_reset_postdata(); ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($related_posts_text_only) : ?>
                            <!-- 纯文字文章 -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-list text-blue-500 mr-2"></i> 更多相关文章
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <?php foreach ($related_posts_text_only as $post) : setup_postdata($post); ?>
                                        <article class="border-l-4 border-blue-200 pl-4 py-2 hover:border-blue-400 transition-colors">
                                            <h5 class="font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                                <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 20); ?></a>
                                            </h5>
                                        </article>
                                    <?php endforeach; wp_reset_postdata(); ?>
                                </div>
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