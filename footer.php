<?php
/**
 * X-Man AI主题 - 页脚模板
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */
?>

<footer class="bg-gray-50 text-gray-800 border-t border-gray-200">
    <div class="max-w-1500 mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- 左侧：博客介绍 -->
            <div class="footer-site-info">
                <h3 class="text-lg font-bold mb-3 text-gray-900"><?php echo esc_html(get_option('xman_footer_title', get_bloginfo('name'))); ?></h3>
                <p class="text-gray-600 text-sm leading-relaxed"><?php echo esc_html(get_option('xman_footer_desc', get_bloginfo('description'))); ?></p>
            </div>
            
            <!-- 中间：快速链接 -->
            <div class="footer-quick-links">
                <h3 class="text-lg font-semibold mb-3 text-gray-900">快速链接</h3>
                <?php
                // 检查是否有页脚菜单
                 if (has_nav_menu('footer')) {
                     wp_nav_menu(array(
                         'theme_location' => 'footer',
                         'menu_class' => 'space-y-1',
                         'container' => false,
                         'fallback_cb' => false,
                         'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                         'link_before' => '',
                         'link_after' => ''
                     ));
                } else {
                    // 如果没有设置页脚菜单，显示默认链接
                    echo '<ul class="space-y-1">';
                    $quick_links = get_option('xman_footer_links', array(
                        array('title' => '首页', 'url' => home_url('/')),
                        array('title' => '关于我们', 'url' => '#'),
                        array('title' => '联系我们', 'url' => '#'),
                        array('title' => '隐私政策', 'url' => '#')
                    ));
                    
                    foreach ($quick_links as $link) :
                    ?>
                        <li><a href="<?php echo esc_url($link['url']); ?>" class="text-gray-600 hover:text-blue-600 transition-colors text-sm"><?php echo esc_html($link['title']); ?></a></li>
                    <?php 
                    endforeach;
                    echo '</ul>';
                }
                ?>
            </div>
            
            <!-- 右侧：联系方式 -->
            <div class="footer-contact">
                <h3 class="text-lg font-semibold mb-3 text-gray-900">联系方式</h3>
                <div class="space-y-2">
                    <?php if ($email = get_option('xman_contact_email', 'contact@xxxx.im')) : ?>
                        <div class="flex items-center text-gray-600 text-sm">
                            <i class="fas fa-envelope mr-2 w-4 text-blue-600"></i>
                            <span><?php echo esc_html($email); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($phone = get_option('xman_contact_phone', '')) : ?>
                        <div class="flex items-center text-gray-600 text-sm">
                            <i class="fas fa-phone mr-2 w-4 text-blue-600"></i>
                            <span><?php echo esc_html($phone); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($address = get_option('xman_contact_address', '')) : ?>
                        <div class="flex items-center text-gray-600 text-sm">
                            <i class="fas fa-map-marker-alt mr-2 w-4 text-blue-600"></i>
                            <span><?php echo esc_html($address); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($work_time = get_option('xman_work_time', '')) : ?>
                        <div class="flex items-center text-gray-600 text-sm">
                            <i class="fas fa-clock mr-2 w-4 text-blue-600"></i>
                            <span><?php echo esc_html($work_time); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- 版权信息 -->
        <div class="mt-8 pt-8 border-t border-gray-300 footer-copyright">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-500 text-sm mb-4 md:mb-0">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html(get_option('xman_site_name', get_bloginfo('name'))); ?>. <?php echo esc_html(get_option('xman_copyright_text', '版权所有，保留所有权利。')); ?> | 主题开发：<a href="https://xxxx.im" target="_blank" class="text-blue-600 hover:text-blue-700">xxxx.im</a> | 本站主题：<a href="https://github.com/imrelax/xxxx-ai" target="_blank" class="text-blue-600 hover:text-blue-700">XXXX-AI</a> | Powered by <a href="https://wordpress.org" target="_blank" class="text-blue-600 hover:text-blue-700">WordPress</a></p>
                </div>
                
                <!-- 备案信息 -->
                <?php if ($icp = get_option('xman_icp_number')) : ?>
                    <div class="text-gray-500 text-sm">
                        <a href="https://beian.miit.gov.cn" target="_blank" class="hover:text-gray-700 transition-colors"><?php echo esc_html($icp); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<!-- 返回顶部按钮 -->
<button id="back-to-top" class="fixed bottom-8 right-8 bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 opacity-0 invisible z-50 flex items-center justify-center" title="返回顶部">
    <i class="fas fa-chevron-up text-lg"></i>
</button>

<script>
// 页面加载完成后的自定义脚本
document.addEventListener('DOMContentLoaded', function() {
    console.log('X-Man AI Theme Loaded');
    
    // 平滑滚动功能已在theme.js中实现
    
    // 返回顶部功能
    const backToTopBtn = document.getElementById('back-to-top');
    
    // 显示/隐藏返回顶部按钮
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.remove('opacity-0', 'invisible');
            backToTopBtn.classList.add('opacity-100', 'visible');
        } else {
            backToTopBtn.classList.add('opacity-0', 'invisible');
            backToTopBtn.classList.remove('opacity-100', 'visible');
        }
    });
    
    // 点击返回顶部
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // 幻灯片功能已移至theme.js文件中
});
</script>

<?php
// 输出自定义页脚代码
$footer_code = get_option('xman_footer_code', '');
if (!empty($footer_code)) {
    echo wp_kses_post($footer_code) . "\n";
}

// WordPress页脚钩子
wp_footer();
?>

</body>
</html>