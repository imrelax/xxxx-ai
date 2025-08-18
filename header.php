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
    
    <!-- Tailwind CSS v4 Play CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/style.css">
    
    <?php
    // 安全输出统计代码
$analytics_code = get_option('xman_analytics_code', '');
if (!empty($analytics_code)) {
    // 检查用户权限，管理员可以输出未过滤的统计代码
    if (current_user_can('unfiltered_html')) {
        echo $analytics_code . "\n"; // 管理员直接输出
    } else {
        echo wp_kses_post($analytics_code) . "\n"; // 普通用户安全输出
    }
}
    ?>
    

    
    <!-- Font Awesome - 使用国内CDN -->
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    
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
        try {
            // 移动端菜单切换
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            
            if (mobileMenuButton && mobileMenu && menuIcon) {
                    mobileMenuButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        const isHidden = mobileMenu.classList.contains('hidden');
                        
                        if (isHidden) {
                            // 显示菜单
                            mobileMenu.classList.remove('hidden');
                            menuIcon.classList.remove('fa-bars');
                            menuIcon.classList.add('fa-times');
                        } else {
                            // 隐藏菜单
                            mobileMenu.classList.add('hidden');
                            menuIcon.classList.remove('fa-times');
                            menuIcon.classList.add('fa-bars');
                        }
                    });
                
                    // 移动端二级菜单处理
                    const mobileMenuItems = document.getElementById('mobile-menu-items');
                    if (mobileMenuItems) {
                        // 处理二级菜单展开/收起
                        const dropdownTriggers = mobileMenuItems.querySelectorAll('.mobile-dropdown-trigger');
                        dropdownTriggers.forEach(trigger => {
                            trigger.addEventListener('click', function(e) {
                                e.preventDefault();
                                
                                const parentLi = this.closest('li');
                                const submenu = parentLi.querySelector('.mobile-sub-menu');
                                const chevron = this.querySelector('.fa-chevron-down');
                                
                                if (submenu) {
                                    const isHidden = submenu.classList.contains('hidden');
                                    
                                    // 关闭其他打开的子菜单
                                    const allSubmenus = mobileMenuItems.querySelectorAll('.mobile-sub-menu');
                                    const allChevrons = mobileMenuItems.querySelectorAll('.mobile-dropdown-trigger .fa-chevron-down');
                                    
                                    allSubmenus.forEach(menu => menu.classList.add('hidden'));
                                    allChevrons.forEach(icon => {
                                        icon.style.transform = 'rotate(0deg)';
                                    });
                                    
                                    if (isHidden) {
                                        // 显示当前子菜单
                                        submenu.classList.remove('hidden');
                                        if (chevron) {
                                            chevron.style.transform = 'rotate(180deg)';
                                        }
                                    }
                                }
                            });
                        });
                        
                        // 点击非下拉菜单项后关闭菜单
                        mobileMenuItems.addEventListener('click', function(e) {
                            if (e.target.tagName === 'A' && !e.target.classList.contains('mobile-dropdown-trigger')) {
                                mobileMenuButton.click();
                            }
                        });
                    }
            }
        
            // 桌面端下拉菜单 - 使用更通用的选择器
            let menusWithSubmenus = [];
            
            // 查找有子菜单的菜单项
            const selectors = [
                '.main-nav .menu > li',
                'nav .menu > li', 
                '.navbar .menu > li',
                'nav ul > li',
                '.navigation ul > li',
                '.nav ul > li',
                'header nav ul > li',
                '.header nav ul > li',
                'ul[id*="menu"] > li',
                '.menu-item'
            ];
            
            for (const selector of selectors) {
                const menuItems = document.querySelectorAll(selector);
                const itemsWithSubmenus = Array.from(menuItems).filter(item => item.querySelector('.sub-menu'));
                if (itemsWithSubmenus.length > 0) {
                    menusWithSubmenus = itemsWithSubmenus;
                    break;
                }
            }
            
            menusWithSubmenus.forEach(menu => {
                const trigger = menu.querySelector('a');
                const content = menu.querySelector('.sub-menu');
                
                if (trigger && content) {
                    let hoverTimeout;
                    let isHoveringMenu = false;
                    let isHoveringSubmenu = false;
                    
                    // 显示子菜单
                    function showSubmenu() {
                        clearTimeout(hoverTimeout);
                        content.style.display = 'block';
                        content.style.opacity = '1';
                        content.style.visibility = 'visible';
                        content.style.transform = 'translateY(0)';
                    }
                    
                    // 隐藏子菜单
                    function hideSubmenu() {
                        if (!isHoveringMenu && !isHoveringSubmenu) {
                            hoverTimeout = setTimeout(() => {
                                if (!isHoveringMenu && !isHoveringSubmenu) {
                                    content.style.opacity = '0';
                                    content.style.visibility = 'hidden';
                                    content.style.transform = 'translateY(-10px)';
                                    setTimeout(() => {
                                        if (content.style.opacity === '0') {
                                            content.style.display = 'none';
                                        }
                                    }, 300);
                                }
                            }, 500); // 增加延迟时间到500ms
                        }
                    }
                    
                    // 主菜单项事件
                    menu.addEventListener('mouseenter', () => {
                        isHoveringMenu = true;
                        showSubmenu();
                    });
                    
                    menu.addEventListener('mouseleave', () => {
                        isHoveringMenu = false;
                        hideSubmenu();
                    });
                    
                    // 子菜单事件
                    content.addEventListener('mouseenter', () => {
                        isHoveringSubmenu = true;
                        clearTimeout(hoverTimeout);
                    });
                    
                    content.addEventListener('mouseleave', () => {
                        isHoveringSubmenu = false;
                        hideSubmenu();
                    });
                }
            });
        } catch (error) {
            // 菜单初始化错误处理
        }
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
        <div class="md:hidden bg-white border-t border-gray-200 shadow-lg hidden" id="mobile-menu">
            <div class="px-4 py-4 space-y-4">
                <!-- 移动端搜索 -->
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="relative">
                        <input type="search" 
                               class="w-full px-4 py-3 pr-12 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400" 
                               placeholder="<?php echo esc_attr(get_option('xman_search_placeholder', '搜索文章...')); ?>" 
                               value="<?php echo get_search_query(); ?>" 
                               name="s">
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-blue-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            
                <!-- 菜单项容器 -->
                <div class="space-y-2" id="mobile-menu-items">
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
    echo '<a href="' . esc_url(home_url('/')) . '" class="flex items-center px-5 py-4 text-gray-700 hover:text-blue-600 bg-white/60 hover:bg-blue-50/80 rounded-2xl transition-all duration-300 font-medium group shadow-sm border border-gray-100/50 hover:border-blue-200/60 hover:shadow-md transform hover:-translate-y-0.5">'
         . '<i class="fas fa-home w-5 h-5 mr-4 text-gray-400 group-hover:text-blue-500 transition-all duration-300"></i>'
         . '<span class="flex-1">首页</span>'
         . '<i class="fas fa-chevron-right w-3 h-3 text-gray-300 group-hover:text-blue-400 transition-all duration-300 group-hover:translate-x-1"></i>'
         . '</a>';
    
    // 分类页面
    $categories = get_categories(array('number' => 5));
    $category_icons = ['fas fa-folder', 'fas fa-tag', 'fas fa-bookmark', 'fas fa-star', 'fas fa-heart'];
    foreach ($categories as $index => $category) {
        $icon = isset($category_icons[$index]) ? $category_icons[$index] : 'fas fa-folder';
        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="flex items-center px-5 py-4 text-gray-700 hover:text-blue-600 bg-white/60 hover:bg-blue-50/80 rounded-2xl transition-all duration-300 font-medium group shadow-sm border border-gray-100/50 hover:border-blue-200/60 hover:shadow-md transform hover:-translate-y-0.5">'
             . '<i class="' . $icon . ' w-5 h-5 mr-4 text-gray-400 group-hover:text-blue-500 transition-all duration-300"></i>'
             . '<span class="flex-1">' . esc_html($category->name) . '</span>'
             . '<i class="fas fa-chevron-right w-3 h-3 text-gray-300 group-hover:text-blue-400 transition-all duration-300 group-hover:translate-x-1"></i>'
             . '</a>';
    }
    
    // 关于页面
    $about_page = get_page_by_path('about');
    if ($about_page) {
        echo '<a href="' . esc_url(get_permalink($about_page)) . '" class="flex items-center px-5 py-4 text-gray-700 hover:text-blue-600 bg-white/60 hover:bg-blue-50/80 rounded-2xl transition-all duration-300 font-medium group shadow-sm border border-gray-100/50 hover:border-blue-200/60 hover:shadow-md transform hover:-translate-y-0.5">'
             . '<i class="fas fa-info-circle w-5 h-5 mr-4 text-gray-400 group-hover:text-blue-500 transition-all duration-300"></i>'
             . '<span class="flex-1">关于</span>'
             . '<i class="fas fa-chevron-right w-3 h-3 text-gray-300 group-hover:text-blue-400 transition-all duration-300 group-hover:translate-x-1"></i>'
             . '</a>';
    }
}
?>