<?php
/**
 * 软件管理相关功能
 * 
 * @package XMAN_AI_Theme
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 添加软件自定义字段元框
 */
function xman_ai_add_software_meta_boxes() {
    // 为软件文章类型添加元框
    add_meta_box(
        'software_details',
        '软件详细信息',
        'xman_ai_software_details_callback',
        'software',
        'normal',
        'high'
    );
    
    add_meta_box(
        'software_downloads',
        '下载地址设置',
        'xman_ai_software_downloads_callback',
        'software',
        'normal',
        'high'
    );
    
    // 为普通文章添加软件相关元框（当选择软件类型时显示）
    add_meta_box(
        'post_software_details',
        '软件详细信息',
        'xman_ai_software_details_callback',
        'post',
        'normal',
        'high'
    );
    
    add_meta_box(
        'post_software_downloads',
        '下载地址设置',
        'xman_ai_software_downloads_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'xman_ai_add_software_meta_boxes');

/**
 * 软件详细信息元框回调函数
 */
function xman_ai_software_details_callback($post) {
    // 添加nonce字段用于安全验证
    wp_nonce_field('xman_ai_software_meta_box', 'xman_ai_software_meta_box_nonce');
    
    // 获取现有值
    $software_intro = get_post_meta($post->ID, '_software_intro', true);
    $software_devices = get_post_meta($post->ID, '_software_devices', true);
    $software_version = get_post_meta($post->ID, '_software_version', true);
    $software_size = get_post_meta($post->ID, '_software_size', true);
    $software_developer = get_post_meta($post->ID, '_software_developer', true);
    $software_license = get_post_meta($post->ID, '_software_license', true);
    
    // 设备选项
    $device_options = array(
        'ios' => 'iOS',
        'android' => 'Android',
        'macos' => 'macOS',
        'windows' => 'Windows',
        'linux' => 'Linux',
        'hongmeng' => '鸿蒙',
        'router' => '路由器',
        'other' => '其他'
    );
    
    if (!is_array($software_devices)) {
        $software_devices = array();
    }
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="software_intro">软件简介</label></th>
            <td>
                <textarea id="software_intro" name="software_intro" rows="4" cols="50" class="large-text"><?php echo esc_textarea($software_intro); ?></textarea>
                <p class="description">简要描述软件的主要功能和特点</p>
            </td>
        </tr>
        <tr>
            <th scope="row">适用设备</th>
            <td>
                <?php foreach ($device_options as $value => $label) : ?>
                    <label style="margin-right: 15px;">
                        <input type="checkbox" name="software_devices[]" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $software_devices)); ?>>
                        <?php echo esc_html($label); ?>
                    </label>
                <?php endforeach; ?>
                <p class="description">选择软件支持的设备平台</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="software_version">软件版本</label></th>
            <td>
                <input type="text" id="software_version" name="software_version" value="<?php echo esc_attr($software_version); ?>" class="regular-text">
                <p class="description">当前软件版本号</p>
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="software_developer">开发者</label></th>
            <td>
                <input type="text" id="software_developer" name="software_developer" value="<?php echo esc_attr($software_developer); ?>" class="regular-text">
                <p class="description">软件开发者或公司名称</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="software_license">许可证类型</label></th>
            <td>
                <select id="software_license" name="software_license" class="regular-text">
                    <option value="">请选择</option>
                    <option value="free" <?php selected($software_license, 'free'); ?>>免费</option>
                    <option value="freemium" <?php selected($software_license, 'freemium'); ?>>免费增值</option>
                    <option value="paid" <?php selected($software_license, 'paid'); ?>>付费</option>
                    <option value="open_source" <?php selected($software_license, 'open_source'); ?>>开源</option>
                    <option value="trial" <?php selected($software_license, 'trial'); ?>>试用版</option>
                </select>
                <p class="description">软件的许可证类型</p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * 下载地址设置元框回调函数
 */
function xman_ai_software_downloads_callback($post) {
    $software_downloads = get_post_meta($post->ID, '_software_downloads', true);
    $software_devices = get_post_meta($post->ID, '_software_devices', true);
    
    if (!is_array($software_downloads)) {
        $software_downloads = array();
    }
    
    if (!is_array($software_devices)) {
        $software_devices = array();
    }
    
    $device_options = array(
        'ios' => 'iOS',
        'android' => 'Android',
        'macos' => 'macOS',
        'windows' => 'Windows',
        'linux' => 'Linux',
        'hongmeng' => '鸿蒙',
        'router' => '路由器',
        'other' => '其他'
    );
    ?>
    <div id="software-downloads-container">
        <p class="description">为每个支持的设备平台设置下载地址（只有在上方勾选了对应设备才会显示）</p>
        
        <?php foreach ($device_options as $device => $label) : 
            $is_device_selected = in_array($device, $software_devices);
        ?>
            <div class="download-item download-item-<?php echo esc_attr($device); ?>" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; <?php echo !$is_device_selected ? 'display: none;' : ''; ?>">
                <h4><?php echo esc_html($label); ?> 下载地址</h4>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label>下载链接</label></th>
                        <td>
                            <input type="url" name="software_downloads[<?php echo esc_attr($device); ?>][url]" value="<?php echo esc_attr(isset($software_downloads[$device]['url']) ? $software_downloads[$device]['url'] : ''); ?>" class="large-text" placeholder="https://">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>下载说明</label></th>
                        <td>
                            <input type="text" name="software_downloads[<?php echo esc_attr($device); ?>][note]" value="<?php echo esc_attr(isset($software_downloads[$device]['note']) ? $software_downloads[$device]['note'] : ''); ?>" class="large-text" placeholder="如：官方下载、备用下载等">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>文件大小</label></th>
                        <td>
                            <input type="text" name="software_downloads[<?php echo esc_attr($device); ?>][size]" value="<?php echo esc_attr(isset($software_downloads[$device]['size']) ? $software_downloads[$device]['size'] : ''); ?>" class="regular-text" placeholder="如：50MB">
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
    
    <style>
    .download-item h4 {
        margin: 0 0 10px 0;
        color: #333;
    }
    .download-item .form-table {
        margin: 0;
    }
    .download-item .form-table th {
        width: 120px;
        padding: 5px 10px 5px 0;
    }
    .download-item .form-table td {
        padding: 5px 0;
    }
    </style>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // 监听设备复选框的变化
        $('input[name="software_devices[]"]').on('change', function() {
            var deviceValue = $(this).val();
            var downloadItem = $('.download-item-' + deviceValue);
            
            if ($(this).is(':checked')) {
                downloadItem.show();
            } else {
                downloadItem.hide();
                // 清空该设备的下载地址数据
                downloadItem.find('input').val('');
            }
        });
    });
    </script>
    <?php
}

