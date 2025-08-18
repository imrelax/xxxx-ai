<?php
/**
 * Template Name: 专题展示
 * 专题展示页面模板 - 显示所有专题
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

<main class="max-w-1500 mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- 页面标题 -->
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">专题</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">精心整理的专题内容，帮您快速找到相关文章</p>
    </div>

    <?php
    // 分页设置
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $posts_per_page = 8; // 每页显示8个专题，提升加载速度
    
    // 获取已发布的专题（分页）
    $collections_query = new WP_Query(array(
        'post_type' => 'topic',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    $collections = $collections_query->posts;
    
    // 预加载所有专题的元数据以提升性能
    $collection_ids = wp_list_pluck($collections, 'ID');
    if (!empty($collection_ids)) {
        // 预加载所有专题的元数据以提升性能
        $meta_keys = array('_topic_intro', '_topic_articles', '_topic_featured_software', '_topic_pinned_article');
        foreach ($meta_keys as $meta_key) {
            update_meta_cache('post', $collection_ids);
        }
        
        // 预加载专题的缩略图信息
        foreach ($collection_ids as $id) {
            if (has_post_thumbnail($id)) {
                get_the_post_thumbnail_url($id, 'medium_large');
            }
        }
    }
    
    if ($collections) :
    ?>
        <!-- 专题网格 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <?php foreach ($collections as $collection) : 
                setup_postdata($collection);
                $collection_id = $collection->ID;
                $collection_intro = get_post_meta($collection_id, '_topic_intro', true);
                $article_ids = get_post_meta($collection_id, '_topic_articles', true);
                $featured_software_id = get_post_meta($collection_id, '_topic_featured_software', true);
                $pinned_article_id = get_post_meta($collection_id, '_topic_pinned_article', true);
                $article_count = 0;
                
                if (!empty($article_ids)) {
                    $article_ids_array = explode(',', $article_ids);
                    $article_count = count(array_filter($article_ids_array));
                }
                
                // 获取专题图片，如果没有则尝试获取置顶软件或文章的图片
                $topic_image_url = '';
                if (has_post_thumbnail($collection_id)) {
                    $topic_image_url = get_the_post_thumbnail_url($collection_id, 'medium_large');
                } else {
                    // 尝试获取置顶软件的图片
                    if (!empty($featured_software_id) && has_post_thumbnail($featured_software_id)) {
                        $topic_image_url = get_the_post_thumbnail_url($featured_software_id, 'medium_large');
                    }
                    // 如果还没有图片，尝试获取置顶文章的图片
                    elseif (!empty($pinned_article_id) && has_post_thumbnail($pinned_article_id)) {
                        $topic_image_url = get_the_post_thumbnail_url($pinned_article_id, 'medium_large');
                    }
                    // 如果还没有图片，尝试获取第一篇文章的图片
                    elseif (!empty($article_ids)) {
                        $first_article_id = intval(explode(',', $article_ids)[0]);
                        if ($first_article_id && has_post_thumbnail($first_article_id)) {
                            $topic_image_url = get_the_post_thumbnail_url($first_article_id, 'medium_large');
                        }
                    }
                }
                
                // 限制标题长度为40个字符
                $collection_title = get_the_title($collection_id);
                if (mb_strlen($collection_title) > 40) {
                    $collection_title = mb_substr($collection_title, 0, 40) . '...';
                }
            ?>
                <article class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <!-- 专题封面图片 -->
                    <a href="<?php echo get_permalink($collection_id); ?>" class="block">
                        <div class="relative h-48 bg-gradient-to-br from-blue-500 to-purple-600 overflow-hidden">
                            <?php if (!empty($topic_image_url)) : ?>
                                <img src="<?php echo esc_url($topic_image_url); ?>" 
                                     alt="<?php echo esc_attr($collection_title); ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy"
                                     decoding="async">
                            <?php else : ?>
                                <!-- 默认渐变背景 -->
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <i class="fas fa-layer-group text-4xl mb-2 opacity-80"></i>
                                        <p class="text-sm opacity-70">专题封面</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                             <!-- 文章数量标签 -->
                             <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1 text-xs font-medium text-gray-700">
                                 <i class="fas fa-file-alt mr-1"></i>
                                 <?php echo $article_count; ?> 篇
                             </div>
                         </div>
                     </a>
                    
                    <!-- 专题信息 -->
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                            <a href="<?php echo get_permalink($collection_id); ?>" class="hover:underline">
                                <?php echo esc_html($collection_title); ?>
                            </a>
                        </h2>
                        <?php if (!empty($collection_intro)) : ?>
                            <p class="text-gray-600 leading-relaxed mb-4">
                                <?php echo wp_trim_words($collection_intro, 25, '...'); ?>
                            </p>
                        <?php endif; ?>
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span class="flex items-center">
                                <i class="fas fa-calendar text-green-500 mr-1"></i>
                                <?php echo get_the_date('Y年m月d日', $collection_id); ?>
                            </span>
                        </div>
                        
                        <?php 
                        // 显示置顶软件或置顶文章
                        if (!empty($featured_software_id) || !empty($pinned_article_id)) : ?>
                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <h4 class="text-sm font-semibold text-yellow-800 mb-2 flex items-center">
                                    <i class="fas fa-thumbtack text-yellow-600 mr-2"></i>
                                    置顶推荐
                                </h4>
                                <?php if (!empty($featured_software_id)) : 
                                    $featured_software = get_post($featured_software_id);
                                    if ($featured_software) : ?>
                                        <a href="<?php echo get_permalink($featured_software_id); ?>" class="block">
                                            <div class="flex items-center py-1.5 px-2 bg-white rounded-md hover:bg-yellow-100 transition-colors">
                                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 flex-shrink-0"></div>
                                                <span class="text-sm text-gray-700 font-medium hover:text-blue-600 transition-colors">
                                                    <i class="fas fa-download text-blue-500 mr-1"></i>
                                                    <?php echo esc_html($featured_software->post_title); ?>
                                                </span>
                                            </div>
                                        </a>
                                    <?php endif; 
                                endif; ?>
                                <?php if (!empty($pinned_article_id)) : 
                                    $pinned_article = get_post($pinned_article_id);
                                    if ($pinned_article) : ?>
                                        <a href="<?php echo get_permalink($pinned_article_id); ?>" class="block">
                                            <div class="flex items-center py-1.5 px-2 bg-white rounded-md hover:bg-yellow-100 transition-colors">
                                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 flex-shrink-0"></div>
                                                <span class="text-sm text-gray-700 font-medium hover:text-blue-600 transition-colors">
                                                    <i class="fas fa-star text-yellow-500 mr-1"></i>
                                                    <?php echo esc_html($pinned_article->post_title); ?>
                                                </span>
                                            </div>
                                        </a>
                                    <?php endif; 
                                endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($article_count > 0) : 
                            // 获取前5篇文章作为预览
                            $preview_ids = array_slice(array_filter(explode(',', $article_ids)), 0, 5);
                            if (!empty($preview_ids)) :
                                // 批量获取文章信息以提升性能
                                $preview_posts = get_posts(array(
                                    'post__in' => $preview_ids,
                                    'post_status' => 'publish',
                                    'orderby' => 'post__in',
                                    'posts_per_page' => 5
                                ));
                        ?>
                            <div class="space-y-2 mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-list text-blue-500 mr-2"></i>
                                    文章预览
                                </h4>
                                <?php foreach ($preview_posts as $article) : ?>
                                    <div class="flex items-center py-1.5 px-2 bg-gray-50 rounded-md hover:bg-blue-50 transition-colors">
                                        <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2 flex-shrink-0"></div>
                                        <span class="text-xs text-gray-700 truncate">
                                            <?php echo wp_trim_words($article->post_title, 12, '...'); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                                <?php if ($article_count > 5) : ?>
                                    <div class="text-center pt-1">
                                        <span class="text-xs text-gray-500">还有 <?php echo ($article_count - 5); ?> 篇文章...</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; endif; ?>

                    </div>
                </article>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
        
        <?php
        // 分页导航
        if ($collections_query->max_num_pages > 1) :
            $pagination_args = array(
                'total' => $collections_query->max_num_pages,
                'current' => $paged,
                'prev_text' => '<i class="fas fa-chevron-left mr-2"></i>上一页',
                'next_text' => '下一页<i class="fas fa-chevron-right ml-2"></i>',
                'type' => 'array'
            );
            $pagination_links = paginate_links($pagination_args);
            
            if ($pagination_links) :
        ?>
            <nav class="mt-12 flex justify-center" aria-label="专题分页导航">
                <div class="flex items-center space-x-2">
                    <?php foreach ($pagination_links as $link) : ?>
                        <div class="pagination-item">
                            <?php echo str_replace(
                                array('page-numbers', 'current'),
                                array('px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200', 'bg-blue-600 text-white'),
                                $link
                            ); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </nav>
            
            <style>
            .pagination-item a {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #374151;
                background-color: #ffffff;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                transition: all 0.2s;
            }
            .pagination-item a:hover {
                background-color: #f9fafb;
                color: #2563eb;
            }
            .pagination-item .current {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                background-color: #2563eb;
                color: #ffffff;
                border-radius: 0.5rem;
            }
            .pagination-item .dots {
                padding: 0.5rem 0.5rem;
                color: #6b7280;
            }
            </style>
        <?php endif; endif; ?>
    <?php else : ?>
        <!-- 空状态 -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-layer-group text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">暂无专题</h3>
        <p class="text-gray-500">还没有创建任何专题，请稍后再来查看。</p>
        </div>
    <?php endif; ?>
</main>

<?php 
// 重置查询对象以避免影响其他页面功能
wp_reset_query();
get_footer(); ?>