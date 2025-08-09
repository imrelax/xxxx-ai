<?php
/**
 * AI主题设置后台管理
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 添加AI主题设置菜单
 */
function xman_ai_admin_menu() {
    add_menu_page(
        'AI主题设置',
        'AI主题设置',
        'manage_options',
        'xman-ai-settings',
        'xman_ai_settings_page',
        'dashicons-admin-customizer',
        30
    );
    
    // 添加子菜单
    add_submenu_page(
        'xman-ai-settings',
        '网站基础设置',
        '网站基础设置',
        'manage_options',
        'xman-ai-settings',
        'xman_ai_settings_page'
    );
    
    add_submenu_page(
        'xman-ai-settings',
        '广告位管理',
        '广告位管理',
        'manage_options',
        'xman-ai-ads',
        'xman_ai_ads_page'
    );
    
    add_submenu_page(
        'xman-ai-settings',
        '推荐站点',
        '推荐站点',
        'manage_options',
        'xman-ai-sites',
        'xman_ai_sites_page'
    );
    
    add_submenu_page(
        'xman-ai-settings',
        '统计代码',
        '统计代码',
        'manage_options',
        'xman-ai-analytics',
        'xman_ai_analytics_page'
    );
}
add_action('admin_menu', 'xman_ai_admin_menu');

/**
 * 注册设置选项
 */
function xman_ai_register_settings() {
    // 网站基础信息
    register_setting('xman_ai_basic', 'xman_site_name');
    register_setting('xman_ai_basic', 'xman_site_description');
    register_setting('xman_ai_basic', 'xman_site_keywords');
    register_setting('xman_ai_basic', 'xman_site_logo');
    register_setting('xman_ai_basic', 'xman_site_favicon');
    
    // 页脚信息
    register_setting('xman_ai_basic', 'xman_footer_title');
    register_setting('xman_ai_basic', 'xman_footer_desc');
    register_setting('xman_ai_basic', 'xman_footer_copyright');
    register_setting('xman_ai_basic', 'xman_footer_icp');
    
    // 网站运行信息
    register_setting('xman_ai_basic', 'xman_site_start_date');
    
    // 联系信息
    register_setting('xman_ai_basic', 'xman_contact_email');
    register_setting('xman_ai_basic', 'xman_contact_phone');
    register_setting('xman_ai_basic', 'xman_contact_address');
    register_setting('xman_ai_basic', 'xman_work_time');
    
    // 站长信息
    register_setting('xman_ai_basic', 'xman_author_name');
    register_setting('xman_ai_basic', 'xman_author_title');
    register_setting('xman_ai_basic', 'xman_author_avatar');
    register_setting('xman_ai_basic', 'xman_author_bio');
    register_setting('xman_ai_basic', 'xman_author_email');
    register_setting('xman_ai_basic', 'xman_author_location');
    register_setting('xman_ai_basic', 'xman_author_weibo');
    register_setting('xman_ai_basic', 'xman_author_github');
    register_setting('xman_ai_basic', 'xman_author_twitter');
    register_setting('xman_ai_basic', 'xman_author_wechat');
    
    // 幻灯片设置
    register_setting('xman_ai_basic', 'xman_slide_post_ids');
    
    // 广告位设置
    register_setting('xman_ai_ads', 'xman_ad1_code');
    register_setting('xman_ai_ads', 'xman_ad2_code');
    register_setting('xman_ai_ads', 'xman_ad3_code');
    register_setting('xman_ai_ads', 'xman_ad4_code');
    register_setting('xman_ai_ads', 'xman_ad5_code');
    
    // 推荐站点
    for ($i = 1; $i <= 5; $i++) {
        register_setting('xman_ai_sites', "xman_recommend_site_{$i}_title");
        register_setting('xman_ai_sites', "xman_recommend_site_{$i}_url");
        register_setting('xman_ai_sites', "xman_recommend_site_{$i}_icon");
        register_setting('xman_ai_sites', "xman_recommend_site_{$i}_desc");
    }
    
    // 统计代码设置
    register_setting('xman_ai_analytics', 'xman_analytics_code');
}
add_action('admin_init', 'xman_ai_register_settings');

