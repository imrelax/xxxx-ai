<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package xxxx-ai
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200 hover:transform hover:scale-[1.02] mb-6'); ?>>
    <!-- Header装饰线 -->
    <div class="header-decoration-line"></div>
    
    <!-- 文章缩略图 -->
    <?php if (has_post_thumbnail()) : ?>
        <div class="relative overflow-hidden">
            <a href="<?php the_permalink(); ?>" class="block">
                <?php the_post_thumbnail('large', array('class' => 'w-full h-64 object-cover hover:scale-105 transition-transform duration-300')); ?>
            </a>
        </div>
    <?php else : ?>
        <div class="relative overflow-hidden">
            <a href="<?php the_permalink(); ?>" class="block">
                <?php echo xman_get_post_thumbnail(get_the_ID(), 'large', 'w-full h-64 object-cover hover:scale-105 transition-transform duration-300'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <!-- 文章内容 -->
    <div class="p-6">
        <!-- 分类标签 -->
        <?php 
        $categories = get_the_category();
        if ($categories) :
        ?>
            <div class="mb-4">
                <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="text-purple-600 hover:text-purple-800"><?php echo esc_html($categories[0]->name); ?></a>
            </div>
        <?php endif; ?>
        
        <!-- 文章标题 -->
        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 leading-tight hover:text-blue-600 transition-colors">
            <a href="<?php the_permalink(); ?>" class="block"><?php the_title(); ?></a>
        </h2>
        
        <!-- 文章摘要 -->
        <div class="text-gray-600 mb-6 leading-relaxed">
            <?php 
            if (has_excerpt()) {
                the_excerpt();
            } else {
                echo wp_trim_words(get_the_content(), 50, '...');
            }
            ?>
        </div>
        
        <!-- 文章标签 -->
        <?php 
        $tags = get_the_tags();
        if ($tags) :
        ?>
            <div class="flex flex-wrap gap-2 mb-6">
                <?php foreach ($tags as $tag) : ?>
                    <?php echo '<span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex items-center"><i class="fas fa-tag mr-1"></i>' . esc_html($tag->name) . '</span>'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- 文章元信息和阅读按钮 -->
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center text-sm text-gray-600 flex-wrap gap-x-4">
                <span class="flex items-center">
                    <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                    <?php echo get_the_date(); ?>
                </span>
                <span class="flex items-center">
                    <i class="fas fa-eye mr-1 text-green-500"></i>
                    <?php echo xman_get_post_views(get_the_ID()); ?> 次浏览
                </span>
                <span class="flex items-center">
                    <i class="fas fa-comments mr-1 text-purple-500"></i>
                    <?php comments_number('0 评论', '1 评论', '% 评论'); ?>
                </span>

            </div>
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-right mr-2"></i>
                阅读全文
            </a>
        </div>
    </div>
</article>