/**
 * 保存软件自定义字段
 */
function xman_ai_save_software_meta_box_data($post_id) {
    // 验证nonce
    if (!isset($_POST['xman_ai_software_meta_box_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['xman_ai_software_meta_box_nonce'], 'xman_ai_software_meta_box')) {
        return;
    }
    
    // 检查用户权限
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // 检查是否为自动保存
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // 保存软件详细信息
    $fields = array(
        'software_intro',
        'software_version',
        'software_developer',
        'software_license'
    );
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // 保存适用设备
    if (isset($_POST['software_devices']) && is_array($_POST['software_devices'])) {
        $devices = array_map('sanitize_text_field', $_POST['software_devices']);
        update_post_meta($post_id, '_software_devices', $devices);
    } else {
        delete_post_meta($post_id, '_software_devices');
    }
    
    // 保存下载地址
    if (isset($_POST['software_downloads']) && is_array($_POST['software_downloads'])) {
        $downloads = array();
        foreach ($_POST['software_downloads'] as $device => $data) {
            if (!empty($data['url'])) {
                $downloads[sanitize_text_field($device)] = array(
                    'url' => esc_url_raw($data['url']),
                    'note' => sanitize_text_field($data['note']),
                    'size' => sanitize_text_field($data['size'])
                );
            }
        }
        update_post_meta($post_id, '_software_downloads', $downloads);
    } else {
        delete_post_meta($post_id, '_software_downloads');
    }
}
add_action('save_post', 'xman_ai_save_software_meta_box_data');

/**
 * 获取软件信息的辅助函数
 */
function xman_ai_get_software_info($post_id) {
    $device_labels = array(
        'ios' => 'iOS',
        'android' => 'Android',
        'macos' => 'macOS',
        'windows' => 'Windows',
        'linux' => 'Linux',
        'hongmeng' => '鸿蒙',
        'router' => '路由器',
        'other' => '其他'
    );
    
    $license_labels = array(
        'free' => '免费',
        'freemium' => '免费增值',
        'paid' => '付费',
        'open_source' => '开源',
        'trial' => '试用版'
    );
    
    $info = array(
        'intro' => get_post_meta($post_id, '_software_intro', true),
        'devices' => get_post_meta($post_id, '_software_devices', true),
        'version' => get_post_meta($post_id, '_software_version', true),
        'size' => get_post_meta($post_id, '_software_size', true),
        'developer' => get_post_meta($post_id, '_software_developer', true),
        'license' => get_post_meta($post_id, '_software_license', true),
        'downloads' => get_post_meta($post_id, '_software_downloads', true),
        'device_labels' => $device_labels,
        'license_label' => isset($license_labels[get_post_meta($post_id, '_software_license', true)]) ? $license_labels[get_post_meta($post_id, '_software_license', true)] : ''
    );
    
    if (!is_array($info['devices'])) {
        $info['devices'] = array();
    }
    
    if (!is_array($info['downloads'])) {
        $info['downloads'] = array();
    }
    
    return $info;
}

/**
 * 在软件列表中显示自定义列
 */
function xman_ai_software_custom_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key == 'title') {
            $new_columns['software_devices'] = '适用设备';
            $new_columns['software_version'] = '版本';
            $new_columns['software_license'] = '许可证';
        }
    }
    return $new_columns;
}
add_filter('manage_software_posts_columns', 'xman_ai_software_custom_columns');

/**
 * 填充自定义列内容
 */
function xman_ai_software_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'software_devices':
            $devices = get_post_meta($post_id, '_software_devices', true);
            if (is_array($devices) && !empty($devices)) {
                $device_labels = array(
                    'ios' => 'iOS',
                    'android' => 'Android',
                    'macos' => 'macOS',
                    'windows' => 'Windows',
                    'linux' => 'Linux',
                    'hongmeng' => '鸿蒙',
                    'router' => '路由器',
                    'other' => '其他'
                );
                $device_names = array();
                foreach ($devices as $device) {
                    if (isset($device_labels[$device])) {
                        $device_names[] = $device_labels[$device];
                    }
                }
                echo esc_html(implode(', ', $device_names));
            } else {
                echo '—';
            }
            break;
            
        case 'software_version':
            $version = get_post_meta($post_id, '_software_version', true);
            echo $version ? esc_html($version) : '—';
            break;
            
        case 'software_license':
            $license = get_post_meta($post_id, '_software_license', true);
            $license_labels = array(
                'free' => '免费',
                'freemium' => '免费增值',
                'paid' => '付费',
                'open_source' => '开源',
                'trial' => '试用版'
            );
            echo isset($license_labels[$license]) ? esc_html($license_labels[$license]) : '—';
            break;
    }
}
add_action('manage_software_posts_custom_column', 'xman_ai_software_custom_column_content', 10, 2);

?>