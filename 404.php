<?php
/**
 * X-Man AI主题 - 404错误页面
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header();
?>

<main class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto text-center">
        <!-- 404图标和数字 -->
        <div class="mb-8">
            <div class="relative">
                <!-- 背景装饰 -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-96 h-96 bg-gradient-to-r from-blue-200 to-purple-200 rounded-full opacity-20 animate-pulse"></div>
                </div>
                
                <!-- 404数字 -->
                <div class="relative z-10">
                    <h1 class="text-9xl md:text-[12rem] font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 leading-none">
                        404
                    </h1>
                </div>
                
                <!-- 浮动图标 -->
                <div class="absolute top-10 left-10 animate-bounce">
                    <i class="fas fa-search text-blue-400 text-3xl opacity-60"></i>
                </div>
                <div class="absolute top-20 right-16 animate-bounce" style="animation-delay: 0.5s;">
                    <i class="fas fa-question-circle text-purple-400 text-2xl opacity-60"></i>
                </div>
                <div class="absolute bottom-16 left-20 animate-bounce" style="animation-delay: 1s;">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl opacity-60"></i>
                </div>
            </div>
        </div>

        <!-- 错误信息 -->
        <div class="mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                页面未找到
            </h2>
            <p class="text-lg text-gray-600 mb-2">
                抱歉，您访问的页面不存在或已被移动。
            </p>
            <p class="text-base text-gray-500">
                请检查URL是否正确，或使用下方的搜索功能查找您需要的内容。
            </p>
        </div>

        <!-- 搜索框 -->
        <div class="mb-12">
            <form class="max-w-md mx-auto" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="relative">
                    <input type="search" 
                           class="w-full px-6 py-4 pr-14 text-gray-700 bg-white border-2 border-gray-200 rounded-full focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 shadow-lg" 
                           placeholder="<?php echo esc_attr(get_option('xman_search_placeholder', '搜索文章...')); ?>" 
                           name="s"
                           autofocus>
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- 快捷导航 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- 返回首页 -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-home text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">返回首页</h3>
                    <p class="text-sm text-gray-600 text-center">回到网站首页浏览最新内容</p>
                </div>
            </a>

            <!-- 热门文章 -->
            <a href="<?php echo esc_url(home_url('/category/hot')); ?>" class="group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-purple-200">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-fire text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">热门文章</h3>
                    <p class="text-sm text-gray-600 text-center">查看最受欢迎的文章内容</p>
                </div>
            </a>

            <!-- 联系我们 -->
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-green-200">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-envelope text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">联系我们</h3>
                    <p class="text-sm text-gray-600 text-center">遇到问题？联系我们获取帮助</p>
                </div>
            </a>
        </div>

        <!-- 最新文章推荐 -->
        <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center justify-center">
                <i class="fas fa-newspaper text-blue-600 mr-3"></i>
                最新文章推荐
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                // 获取最新的6篇文章
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 6,
                    'post_status' => 'publish'
                ));
                
                if ($recent_posts) :
                    foreach ($recent_posts as $post) :
                        $post_id = $post['ID'];
                        $post_title = $post['post_title'];
                        $post_url = get_permalink($post_id);
                        $post_date = get_the_date('Y-m-d', $post_id);
                        $post_excerpt = wp_trim_words($post['post_content'], 15, '...');
                ?>
                <article class="group">
                    <a href="<?php echo esc_url($post_url); ?>" class="block bg-gray-50 rounded-lg p-4 hover:bg-blue-50 transition-colors duration-200">
                        <h4 class="font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                            <?php echo esc_html($post_title); ?>
                        </h4>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            <?php echo esc_html($post_excerpt); ?>
                        </p>
                        <div class="flex items-center text-xs text-gray-500">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            <?php echo esc_html($post_date); ?>
                        </div>
                    </a>
                </article>
                <?php
                    endforeach;
                else :
                ?>
                <div class="col-span-full text-center py-8">
                    <i class="fas fa-file-alt text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">暂无文章内容</p>
                </div>
                <?php endif; ?>
            </div>
        </div>


    </div>
</main>
<?php get_footer(); ?>