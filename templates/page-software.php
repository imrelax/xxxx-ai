<?php
/**
 * Template Name: 软件合集
 * 软件合集页面模板 - 显示所有软件分类的文章
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

<main class="max-w-1500 mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- 页面标题 -->
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">软件合集</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">精选优质软件资源，为您提供最佳的软件体验</p>
    </div>

    <?php
    // 获取所有软件类型的文章（通过自定义字段_post_content_type查询）
    $software_posts = get_posts(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => '_post_content_type',
                'value' => 'software',
                'compare' => '='
            )
        )
    ));
        
    if ($software_posts) :
    ?>
        <!-- 统计信息 -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 mb-8">
            <div class="flex items-center justify-center space-x-8">
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600"><?php echo count($software_posts); ?></div>
                    <div class="text-sm text-gray-600">软件总数</div>
                </div>
                <div class="w-px h-12 bg-gray-300"></div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600"><?php echo date('Y'); ?></div>
                    <div class="text-sm text-gray-600">持续更新</div>
                </div>
            </div>
        </div>

        <!-- 软件列表 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($software_posts as $post) : 
                setup_postdata($post);
                $software_info = xman_ai_get_software_info(get_the_ID());
            ?>
                <article class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <!-- 软件图标和基本信息 -->
                    <div class="p-6">
                        <div class="flex items-start space-x-4 mb-4">
                            <!-- 软件图标 -->
                            <div class="flex-shrink-0">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="w-16 h-16 rounded-xl overflow-hidden shadow-md bg-white p-1">
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'w-full h-full object-cover rounded-lg')); ?>
                                    </div>
                                <?php else : ?>
                                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center shadow-md">
                                        <i class="fas fa-download text-white text-xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- 软件信息 -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors truncate">
                                    <a href="<?php the_permalink(); ?>" class="hover:underline">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <?php if (!empty($software_info['intro'])) : ?>
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        <?php echo wp_trim_words($software_info['intro'], 15, '...'); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- 软件详细信息 -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <?php if (!empty($software_info['version'])) : ?>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-code-branch text-blue-500 mr-2 text-sm"></i>
                                        <div>
                                            <div class="text-xs text-gray-500">版本</div>
                                            <div class="font-semibold text-gray-900 text-sm">v<?php echo esc_html($software_info['version']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($software_info['size'])) : ?>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-hdd text-green-500 mr-2 text-sm"></i>
                                        <div>
                                            <div class="text-xs text-gray-500">大小</div>
                                            <div class="font-semibold text-gray-900 text-sm"><?php echo esc_html($software_info['size']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($software_info['developer'])) : ?>
                                <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-tie text-purple-500 mr-2 text-sm"></i>
                                        <div>
                                            <div class="text-xs text-gray-500">开发者</div>
                                            <div class="font-semibold text-gray-900 text-sm truncate"><?php echo esc_html($software_info['developer']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- 软件标签 -->
                        <?php if (!empty($software_info['tags'])) : ?>
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-1">
                                    <?php 
                                    $tags = explode(',', $software_info['tags']);
                                    foreach (array_slice($tags, 0, 3) as $tag) : 
                                        $tag = trim($tag);
                                        if (!empty($tag)) :
                                    ?>
                                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            <?php echo esc_html($tag); ?>
                                        </span>
                                    <?php endif; endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- 文章元信息 -->
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                <?php echo get_the_date('Y-m-d'); ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                <?php echo xman_get_post_views(get_the_ID()); ?> 次浏览
                            </span>
                        </div>
                        
                        <!-- 查看按钮 -->
                        <div class="text-center">
                            <a href="<?php the_permalink(); ?>" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-blue-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg text-sm">
                                <i class="fas fa-eye mr-2"></i>
                                查看详情
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    <?php else : ?>
        <!-- 暂无软件 -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-download text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">暂无软件</h3>
            <p class="text-gray-500">还没有发布任何软件，请稍后再来查看。</p>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>