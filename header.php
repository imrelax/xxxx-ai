<?php
/**
 * X-Man AI主题 - 头部模板
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <?php
    // 输出统计代码
    $analytics_code = get_option('xman_analytics_code', '');
    if (!empty($analytics_code)) {
        echo stripslashes($analytics_code) . "\n";
    }
    ?>
    
    <!-- 自定义菜单样式 -->
    <style>
        /* 菜单项悬停效果 */
        .menu-item-hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
        }
        
        /* 移动端菜单滑动动画 */
        .mobile-menu-slide {
            transform: translateY(-10px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .mobile-menu-slide.active {
            transform: translateY(0);
            opacity: 1;
        }
        
        /* 下拉菜单动画 */
        .dropdown-content {
            transform: translateY(-8px) scale(0.95);
            transform-origin: top;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .dropdown-content.show {
            transform: translateY(0) scale(1);
        }
        
        /* 菜单按钮旋转动画 */
        .menu-icon-rotate {
            transition: transform 0.3s ease;
        }
        
        .menu-icon-rotate.active {
            transform: rotate(90deg);
        }
        
        /* 搜索框聚焦效果 */
        .search-focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* 二级菜单悬停显示 */
        .menu-item-has-children {
            position: relative;
        }
        
        .menu-item-has-children .sub-menu {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 200px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .menu-item-has-children:hover .sub-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .sub-menu .menu-item {
            display: block;
            width: 100%;
        }
        
        .sub-menu .menu-item a {
            display: block;
            padding: 12px 16px;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s ease;
            border-radius: 6px;
            margin: 4px;
        }
        
        .sub-menu .menu-item a:hover {
            background: #f3f4f6;
            color: #2563eb;
            transform: translateX(4px);
        }
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php
    // SEO Meta标签
    $site_description = get_option('xman_site_description', get_bloginfo('description'));
    $site_keywords = get_option('xman_site_keywords', '');
    $site_name = get_option('xman_site_name', get_bloginfo('name'));
    
    // 根据页面类型设置不同的title和description
    if (is_home() || is_front_page()) {
        $page_title = $site_name;
        $page_description = $site_description;
    } elseif (is_single()) {
        $page_title = get_the_title() . ' - ' . $site_name;
        $page_description = get_the_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : $site_description;
    } elseif (is_page()) {
        $page_title = get_the_title() . ' - ' . $site_name;
        $page_description = get_the_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : $site_description;
    } elseif (is_category()) {
        $page_title = single_cat_title('', false) . ' - ' . $site_name;
        $page_description = category_description() ? wp_strip_all_tags(category_description()) : $site_description;
    } elseif (is_tag()) {
        $page_title = single_tag_title('', false) . ' - ' . $site_name;
        $page_description = tag_description() ? wp_strip_all_tags(tag_description()) : $site_description;
    } elseif (is_archive()) {
        $page_title = get_the_archive_title() . ' - ' . $site_name;
        $page_description = get_the_archive_description() ? wp_strip_all_tags(get_the_archive_description()) : $site_description;
    } else {
        $page_title = wp_get_document_title();
        $page_description = $site_description;
    }
    ?>
    
    <!-- SEO Meta标签 -->
    <meta name="description" content="<?php echo esc_attr($page_description); ?>">
    <?php if (!empty($site_keywords)): ?>
    <meta name="keywords" content="<?php echo esc_attr($site_keywords); ?>">
    <?php endif; ?>
    <meta name="author" content="<?php echo esc_attr(get_option('xman_author_name', get_bloginfo('name'))); ?>">
    
    <!-- Open Graph标签 -->
    <meta property="og:title" content="<?php echo esc_attr($page_title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($page_description); ?>">
    <meta property="og:type" content="<?php echo is_single() ? 'article' : 'website'; ?>">
    <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    <?php if (has_post_thumbnail() && is_single()): ?>
    <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>">
    <?php endif; ?>
    
    <!-- Twitter Card标签 -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($page_title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($page_description); ?>">
    <?php if (has_post_thumbnail() && is_single()): ?>
    <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>">
    <?php endif; ?>
    
    <?php wp_head(); ?>
    
    <!-- 自定义Favicon -->
    <?php 
    $favicon = get_option('xman_site_favicon', '');
    if (!empty($favicon)): 
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo esc_url($favicon); ?>">
    <link rel="apple-touch-icon" href="<?php echo esc_url($favicon); ?>">
    <?php endif; ?>
    

    <!-- 菜单交互脚本 -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 移动端菜单切换
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        
        if (mobileMenuButton && mobileMenu && menuIcon) {
            mobileMenuButton.addEventListener('click', function() {
                const isHidden = mobileMenu.classList.contains('hidden');
                
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    setTimeout(() => {
                        mobileMenu.classList.add('opacity-100', 'translate-y-0');
                        mobileMenu.classList.remove('opacity-0', '-translate-y-2');
                    }, 10);
                    menuIcon.classList.remove('fa-bars');
                    menuIcon.classList.add('fa-times');
                } else {
                    mobileMenu.classList.add('opacity-0', '-translate-y-2');
                    mobileMenu.classList.remove('opacity-100', 'translate-y-0');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 200);
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');
                }
            });
        }
        
        // 桌面端下拉菜单
        const dropdownMenus = document.querySelectorAll('.menu-item-has-children');
        dropdownMenus.forEach(menu => {
            const trigger = menu.querySelector('a');
            const content = menu.querySelector('.sub-menu');
            
            if (trigger && content) {
                // 鼠标进入主菜单项
                menu.addEventListener('mouseenter', () => {
                    content.classList.remove('opacity-0', 'invisible', 'translate-y-1');
                    content.classList.add('opacity-100', 'visible', 'translate-y-0');
                    content.style.display = 'block';
                });
                
                // 鼠标离开主菜单项
                menu.addEventListener('mouseleave', () => {
                    content.classList.add('opacity-0', 'invisible', 'translate-y-1');
                    content.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    setTimeout(() => {
                        if (content.classList.contains('opacity-0')) {
                            content.style.display = 'none';
                        }
                    }, 200);
                });
            }
        });
    });
    </script>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-gray-100">
    <nav class="max-w-1500 mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- 左侧Logo区域 -->
            <div class="flex items-center space-x-3">
                <?php 
                $site_logo = get_option('xman_site_logo', '');
                $logo_icon = get_option('xman_logo_icon', 'fas fa-code');
                $site_title = get_option('xman_site_name', get_bloginfo('name'));
                ?>
                <div class="logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center space-x-3 text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
                        <?php if (!empty($site_logo)): ?>
                            <img src="<?php echo esc_url($site_logo); ?>" alt="<?php echo esc_attr($site_title); ?>" class="h-8 w-auto">
                        <?php else: ?>
                            <i class="<?php echo esc_attr($logo_icon); ?> text-2xl text-blue-600"></i>
                        <?php endif; ?>
                        <span><?php echo esc_html($site_title); ?></span>
                    </a>
                </div>
            </div>

            <!-- 中间搜索区域 -->
            <div class="hidden md:flex flex-1 max-w-lg mx-8">
                <form class="w-full" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="relative">
                        <input type="search" 
                               class="w-full px-4 py-2 pr-12 text-gray-700 bg-gray-100 border border-gray-300 rounded-full focus:outline-none focus:search-focus transition-all duration-200" 
                               placeholder="<?php echo esc_attr(get_option('xman_search_placeholder', '搜索文章...')); ?>" 
                               value="<?php echo get_search_query(); ?>" 
                               name="s">
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- 右侧导航链接 -->
            <div class="hidden md:flex items-center space-x-1">
                <?php
                // 检查是否有自定义菜单
if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'flex items-center space-x-1',
                        'container' => false,
                        'depth' => 2,
                        'walker' => new Custom_Nav_Walker(),
                        'fallback_cb' => 'xman_fallback_menu'
                    ));
                } else {
                    // 显示默认菜单
                    xman_fallback_menu();
                }
                ?>
            </div>

            <!-- 移动端菜单按钮 -->
            <div class="md:hidden">
                <button type="button" class="p-2 text-gray-700 hover:text-blue-600 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200" id="mobile-menu-button">
                    <i class="fas fa-bars text-xl transition-transform duration-200" id="menu-icon"></i>
                </button>
            </div>
        </div>

        <!-- 移动端菜单 -->
        <div class="md:hidden hidden opacity-0 -translate-y-2 transition-all duration-200 ease-out" id="mobile-menu">
            <div class="px-4 pt-4 pb-6 space-y-2 bg-white/95 backdrop-blur-md border-t border-gray-100 shadow-lg">
                <!-- 移动端搜索 -->
                <form class="mb-6" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="relative">
                        <input type="search" 
                               class="w-full px-4 py-3 pr-12 text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:search-focus focus:bg-white transition-all duration-200" 
                               placeholder="<?php echo esc_attr(get_option('xman_search_placeholder', '搜索文章...')); ?>" 
                               value="<?php echo get_search_query(); ?>" 
                               name="s">
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-blue-600 transition-colors duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <?php
                // 移动端菜单
