<?php
/**
 * Template Name: 通栏页面
 * 
 * X-Man AI主题 - 无边栏的通栏页面模板
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
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="px-8 py-12 text-center border-b border-gray-200">
                            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4"><?php the_title(); ?></h1>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="page-content px-8 py-12">
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
</main>

<?php get_footer(); ?>