/**
 * 主设置页面
 */
function xman_ai_settings_page() {
    if (isset($_POST['submit'])) {
        // 验证nonce
        if (!wp_verify_nonce($_POST['_wpnonce'], 'xman_ai_settings')) {
            wp_die('安全验证失败');
        }
        
        // 处理表单提交
        $fields = [
            'xman_site_name', 'xman_site_description', 'xman_site_keywords',
            'xman_site_logo', 'xman_site_favicon', 'xman_logo_icon', 'xman_search_placeholder',
            'xman_footer_title', 'xman_footer_desc', 'xman_footer_links', 'xman_contact_email',
            'xman_copyright_text', 'xman_icp_number', 'xman_site_start_date',
            'xman_contact_phone', 'xman_contact_address', 'xman_work_time',
            'xman_author_name', 'xman_author_title', 'xman_author_avatar', 'xman_author_bio',
            'xman_author_email', 'xman_author_location', 'xman_author_weibo', 'xman_author_github',
            'xman_author_twitter', 'xman_author_wechat',
            'xman_slide_post_ids', 'xman_custom_css'
        ];
        
        // 处理快捷链接
        for ($i = 1; $i <= 5; $i++) {
            $fields[] = "xman_quick_link_{$i}_title";
            $fields[] = "xman_quick_link_{$i}_icon";
            $fields[] = "xman_quick_link_{$i}_desc";
            
            // 特殊处理URL字段，允许为空并设置默认值
            if (isset($_POST["xman_quick_link_{$i}_url"])) {
                $url_value = sanitize_text_field($_POST["xman_quick_link_{$i}_url"]);
                // 如果URL为空，设置默认值为 #
                if (empty($url_value)) {
                    $url_value = '#';
                }
                update_option("xman_quick_link_{$i}_url", $url_value);
            }
        }
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_option($field, sanitize_text_field($_POST[$field]));
            }
        }
        
        echo '<div class="notice notice-success"><p>设置已保存！</p></div>';
    }
    
    // 获取当前设置值
    $site_name = get_option('xman_site_name', get_bloginfo('name'));
    $site_description = get_option('xman_site_description', get_bloginfo('description'));
    $site_keywords = get_option('xman_site_keywords', '');
    $site_logo = get_option('xman_site_logo', '');
    $site_favicon = get_option('xman_site_favicon', '');
    $logo_icon = get_option('xman_logo_icon', 'fas fa-code');
    $search_placeholder = get_option('xman_search_placeholder', '搜索文章...');
    
    $footer_title = get_option('xman_footer_title', get_bloginfo('name'));
    $footer_desc = get_option('xman_footer_desc', get_bloginfo('description'));
    $contact_email = get_option('xman_contact_email', 'contact@xxxx.im');
    $copyright_text = get_option('xman_copyright_text', '版权所有，保留所有权利。');
    $icp_number = get_option('xman_icp_number', '');
    
    $author_name = get_option('xman_author_name', 'X-Man');
    $author_title = get_option('xman_author_title', '全栈开发工程师');
    $author_avatar = get_option('xman_author_avatar', '');
    $author_bio = get_option('xman_author_bio', '欢迎来到我的博客！');
    $author_email = get_option('xman_author_email', '');
    $author_location = get_option('xman_author_location', '中国·北京');
    $author_weibo = get_option('xman_author_weibo', '');
    $author_github = get_option('xman_author_github', '');
    $author_twitter = get_option('xman_author_twitter', '');
    $author_wechat = get_option('xman_author_wechat', '');
    
    $slide_post_ids = get_option('xman_slide_post_ids', '');
    $custom_css = get_option('xman_custom_css', '');
    
    ?>
    <div class="wrap">
        <h1>AI主题设置</h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('xman_ai_settings'); ?>
            
            <!-- 网站基础信息 -->
            <div class="xman-admin-section">
                <h2>🌐 网站基础信息</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">网站名称</th>
                        <td>
                            <input type="text" name="xman_site_name" value="<?php echo esc_attr($site_name); ?>" class="regular-text" />
                            <p class="description">网站的标题名称</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">网站描述</th>
                        <td>
                            <textarea name="xman_site_description" rows="3" class="large-text"><?php echo esc_textarea($site_description); ?></textarea>
                            <p class="description">网站的简短描述，用于SEO</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">网站关键词</th>
                        <td>
                            <input type="text" name="xman_site_keywords" value="<?php echo esc_attr($site_keywords); ?>" class="large-text" />
                            <p class="description">网站关键词，用逗号分隔</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Logo图标</th>
                        <td>
                            <input type="text" name="xman_logo_icon" value="<?php echo esc_attr($logo_icon); ?>" class="regular-text" />
                            <p class="description">Font Awesome图标类名，如：fas fa-code</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">搜索框占位符</th>
                        <td>
                            <input type="text" name="xman_search_placeholder" value="<?php echo esc_attr($search_placeholder); ?>" class="regular-text" />
                            <p class="description">搜索框的占位符文字</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">网站Logo</th>
                        <td>
                            <input type="url" name="xman_site_logo" value="<?php echo esc_url($site_logo); ?>" class="regular-text" />
                            <p class="description">网站Logo图片URL</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">网站图标</th>
                        <td>
                            <input type="url" name="xman_site_favicon" value="<?php echo esc_url($site_favicon); ?>" class="regular-text" />
                            <p class="description">网站Favicon图标URL</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- 页脚信息 -->
            <div class="xman-admin-section">
                <h2>🦶 页脚信息设置</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">页脚标题</th>
                        <td>
                            <input type="text" name="xman_footer_title" value="<?php echo esc_attr($footer_title); ?>" class="regular-text" />
                            <p class="description">页脚显示的网站标题</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">页脚描述</th>
                        <td>
                            <textarea name="xman_footer_desc" rows="3" class="large-text"><?php echo esc_textarea($footer_desc); ?></textarea>
                            <p class="description">页脚显示的网站介绍</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">联系邮箱</th>
                        <td>
                            <input type="email" name="xman_contact_email" value="<?php echo esc_attr($contact_email); ?>" class="regular-text" />
                            <p class="description">页脚显示的联系邮箱</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">版权信息</th>
                        <td>
                            <input type="text" name="xman_copyright_text" value="<?php echo esc_attr($copyright_text); ?>" class="large-text" />
                            <p class="description">页脚版权信息</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">备案信息</th>
                        <td>
                            <input type="text" name="xman_icp_number" value="<?php echo esc_attr($icp_number); ?>" class="regular-text" />
                            <p class="description">网站备案号</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">网站创建日期</th>
                        <td>
                            <input type="date" name="xman_site_start_date" value="<?php echo esc_attr(get_option('xman_site_start_date', date('Y-m-d'))); ?>" class="regular-text" />
                            <p class="description">网站开始运行的日期，用于计算运行天数</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">联系电话</th>
                        <td>
                            <input type="text" name="xman_contact_phone" value="<?php echo esc_attr(get_option('xman_contact_phone', '')); ?>" class="regular-text" />
                            <p class="description">页脚显示的联系电话</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">联系地址</th>
                        <td>
                            <input type="text" name="xman_contact_address" value="<?php echo esc_attr(get_option('xman_contact_address', '')); ?>" class="regular-text" />
                            <p class="description">页脚显示的联系地址</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">工作时间</th>
                        <td>
                            <input type="text" name="xman_work_time" value="<?php echo esc_attr(get_option('xman_work_time', '')); ?>" class="regular-text" />
                            <p class="description">页脚显示的工作时间</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- 站长信息 -->
            <div class="xman-admin-section">
                <h2>👤 站长基础信息</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">站长昵称</th>
                        <td>
                            <input type="text" name="xman_author_name" value="<?php echo esc_attr($author_name); ?>" class="regular-text" />
                            <p class="description">站长显示名称</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">职位标题</th>
                        <td>
                            <input type="text" name="xman_author_title" value="<?php echo esc_attr($author_title); ?>" class="regular-text" />
                            <p class="description">站长职位或专业领域</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">站长头像</th>
                        <td>
                            <input type="url" name="xman_author_avatar" value="<?php echo esc_url($author_avatar); ?>" class="regular-text" />
                            <p class="description">站长头像图片URL</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">站长简介</th>
                        <td>
                            <textarea name="xman_author_bio" rows="3" class="large-text"><?php echo esc_textarea($author_bio); ?></textarea>
                            <p class="description">站长个人简介</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">邮箱地址</th>
                        <td>
                            <input type="email" name="xman_author_email" value="<?php echo esc_attr($author_email); ?>" class="regular-text" />
                            <p class="description">站长联系邮箱</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">所在地区</th>
                        <td>
                            <input type="text" name="xman_author_location" value="<?php echo esc_attr($author_location); ?>" class="regular-text" />
                            <p class="description">站长所在地区</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">微博链接</th>
                        <td>
                            <input type="url" name="xman_author_weibo" value="<?php echo esc_url($author_weibo); ?>" class="regular-text" />
                            <p class="description">站长微博主页链接</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">GitHub链接</th>
                        <td>
                            <input type="url" name="xman_author_github" value="<?php echo esc_url($author_github); ?>" class="regular-text" />
                            <p class="description">站长GitHub主页链接</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Twitter链接</th>
                        <td>
                            <input type="url" name="xman_author_twitter" value="<?php echo esc_url($author_twitter); ?>" class="regular-text" />
                            <p class="description">站长Twitter主页链接</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">微信二维码</th>
                        <td>
                            <input type="url" name="xman_author_wechat" value="<?php echo esc_url($author_wechat); ?>" class="regular-text" />
                            <p class="description">微信二维码图片URL</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- 幻灯片设置 -->
            <div class="xman-admin-section">
                <h2>🎬 首页幻灯片设置</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">幻灯片文章ID</th>
                        <td>
                            <input type="text" name="xman_slide_post_ids" value="<?php echo esc_attr($slide_post_ids); ?>" class="large-text" />
                            <p class="description">指定显示在首页幻灯片的文章ID，多个ID用英文逗号分隔，如：1,2,3,4</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- 快捷链接设置 -->
            <div class="xman-admin-section">
                <h2>🔗 首页快捷链接设置</h2>
                <table class="form-table">
                    <?php for ($i = 1; $i <= 5; $i++) : 
                        $title = get_option("xman_quick_link_{$i}_title", '');
                        $url = get_option("xman_quick_link_{$i}_url", '#');
                        $icon = get_option("xman_quick_link_{$i}_icon", 'fas fa-link');
                        $desc = get_option("xman_quick_link_{$i}_desc", '');
                    ?>
                    <tr>
                        <th scope="row">快捷链接 <?php echo esc_html($i); ?></th>
                        <td>
                            <table class="widefat" style="margin-bottom: 10px;">
                                <tr>
                                    <td style="width: 100px;">标题：</td>
                                    <td><input type="text" name="xman_quick_link_<?php echo $i; ?>_title" value="<?php echo esc_attr($title); ?>" class="regular-text" /></td>
                                </tr>
                                <tr>
                                    <td>链接：</td>
                                    <td><input type="text" name="xman_quick_link_<?php echo $i; ?>_url" value="<?php echo esc_url($url); ?>" class="regular-text" placeholder="可选，留空则使用 # 作为默认链接" /></td>
                                </tr>
                                <tr>
                                    <td>图标：</td>
                                    <td><input type="text" name="xman_quick_link_<?php echo $i; ?>_icon" value="<?php echo esc_attr($icon); ?>" class="regular-text" placeholder="如：fas fa-link" /></td>
                                </tr>
                                <tr>
                                    <td>描述：</td>
                                    <td><input type="text" name="xman_quick_link_<?php echo $i; ?>_desc" value="<?php echo esc_attr($desc); ?>" class="regular-text" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php endfor; ?>
                </table>
            </div>
            
            <!-- 自定义样式 -->
            <div class="xman-admin-section">
                <h2>🎨 自定义样式</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">自定义CSS</th>
                        <td>
                            <textarea name="xman_custom_css" rows="10" class="large-text code"><?php echo esc_textarea($custom_css); ?></textarea>
                            <p class="description">在这里添加自定义CSS代码，将会输出到网站头部</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <?php submit_button('保存设置'); ?>
        </form>
    </div>
    
    <style>
    .xman-admin-section {
        background: #fff;
        margin: 20px 0;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .xman-admin-section h2 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #0073aa;
        padding-bottom: 10px;
    }
    </style>
    <?php
}

