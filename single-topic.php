<?php
/**
 * X-Man AI主题 - 专题模板
 * 用于显示topic文章类型的专题页面
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
    <?php
    // 获取合集数据
    $collection_id = get_the_ID();
    $collection_posts = xman_get_topic_posts($collection_id);
$featured_software = xman_get_topic_featured_software($collection_id);
    ?>
    
    <!-- 置顶软件信息区域 -->
    <?php if ($featured_software) : ?>
        <?php
        // 设置全局$post为软件文章，以便使用现有的软件显示函数
        $original_post = $GLOBALS['post'];
        $GLOBALS['post'] = $featured_software;
        setup_postdata($featured_software);
        
        // 获取软件信息
        $software_info = xman_ai_get_software_info($featured_software->ID);
        ?>
        <div class="w-full pt-8 pb-3">
            <div class="max-w-1500 mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl shadow-lg border border-gray-200 p-8">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- 软件图标 -->
                        <div class="flex-shrink-0">
                            <?php if (has_post_thumbnail($featured_software->ID)) : ?>
                                <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-2xl overflow-hidden shadow-lg bg-white p-2">
                                    <?php echo get_the_post_thumbnail($featured_software->ID, 'thumbnail', array('class' => 'w-full h-full object-cover rounded-xl')); ?>
                                </div>
                            <?php else : ?>
                                <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                                    <i class="fas fa-download text-white text-3xl lg:text-4xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- 软件基本信息 -->
                        <div class="flex-1 min-w-0">
                            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3"><?php echo esc_html($featured_software->post_title); ?></h2>
                            
                            <?php if (!empty($software_info['intro'])) : ?>
                                <p class="text-gray-700 mb-4 leading-relaxed"><?php echo esc_html($software_info['intro']); ?></p>
                            <?php endif; ?>
                            
                            <!-- 软件详细信息网格 -->
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                <?php if (!empty($software_info['version'])) : ?>
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <div class="flex items-center">
                                            <i class="fas fa-code-branch text-blue-500 mr-2"></i>
                                            <div>
                                                <div class="text-xs text-gray-500">版本</div>
                                                <div class="font-semibold text-gray-900">v<?php echo esc_html($software_info['version']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($software_info['size'])) : ?>
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <div class="flex items-center">
                                            <i class="fas fa-hdd text-green-500 mr-2"></i>
                                            <div>
                                                <div class="text-xs text-gray-500">大小</div>
                                                <div class="font-semibold text-gray-900"><?php echo esc_html($software_info['size']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($software_info['developer'])) : ?>
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <div class="flex items-center">
                                            <i class="fas fa-user-tie text-purple-500 mr-2"></i>
                                            <div>
                                                <div class="text-xs text-gray-500">开发者</div>
                                                <div class="font-semibold text-gray-900"><?php echo esc_html($software_info['developer']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($software_info['license_label'])) : ?>
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <div class="flex items-center">
                                            <i class="fas fa-certificate text-orange-500 mr-2"></i>
                                            <div>
                                                <div class="text-xs text-gray-500">许可</div>
                                                <div class="font-semibold text-gray-900"><?php echo esc_html($software_info['license_label']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- 适用设备 -->
                            <?php if (!empty($software_info['devices'])) : ?>
                                <div class="mb-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-mobile-alt text-blue-500 mr-1"></i>适用设备</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($software_info['devices'] as $device) : ?>
                                            <?php if (isset($software_info['device_labels'][$device])) : ?>
                                                <?php 
                                                // 为不同设备类型定义图标
                                                $device_icons = array(
                                                    'ios' => 'fab fa-apple',
                                                    'android' => 'fab fa-android',
                                                    'macos' => 'fab fa-apple',
                                                    'windows' => 'fab fa-windows',
                                                    'linux' => 'fab fa-linux',
                                                    'hongmeng' => 'fas fa-mobile-alt',
                                                    'router' => 'fas fa-wifi',
                                                    'other' => 'fas fa-desktop'
                                                );
                                                $icon = isset($device_icons[$device]) ? $device_icons[$device] : 'fas fa-desktop';
                                                ?>
                                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                                    <i class="<?php echo esc_attr($icon); ?> mr-1"></i>
                                                    <?php echo esc_html($software_info['device_labels'][$device]); ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- 下载地址 -->
                            <?php if (!empty($software_info['downloads']) && is_array($software_info['downloads'])) : ?>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">下载地址</h4>
                                    <div class="flex flex-wrap gap-3">
                                        <?php foreach ($software_info['downloads'] as $device => $download) : ?>
                                            <?php if (!empty($download['url'])) : ?>
                                                <?php 
                                                $device_label = isset($software_info['device_labels'][$device]) ? $software_info['device_labels'][$device] : $device;
                                                $button_text = $device_label;
                                                if (!empty($download['note'])) {
                                                    $button_text .= ' (' . $download['note'] . ')';
                                                }
                                                ?>
                                                <a href="<?php echo esc_url($download['url']); ?>" target="_blank" rel="nofollow" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                                                    <i class="fas fa-download mr-2"></i>
                                                    <?php echo esc_html($button_text); ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
        <?php
        // 恢复原始$post
        $GLOBALS['post'] = $original_post;
        wp_reset_postdata();
        ?>
    <?php endif; ?>
    
    <!-- 主要内容区域 -->
    <main class="max-w-1500 mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- 面包屑导航 -->
        <?php 
        // 自定义面包屑导航用于合集页面
        echo '<nav class="breadcrumb mb-6 bg-white rounded-lg shadow-sm border border-gray-100 px-4 py-3">';
        echo '<ol class="flex items-center space-x-2 text-sm text-gray-600">';
        echo '<li><a href="' . home_url() . '" class="flex items-center hover:text-blue-600 transition-colors"><i class="fas fa-home mr-1"></i>首页</a></li>';
        echo '<li><i class="fas fa-chevron-right mx-2 text-gray-400"></i></li>';
        echo '<li class="flex items-center text-gray-900 font-medium"><i class="fas fa-layer-group mr-1 text-purple-500"></i>' . get_the_title() . '</li>';
        echo '</ol>';
        echo '</nav>';
        ?>
        
        <?php if (!empty($collection_posts)) : ?>
            <div class="flex gap-8">
                <!-- 左侧边栏 -->
                <div class="w-80 flex-shrink-0 space-y-6">
                    <!-- 文章导航 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4">
                            <h3 class="text-white font-semibold text-lg flex items-center">
                                <i class="fas fa-list text-white mr-2"></i> 文章导航
                            </h3>
                        </div>
                        <div class="collection-nav-list">
                            <?php foreach ($collection_posts as $index => $post_item) : ?>
                                <div class="collection-nav-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                                     data-post-id="<?php echo esc_attr($post_item->ID); ?>" 
                                     data-index="<?php echo esc_attr($index); ?>">
                                    <div class="p-4 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold">
                                                <?php echo $index + 1; ?>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-medium text-gray-900 text-sm leading-tight mb-1">
                                                    <?php echo esc_html($post_item->post_title); ?>
                                                </h4>
                                                <p class="text-xs text-gray-500">
                                                    <?php echo get_the_date('Y-m-d', $post_item->ID); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- 边栏小工具 -->
                            <!-- 热门标签小工具 -->
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                                <div class="bg-gradient-to-r from-green-500 to-teal-600 text-white px-6 py-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-tags mr-2"></i>
                                        <h3 class="text-lg font-bold">热门标签</h3>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="flex flex-wrap gap-2">
                                        <?php
                                        $tags = get_tags(array(
                                            'orderby' => 'count',
                                            'order' => 'DESC',
                                            'number' => 12
                                        ));
                                        
                                        if ($tags && !is_wp_error($tags)) {
                                            foreach ($tags as $tag) {
                                                echo '<a href="' . get_tag_link($tag->term_id) . '" class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-800 text-sm rounded-full transition-colors duration-200"><i class="fas fa-hashtag mr-1 text-xs"></i>' . esc_html($tag->name) . '</a>';
                                            }
                                        } else {
                                            // 默认标签
                                            $default_tags = array(
                                                'JavaScript', 'Vue.js', 'React', 'Node.js',
                                                'TypeScript', 'CSS3', 'Docker', 'MongoDB'
                                            );
                                            foreach ($default_tags as $tag) {
                                                echo '<a href="#" class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-800 text-sm rounded-full transition-colors duration-200"><i class="fas fa-hashtag mr-1 text-xs"></i>' . esc_html($tag) . '</a>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- AD1 广告位 -->
                            <?php if (function_exists('xman_has_ad') && xman_has_ad(1)) : ?>
                            <div>
                                <?php if (function_exists('xman_show_sidebar_ad1')) xman_show_sidebar_ad1(); ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- 站长推荐小工具 -->
                            <?php xman_render_recommended_sites(); ?>
                </div>
                
                <!-- 右侧文章内容 -->
                <div class="flex-1">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div id="collection-content-area">
                            <!-- 默认显示第一篇文章 -->
                            <?php if (!empty($collection_posts)) : ?>
                                <?php
                                $first_post = $collection_posts[0];
                                $original_post = $GLOBALS['post'];
                                $GLOBALS['post'] = $first_post;
                                setup_postdata($first_post);
                                ?>
                                <div class="collection-post-content" data-post-id="<?php echo esc_attr($first_post->ID); ?>">
                                    <!-- Header装饰线 -->
                                    <div class="header-decoration-line"></div>
                                    
                                    <!-- 文章头部 -->
                                    <header class="p-8 border-b border-gray-100">
                                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4"><?php the_title(); ?></h2>
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar text-blue-500 mr-2"></i>
                                                <?php echo get_the_date(); ?>
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-eye text-orange-500 mr-2"></i>
                                                <?php echo xman_get_post_views(get_the_ID()); ?> 次浏览
                                            </span>
                                        </div>
                                        
                                        <?php // 专题页面不显示特色图片 ?>
                                    </header>
                                    
                                    <!-- 文章上方广告位 AD3 -->
                    <?php if (function_exists('xman_has_ad') && xman_has_ad(3)) : ?>
                        <?php if (function_exists('xman_show_content_top_ad')) xman_show_content_top_ad(); ?>
                    <?php endif; ?>
                    
                    <!-- 文章内容 -->
                    <div class="p-8 prose prose-lg max-w-none">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- 文章下方广告位 AD4 -->
                    <?php if (function_exists('xman_has_ad') && xman_has_ad(4)) : ?>
                        <?php if (function_exists('xman_show_content_bottom_ad')) xman_show_content_bottom_ad(); ?>
                    <?php endif; ?>
                                </div>
                                <?php
                                $GLOBALS['post'] = $original_post;
                                wp_reset_postdata();
                                ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">暂无文章</h3>
                <p class="text-gray-500">此合集还没有添加任何文章。</p>
            </div>
        <?php endif; ?>
    </main>
    
    <!-- JavaScript for navigation -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const navItems = document.querySelectorAll('.collection-nav-item');
        const contentArea = document.getElementById('collection-content-area');
        
        // 文章数据
        const posts = <?php echo json_encode(array_map(function($post) {
            return array(
                'ID' => $post->ID,
                'title' => $post->post_title,
                'content' => apply_filters('the_content', $post->post_content),
                'date' => get_the_date('', $post->ID),
                'views' => xman_get_post_views($post->ID),
                'thumbnail' => get_the_post_thumbnail($post->ID, 'large', array('class' => 'w-full h-auto')),
                'is_software' => xman_ai_is_software_post($post->ID)
            );
        }, $collection_posts)); ?>;
        
        navItems.forEach(function(item) {
            item.addEventListener('click', function() {
                const postId = this.dataset.postId;
                const index = parseInt(this.dataset.index);
                
                // 更新导航状态
                navItems.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                // 发送 AJAX 请求获取文章内容
                fetch(`${window.ajaxurl || '/wp-admin/admin-ajax.php'}?action=get_topic_post_content&post_id=${postId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // 构建文章HTML
                            const thumbnailHtml = ''; // 专题页面不显示特色图片
                            
                            contentArea.innerHTML = `
                                <div class="collection-post-content" data-post-id="${postId}">
                                    <div class="header-decoration-line"></div>
                                    <header class="p-8 border-b border-gray-100">
                                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">${data.data.title}</h2>
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar text-blue-500 mr-2"></i>
                                                ${data.data.date}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-eye text-orange-500 mr-2"></i>
                                                ${data.data.views} 次浏览
                                            </span>
                                        </div>
                                        ${thumbnailHtml}
                                    </header>
                                    ${data.data.ad_top || ''}
                                    <div class="p-8 prose prose-lg max-w-none">
                                        ${data.data.content}
                                    </div>
                                    ${data.data.ad_bottom || ''}
                                </div>
                            `;
                            
                            // 内容已经通过PHP的apply_filters('the_content')处理过，无需再次解析Markdown
                        } else {
                            console.error('获取文章内容失败:', data.data);
                        }
                    })
                    .catch(error => {
                        console.error('AJAX请求失败:', error);
                        // 降级到使用预加载的数据
                        const post = posts[index];
                        if (post) {
                            const thumbnailHtml = ''; // 专题页面不显示特色图片
                            
                            contentArea.innerHTML = `
                                <div class="collection-post-content" data-post-id="${post.ID}">
                                    <div class="header-decoration-line"></div>
                                    <header class="p-8 border-b border-gray-100">
                                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">${post.title}</h2>
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar text-blue-500 mr-2"></i>
                                                ${post.date}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-eye text-orange-500 mr-2"></i>
                                                ${post.views} 次浏览
                                            </span>
                                        </div>
                                        ${thumbnailHtml}
                                    </header>
                                    <div class="p-8 prose prose-lg max-w-none">
                                        ${post.content}
                                    </div>
                                </div>
                            `;
                        }
                    });
            });
        });
    });
    </script>
    

    
<?php endwhile; ?>

<?php get_footer(); ?>