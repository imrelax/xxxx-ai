<?php
/**
 * X-Man AI主题 - 搜索结果页面模板
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

<!-- 搜索信息通栏 -->
<div class="bg-gradient-to-r from-orange-500 to-red-600 text-white">
    <div class="max-w-1500 mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center">
            <i class="fas fa-search text-4xl mr-6"></i>
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-3">搜索结果</h1>
                <p class="text-orange-100 text-xl mb-4">
                    关键词：<span class="font-semibold"><?php echo get_search_query(); ?></span>
                </p>
                <div class="flex items-center text-orange-100">
                    <i class="fas fa-file-alt mr-2"></i>
                    <span class="text-lg">找到 <?php echo esc_html($wp_query->found_posts); ?> 篇相关文章</span>
                </div>
            </div>
        </div>
    </div>
</div>

<main class="max-w-1500 mx-auto flex flex-col lg:flex-row gap-8 py-8 px-4 sm:px-6 lg:px-8">
    <!-- 搜索结果区域 -->
    <div class="flex-1 lg:w-2/3">
        <?php 
        // 显示面包屑导航
        xman_breadcrumb();
        ?>
        
        <?php if (have_posts()) : ?>
            <!-- 搜索结果列表 -->
            <div class="space-y-6">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200 hover:transform hover:scale-[1.02]">
                        <div class="md:flex md:h-auto">
                            <div class="md:w-1/3 md:flex md:items-stretch">
                                <a href="<?php the_permalink(); ?>" class="block w-full">
                                    <?php echo xman_get_post_thumbnail(get_the_ID(), 'medium', 'w-full h-48 md:h-full object-cover'); ?>
                                </a>
                            </div>
                            <div class="md:w-2/3 p-6">
                                <h3 class="text-2xl font-bold text-gray-900 mb-1 hover:text-blue-600 transition-colors duration-200">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php 
                                        // 高亮搜索关键词
                                        $title = get_the_title();
                                        $search_query = get_search_query();
                                        if ($search_query) {
                                            $title = str_ireplace($search_query, '<mark class="bg-yellow-200 px-1 rounded">' . $search_query . '</mark>', $title);
                                        }
                                        echo wp_kses_post($title);
                                        ?>
                                    </a>
                                </h3>
                                
                                <div class="text-gray-700 mb-4 leading-relaxed whitespace-pre-line">
                                    <?php 
                                    // 高亮搜索关键词
                                    $excerpt = get_the_excerpt();
                                    if (empty($excerpt)) {
                                        $excerpt = wp_trim_words(get_the_content(), 100, '...');
                                    }
                                    $excerpt = wp_trim_words($excerpt, 100, '...');
                                    
                                    $search_query = get_search_query();
                                    if ($search_query) {
                                        $excerpt = str_ireplace($search_query, '<mark class="bg-yellow-200 px-1 rounded">' . $search_query . '</mark>', $excerpt);
                                    }
                                    echo wp_kses_post($excerpt);
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
                <?php endwhile; ?>
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
            <!-- 无搜索结果提示 -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">未找到相关内容</h2>
                <p class="text-gray-600 mb-6">抱歉，没有找到与 "<?php echo get_search_query(); ?>" 相关的文章。</p>
                
                <!-- 搜索建议 -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">搜索建议：</h3>
                    <ul class="text-left text-gray-600 space-y-2">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>检查拼写是否正确</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>尝试使用更简单的关键词</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>使用同义词或相关词汇</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>减少搜索词的数量</li>
                    </ul>
                </div>
                
                <!-- 重新搜索 -->
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="mb-6">
                    <div class="flex max-w-md mx-auto">
                        <input type="search" 
                               class="flex-1 px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="重新搜索..." 
                               value="<?php echo get_search_query(); ?>" 
                               name="s">
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-r-lg transition-colors duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i> 返回首页
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- 侧边栏 -->
    <aside class="w-full lg:w-1/3 sidebar-container">
        <?php get_sidebar(); ?>
    </aside>
</main>

<?php get_footer(); ?>