/**
 * 广告位管理页面
 */
function xman_ai_ads_page() {
    if (isset($_POST['submit'])) {
        // 验证nonce
        if (!wp_verify_nonce($_POST['_wpnonce'], 'xman_ai_ads')) {
            wp_die('安全验证失败');
        }
        
        // 处理表单提交
        $ad_fields = ['xman_ad1_code', 'xman_ad2_code', 'xman_ad3_code', 'xman_ad4_code', 'xman_ad5_code'];
        
        foreach ($ad_fields as $field) {
            if (isset($_POST[$field])) {
                // 直接保存广告代码，不进行HTML过滤以保持代码完整性
                $ad_code = stripslashes($_POST[$field]);
                update_option($field, $ad_code);
            }
        }
        
        echo '<div class="notice notice-success"><p>广告位设置已保存！</p></div>';
    }
    
    // 获取当前广告代码
    $ad1_code = get_option('xman_ad1_code', '');
    $ad2_code = get_option('xman_ad2_code', '');
    $ad3_code = get_option('xman_ad3_code', '');
    $ad4_code = get_option('xman_ad4_code', '');
    $ad5_code = get_option('xman_ad5_code', '');
    
    ?>
    <div class="wrap">
        <h1>广告位管理</h1>
        <p>在这里设置各个广告位的HTML代码，支持Google AdSense、百度联盟等广告代码。</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('xman_ai_ads'); ?>
            
            <div class="xman-admin-section">
                <h2>📍 AD1 - 侧边栏站长信息下方</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">广告代码</th>
                        <td>
                            <textarea name="xman_ad1_code" rows="8" class="large-text code"><?php echo esc_textarea($ad1_code); ?></textarea>
                            <p class="description">推荐尺寸：300x250，显示位置：侧边栏站长信息下方</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="xman-admin-section">
                <h2>📍 AD2 - 侧边栏热门文章间</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">广告代码</th>
                        <td>
                            <textarea name="xman_ad2_code" rows="8" class="large-text code"><?php echo esc_textarea($ad2_code); ?></textarea>
                            <p class="description">推荐尺寸：300x250，显示位置：侧边栏热门文章和站长推荐之间</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="xman-admin-section">
                <h2>📍 AD3 - 文章内容上方</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">广告代码</th>
                        <td>
                            <textarea name="xman_ad3_code" rows="8" class="large-text code"><?php echo esc_textarea($ad3_code); ?></textarea>
                            <p class="description">推荐尺寸：728x90，显示位置：文章内容上方</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="xman-admin-section">
                <h2>📍 AD4 - 文章内容下方</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">广告代码</th>
                        <td>
                            <textarea name="xman_ad4_code" rows="8" class="large-text code"><?php echo esc_textarea($ad4_code); ?></textarea>
                            <p class="description">推荐尺寸：728x90，显示位置：文章内容下方</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="xman-admin-section">
                <h2>📍 AD5 - 首页文章列表间</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">广告代码</th>
                        <td>
                            <textarea name="xman_ad5_code" rows="8" class="large-text code"><?php echo esc_textarea($ad5_code); ?></textarea>
                            <p class="description">推荐尺寸：300x250，显示位置：首页文章列表第3篇文章后</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <?php submit_button('保存广告设置'); ?>
        </form>
    </div>
    
    <style>
    .xman-admin-section {
        background: #fff;
        margin: 20px 0;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .xman-admin-section h2 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #0073aa;
        padding-bottom: 10px;
    }
    </style>
    <?php
}

