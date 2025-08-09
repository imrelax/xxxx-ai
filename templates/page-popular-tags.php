<?php
/**
 * Template Name: 热门标签
 * 
 * X-Man AI主题 - 热门标签页面模板
 * 显示网站所有标签，根据文章数量调整字体大小和样式
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

<main class="max-w-1500 mx-auto min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden'); ?>>
            <header class="page-header">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="relative h-96 overflow-hidden">
                        <?php the_post_thumbnail('full', array('class' => 'w-full h-full object-cover')); ?>
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <div class="text-center text-white px-6">
                                <h1 class="text-4xl lg:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
                                <p class="text-xl opacity-90">探索网站所有标签，发现更多精彩内容</p>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="px-8 py-12 text-center border-b border-gray-200">
                        <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4"><?php the_title(); ?></h1>
                        <p class="text-xl text-gray-600">探索网站所有标签，发现更多精彩内容</p>
                    </div>
                <?php endif; ?>
            </header>

            <div class="page-content px-8 py-12">
                <!-- 页面内容 -->
                <?php if (get_the_content()) : ?>
                    <div class="prose prose-lg max-w-none mb-12">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

                <!-- 标签统计信息 -->
                <?php
                $all_tags = get_tags(array(
                    'hide_empty' => true,
                    'orderby' => 'count',
                    'order' => 'DESC'
                ));
                $total_tags = count($all_tags);
                $total_posts = 0;
                foreach ($all_tags as $tag) {
                    $total_posts += $tag->count;
                }
                ?>
                
                <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="text-3xl font-bold text-blue-600 mb-2"><?php echo $total_tags; ?></div>
                            <div class="text-gray-600">总标签数</div>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="text-3xl font-bold text-green-600 mb-2"><?php echo $total_posts; ?></div>
                            <div class="text-gray-600">标签文章总数</div>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="text-3xl font-bold text-purple-600 mb-2"><?php echo $all_tags ? $all_tags[0]->count : 0; ?></div>
                            <div class="text-gray-600">最热标签文章数</div>
                        </div>
                    </div>
                </div>

                <!-- 热门标签云 -->
                <div class="tags-cloud-container">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">热门标签云</h2>
                    
                    <?php if ($all_tags) : ?>
                        <div class="tags-cloud bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-8 text-center" style="min-height: 400px; display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 15px;">
                            <?php
                            // 计算字体大小范围
                            $min_count = min(array_map(function($tag) { return $tag->count; }, $all_tags));
                            $max_count = max(array_map(function($tag) { return $tag->count; }, $all_tags));
                            
                            // 随机打乱标签顺序，增加视觉效果
                            shuffle($all_tags);
                            
                            foreach ($all_tags as $tag) :
                                // 计算字体大小 (12px - 48px)
                                $font_size = 12 + (($tag->count - $min_count) / ($max_count - $min_count)) * 36;
                                
                                // 根据热度选择颜色
                                $heat_ratio = ($tag->count - $min_count) / ($max_count - $min_count);
                                if ($heat_ratio > 0.8) {
                                    $color_class = 'text-red-600 hover:text-red-700';
                                    $bg_class = 'hover:bg-red-50';
                                } elseif ($heat_ratio > 0.6) {
                                    $color_class = 'text-orange-600 hover:text-orange-700';
                                    $bg_class = 'hover:bg-orange-50';
                                } elseif ($heat_ratio > 0.4) {
                                    $color_class = 'text-yellow-600 hover:text-yellow-700';
                                    $bg_class = 'hover:bg-yellow-50';
                                } elseif ($heat_ratio > 0.2) {
                                    $color_class = 'text-green-600 hover:text-green-700';
                                    $bg_class = 'hover:bg-green-50';
                                } else {
                                    $color_class = 'text-blue-600 hover:text-blue-700';
                                    $bg_class = 'hover:bg-blue-50';
                                }
                                
                                // 添加随机旋转角度
                                $rotation = rand(-5, 5);
                            ?>
                                <a href="<?php echo get_tag_link($tag->term_id); ?>" 
                                   class="tag-item inline-block px-3 py-2 rounded-lg transition-all duration-300 transform hover:scale-110 <?php echo $color_class . ' ' . $bg_class; ?>"
                                   style="font-size: <?php echo $font_size; ?>px; transform: rotate(<?php echo $rotation; ?>deg); font-weight: <?php echo 400 + ($heat_ratio * 300); ?>;"
                                   title="<?php echo $tag->count; ?> 篇文章">
                                    #<?php echo $tag->name; ?>
                                    <span class="text-xs opacity-70 ml-1">(<?php echo $tag->count; ?>)</span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-tags text-4xl mb-4 opacity-50"></i>
                            <p class="text-lg">暂无标签内容</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 标签列表视图 -->
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">标签列表</h2>
                    
                    <?php if ($all_tags) : ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php 
                            // 重新按文章数量排序
                            usort($all_tags, function($a, $b) {
                                return $b->count - $a->count;
                            });
                            
                            foreach ($all_tags as $index => $tag) : 
                                $rank_class = '';
                                if ($index < 3) {
                                    $rank_class = 'border-l-4 border-l-yellow-400 bg-yellow-50';
                                } elseif ($index < 10) {
                                    $rank_class = 'border-l-4 border-l-blue-400 bg-blue-50';
                                } else {
                                    $rank_class = 'border-l-4 border-l-gray-300 bg-gray-50';
                                }
                            ?>
                                <div class="tag-list-item p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow <?php echo $rank_class; ?>">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <a href="<?php echo get_tag_link($tag->term_id); ?>" class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                                                <?php echo $tag->name; ?>
                                            </a>
                                            <?php if ($tag->description) : ?>
                                                <p class="text-sm text-gray-600 mt-1"><?php echo wp_trim_words($tag->description, 10); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-right ml-4">
                                            <div class="text-2xl font-bold text-blue-600"><?php echo $tag->count; ?></div>
                                            <div class="text-xs text-gray-500">篇文章</div>
                                            <?php if ($index < 3) : ?>
                                                <div class="text-xs text-yellow-600 font-medium mt-1">
                                                    <i class="fas fa-crown"></i> TOP <?php echo $index + 1; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<style>
.tags-cloud .tag-item:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    z-index: 10;
    position: relative;
}

.tag-list-item:hover {
    transform: translateY(-2px);
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(var(--rotation)); }
    50% { transform: translateY(-10px) rotate(var(--rotation)); }
}

.tags-cloud .tag-item:nth-child(odd) {
    animation: float 6s ease-in-out infinite;
}

.tags-cloud .tag-item:nth-child(even) {
    animation: float 8s ease-in-out infinite reverse;
}
</style>

<script>
// 添加标签云交互效果
document.addEventListener('DOMContentLoaded', function() {
    const tagItems = document.querySelectorAll('.tag-item');
    
    tagItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            // 鼠标悬停时暂停动画
            this.style.animationPlayState = 'paused';
        });
        
        item.addEventListener('mouseleave', function() {
            // 鼠标离开时恢复动画
            this.style.animationPlayState = 'running';
        });
    });
});
</script>

<?php get_footer(); ?>