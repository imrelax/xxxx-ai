<?php
/**
 * X-Man AI主题 - 页面模板
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

<main class="max-w-1500 mx-auto flex flex-col lg:flex-row gap-8 py-8 px-4 sm:px-6 lg:px-8">
        <!-- 页面内容区域 -->
        <div class="flex-1 lg:w-2/3">
            <?php 
            // 显示面包屑导航
            xman_breadcrumb();
            ?>
            <?php while (have_posts()) : the_post(); ?>
                <article class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- 页面头部 -->
                    <header class="p-8 border-b border-gray-100">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight"><?php the_title(); ?></h1>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="rounded-lg overflow-hidden">
                                <?php the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
                            </div>
                        <?php endif; ?>
                    </header>
                    
                    <!-- 页面内容 -->
                    <div class="p-8">
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                            <?php the_content(); ?>
                        </div>
                        
                        <?php
                        wp_link_pages(array(
                            'before' => '<div class="flex justify-center space-x-2 mt-8 pt-6 border-t border-gray-100">',
                            'after'  => '</div>',
                            'link_before' => '<span class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">',
                            'link_after'  => '</span>',
                        ));
                        ?>
                    </div>
                </article>
                
                <!-- 评论区域 -->
                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-8">
                        <div class="p-8">
                            <?php comments_template(); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
            <?php endwhile; ?>
        </div>
        
        <!-- 侧边栏 -->
        <aside class="w-full lg:w-1/3">
            <?php get_sidebar(); ?>
        </aside>
    </main>



<?php get_footer(); ?>