/**
 * 推荐站点管理页面
 */
function xman_ai_sites_page() {
    if (isset($_POST['submit'])) {
        // 验证nonce
        if (!wp_verify_nonce($_POST['_wpnonce'], 'xman_ai_sites')) {
            wp_die('安全验证失败');
        }
        
        // 处理表单提交
        for ($i = 1; $i <= 5; $i++) {
            $fields = [
                "xman_recommend_site_{$i}_title",
                "xman_recommend_site_{$i}_url",
                "xman_recommend_site_{$i}_icon",
                "xman_recommend_site_{$i}_desc"
            ];
            
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    update_option($field, sanitize_text_field($_POST[$field]));
                }
            }
        }
        
        echo '<div class="notice notice-success"><p>推荐站点设置已保存！</p></div>';
    }
    
    ?>
    <div class="wrap">
        <h1>推荐站点管理</h1>
        <p>设置侧边栏显示的推荐站点链接，最多可设置5个推荐站点。</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('xman_ai_sites'); ?>
            
            <div class="xman-admin-section">
                <h2>🔗 推荐站点设置</h2>
                
                <?php for ($i = 1; $i <= 5; $i++) : 
                    $title = get_option("xman_recommend_site_{$i}_title", '');
                    $url = get_option("xman_recommend_site_{$i}_url", '');
                    $icon = get_option("xman_recommend_site_{$i}_icon", 'fas fa-link');
                    $desc = get_option("xman_recommend_site_{$i}_desc", '');
                ?>
                
                <div class="site-setting-group" style="border: 1px solid #ddd; padding: 20px; margin: 20px 0; border-radius: 8px; background: #f9f9f9;">
                    <h3>推荐站点 <?php echo esc_html($i); ?></h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row">站点名称</th>
                            <td>
                                <input type="text" name="xman_recommend_site_<?php echo $i; ?>_title" value="<?php echo esc_attr($title); ?>" class="regular-text" placeholder="例如：GitHub" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">站点链接</th>
                            <td>
                                <input type="url" name="xman_recommend_site_<?php echo $i; ?>_url" value="<?php echo esc_attr($url); ?>" class="regular-text" placeholder="https://github.com" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">图标类名</th>
                            <td>
                                <input type="text" name="xman_recommend_site_<?php echo $i; ?>_icon" value="<?php echo esc_attr($icon); ?>" class="regular-text" placeholder="fab fa-github" />
                                <p class="description">使用 FontAwesome 图标类名，例如：fab fa-github、fas fa-link</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">站点描述</th>
                            <td>
                                <input type="text" name="xman_recommend_site_<?php echo $i; ?>_desc" value="<?php echo esc_attr($desc); ?>" class="regular-text" placeholder="开源代码托管平台" />
                            </td>
                        </tr>
                    </table>
                </div>
                
                <?php endfor; ?>
                
                <?php submit_button('保存设置'); ?>
            </div>
        </form>
        
        <div class="xman-admin-section">
            <h2>📋 推荐站点预览</h2>
            <?php
            $has_sites = false;
            echo '<div class="xman-sites-preview">';
            
            for ($i = 1; $i <= 5; $i++) {
                $title = get_option("xman_recommend_site_{$i}_title", '');
                $url = get_option("xman_recommend_site_{$i}_url", '');
                $icon = get_option("xman_recommend_site_{$i}_icon", 'fas fa-link');
                $desc = get_option("xman_recommend_site_{$i}_desc", '');
                
                if (!empty($title) && !empty($url)) {
                    $has_sites = true;
                    echo '<div class="site-item" style="margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 4px; display: flex; align-items: center;">';
                    echo '<i class="' . esc_attr($icon) . '" style="margin-right: 10px; color: #666;"></i>';
                    echo '<div>';
                    echo '<strong><a href="' . esc_url($url) . '" target="_blank">' . esc_html($title) . '</a></strong>';
                    if ($desc) {
                        echo '<br><span style="color: #666; font-size: 12px;">' . esc_html($desc) . '</span>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
            }
            
            if (!$has_sites) {
                echo '<p>暂无推荐站点，请在上方添加站点信息。</p>';
            }
            
            echo '</div>';
            ?>
        </div>
    </div>
    
    <style>
    .xman-admin-section {
        background: #fff;
        margin: 20px 0;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .xman-admin-section h2 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #0073aa;
        padding-bottom: 10px;
    }
    .xman-sites-preview {
        max-height: 400px;
        overflow-y: auto;
    }
    </style>
    <?php
}

/**
 * 获取AI主题设置选项
 */
function xman_ai_get_option($option_name, $default = '') {
    return get_option($option_name, $default);
}

/**
 * 获取广告代码
 */
function xman_ai_get_ad_code($ad_position) {
    $ad_code = get_option('xman_ad' . $ad_position . '_code', '');
    if ($ad_code) {
        return $ad_code;
    }
    return '';
}

/**
 * 统计代码管理页面
 */
function xman_ai_analytics_page() {
    if (isset($_POST['submit'])) {
        // 验证nonce
        if (!wp_verify_nonce($_POST['_wpnonce'], 'xman_ai_analytics')) {
            wp_die('安全验证失败');
        }
        
        // 处理表单提交
        if (isset($_POST['xman_analytics_code'])) {
            // 直接保存统计代码，不进行HTML过滤以保持代码完整性
            $analytics_code = stripslashes($_POST['xman_analytics_code']);
            update_option('xman_analytics_code', $analytics_code);
            echo '<div class="notice notice-success"><p>统计代码设置已保存！</p></div>';
        }
    }
    
    // 获取当前统计代码
    $analytics_code = get_option('xman_analytics_code', '');
    
    ?>
    <div class="wrap">
        <h1>统计代码管理</h1>
        <p>在这里设置网站的统计代码，支持Google Analytics、百度统计、CNZZ等第三方统计工具。</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('xman_ai_analytics'); ?>
            
            <div class="xman-admin-section">
                <h2>📊 网站统计代码</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">统计代码</th>
                        <td>
                            <textarea name="xman_analytics_code" rows="10" class="large-text code"><?php echo esc_textarea($analytics_code); ?></textarea>
                            <p class="description">粘贴统计工具提供的完整跟踪代码，包括&lt;script&gt;标签。支持Google Analytics、百度统计、CNZZ等。</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <?php submit_button('保存统计代码'); ?>
        </form>
    </div>
    
    <style>
    .xman-admin-section {
        background: #fff;
        margin: 20px 0;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .xman-admin-section h2 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #0073aa;
        padding-bottom: 10px;
    }
    </style>
    <?php
}

/**
 * 获取推荐站点列表
 */
function xman_ai_get_recommended_sites() {
    $sites = array();
    
    // 从单独的选项字段获取推荐站点
    for ($i = 1; $i <= 5; $i++) {
        $title = get_option("xman_recommend_site_{$i}_title", '');
        $url = get_option("xman_recommend_site_{$i}_url", '');
        $icon = get_option("xman_recommend_site_{$i}_icon", 'fas fa-link');
        $desc = get_option("xman_recommend_site_{$i}_desc", '');
        
        // 只有当标题和URL都不为空时才添加到列表中
        if (!empty($title) && !empty($url)) {
            $sites[] = array(
                'name' => $title,
                'url' => $url,
                'desc' => $desc,
                'icon' => $icon
            );
        }
    }
    
    return $sites;
}

/**
 * 获取统计代码
 */
function xman_ai_get_analytics_code() {
    return get_option('xman_analytics_code', '');
}