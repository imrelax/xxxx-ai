<?php
/**
 * X-Man AI主题 - 分类页面模板
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

<!-- 分类信息通栏 -->
<div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white hidden md:block">
    <div class="max-w-1500 mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center">
            <i class="fas fa-folder-open text-4xl mr-6"></i>
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-3"><?php single_cat_title(); ?></h1>
                <div class="text-blue-100 text-xl mb-4">
                    <?php 
                    $category_desc = category_description();
                    if ($category_desc) {
                        echo wp_kses_post($category_desc);
                    } else {
                        echo '这里汇集了关于「' . single_cat_title('', false) . '」的精彩内容，为您提供深度见解和实用信息。探索这个分类下的优质文章，发现更多有价值的知识和观点。';
                    }
                    ?>
                </div>
                <div class="flex items-center text-blue-100">
                    <i class="fas fa-file-alt mr-2"></i>
                    <span class="text-lg">共收录 <?php echo esc_html($wp_query->found_posts); ?> 篇优质文章</span>
                </div>
            </div>
        </div>
    </div>
</div>

<main class="max-w-1500 mx-auto flex flex-col lg:flex-row gap-8 py-8 px-4 sm:px-6 lg:px-8">
    <!-- 文章列表区域 -->
    <div class="flex-1 lg:w-2/3">
        <?php 
        // 显示面包屑导航
        xman_breadcrumb();
        ?>
        
        <?php if (have_posts()) : ?>
            <!-- 文章列表 -->
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
            <!-- 无文章提示 -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-folder-open text-gray-300 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">该分类下暂无文章</h2>
                <p class="text-gray-600 mb-6">这个分类还没有发布任何文章。</p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
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