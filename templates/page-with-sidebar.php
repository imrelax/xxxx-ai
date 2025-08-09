<?php
/**
 * Template Name: 带边栏页面
 * 
 * X-Man AI主题 - 带边栏的页面模板
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

<main class="max-w-1500 mx-auto flex flex-col lg:flex-row gap-8 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
            <!-- 主要内容区域 -->
            <div class="flex-1 lg:w-2/3">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden'); ?>>
                        <header class="page-header">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="relative h-64 overflow-hidden">
                                    <?php the_post_thumbnail('large', array('class' => 'w-full h-full object-cover')); ?>
                                </div>
                                <div class="px-8 py-6 border-b border-gray-200">
                                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900"><?php the_title(); ?></h1>
                                </div>
                            <?php else : ?>
                                <div class="px-8 py-8 border-b border-gray-200">
                                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900"><?php the_title(); ?></h1>
                                </div>
                            <?php endif; ?>
                        </header>

                        <div class="page-content px-8 py-8">
                            <div class="prose prose-lg max-w-none">
                                <?php
                                the_content();
                                
                                wp_link_pages(array(
                                    'before' => '<div class="flex flex-wrap gap-2 mt-8 pt-6 border-t border-gray-200"><span class="text-gray-600 font-medium mr-2">' . esc_html__('Pages:', 'textdomain') . '</span>',
                                    'after'  => '</div>',
                                    'link_before' => '<span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">',
                                    'link_after' => '</span>',
                                ));
                                ?>
                            </div>
                        </div>
                        
                        <?php if (comments_open() || get_comments_number()) : ?>
                            <div class="page-comments px-8 py-8 border-t border-gray-200">
                                <?php comments_template(); ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <!-- 边栏 -->
            <aside class="w-full lg:w-1/3">
                <?php get_sidebar(); ?>
            </aside>
</main>

<?php get_footer(); ?>