if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'space-y-2',
                        'container' => false,
                        'depth' => 2,
                        'walker' => new Custom_Mobile_Nav_Walker(),
                        'fallback_cb' => 'xman_fallback_mobile_menu'
                    ));
                } else {
                    xman_fallback_mobile_menu();
                }
                ?>
            </div>
        </div>
    </nav>
</header>

<?php
/**
 * 默认导航菜单回调函数
 */
function xman_fallback_menu() {
    // 首页链接
    echo '<a href="' . esc_url(home_url('/')) . '" class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 font-medium relative group">'
         . '<span class="relative z-10">首页</span>'
         . '<span class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg opacity-0 group-hover:opacity-10 transition-opacity duration-200"></span>'
         . '</a>';
    
    // 分类页面
    $categories = get_categories(array('number' => 4));
    foreach ($categories as $category) {
        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 font-medium relative group">'
             . '<span class="relative z-10">' . esc_html($category->name) . '</span>'
             . '<span class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg opacity-0 group-hover:opacity-10 transition-opacity duration-200"></span>'
             . '</a>';
    }
    
    // 关于页面
    $about_page = get_page_by_path('about');
    if ($about_page) {
        echo '<a href="' . esc_url(get_permalink($about_page)) . '" class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 font-medium relative group">'
             . '<span class="relative z-10">关于</span>'
             . '<span class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg opacity-0 group-hover:opacity-10 transition-opacity duration-200"></span>'
             . '</a>';
    }
}

/**
 * 移动端默认导航菜单回调函数
 */
function xman_fallback_mobile_menu() {
    // 首页链接
    echo '<a href="' . esc_url(home_url('/')) . '" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200 font-medium group">'
         . '<i class="fas fa-home w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors"></i>'
         . '<span>首页</span>'
         . '</a>';
    
    // 分类页面
    $categories = get_categories(array('number' => 5));
    $category_icons = ['fas fa-folder', 'fas fa-tag', 'fas fa-bookmark', 'fas fa-star', 'fas fa-heart'];
    foreach ($categories as $index => $category) {
        $icon = isset($category_icons[$index]) ? $category_icons[$index] : 'fas fa-folder';
        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200 font-medium group">'
             . '<i class="' . $icon . ' w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors"></i>'
             . '<span>' . esc_html($category->name) . '</span>'
             . '</a>';
    }
    
    // 关于页面
    $about_page = get_page_by_path('about');
    if ($about_page) {
        echo '<a href="' . esc_url(get_permalink($about_page)) . '" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200 font-medium group">'
             . '<i class="fas fa-info-circle w-5 h-5 mr-3 text-gray-400 group-hover:text-blue-500 transition-colors"></i>'
             . '<span>关于</span>'
             . '</a>';
    }
}
?>