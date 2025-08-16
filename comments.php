<?php
/**
 * 评论模板文件
 * X-Man AI Theme Comments Template
 */

// 如果当前文章受密码保护且用户未输入密码，则不显示评论
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area bg-white rounded-lg shadow-sm p-6 mt-8">
    <?php if (have_comments()) : ?>
        <h3 class="comments-title text-xl font-bold text-gray-800 mb-6">
            <?php
            $comments_number = get_comments_number();
            if ($comments_number == 1) {
                echo '1 条评论';
            } else {
                printf('%s 条评论', number_format_i18n($comments_number));
            }
            ?>
        </h3>

        <ol class="comment-list space-y-6">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 50,
                'callback' => 'xman_comment_list'
            ));
            ?>
        </ol>

        <?php
        // 评论分页
        if (get_comment_pages_count() > 1 && get_option('page_comments')) :
        ?>
            <nav class="comment-navigation mt-6">
                <div class="nav-links flex justify-between">
                    <div class="nav-previous">
                        <?php previous_comments_link('← 较早评论'); ?>
                    </div>
                    <div class="nav-next">
                        <?php next_comments_link('较新评论 →'); ?>
                    </div>
                </div>
            </nav>
        <?php endif; ?>

    <?php endif; // 结束 have_comments() 检查 ?>

    <?php
    // 如果评论已关闭且有评论存在，显示提示信息
    if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
    ?>
        <p class="no-comments text-gray-500 text-center py-4">
            评论已关闭。
        </p>
    <?php endif; ?>

    <?php
    // 显示评论表单
    if (comments_open()) :
        $comment_form_args = array(
            'title_reply' => '发表评论',
            'title_reply_to' => '回复 %s',
            'cancel_reply_link' => '取消回复',
            'label_submit' => '提交评论',
            'comment_field' => '<div class="comment-form-comment mb-4">' .
                              '<label for="comment" class="block text-sm font-medium text-gray-700 mb-2">评论内容 <span class="required text-red-500">*</span></label>' .
                              '<textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required="required" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical" placeholder="请输入您的评论..."></textarea>' .
                              '</div>',
            'must_log_in' => '<p class="must-log-in bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">' .
                            sprintf(
                                '您必须 <a href="%s" class="text-blue-600 hover:text-blue-800">登录</a> 才能发表评论。',
                                wp_login_url(apply_filters('the_permalink', get_permalink()))
                            ) . '</p>',
            'logged_in_as' => '<p class="logged-in-as bg-green-50 border border-green-200 rounded-md p-4 mb-4">' .
                             sprintf(
                                 '以 <a href="%1$s" class="text-blue-600 hover:text-blue-800">%2$s</a> 身份登录。 <a href="%3$s" title="退出此账户" class="text-red-600 hover:text-red-800">退出？</a>',
                                 get_edit_user_link(),
                                 wp_get_current_user()->display_name,
                                 wp_logout_url(apply_filters('the_permalink', get_permalink()))
                             ) . '</p>',
            'comment_notes_before' => '<p class="comment-notes text-sm text-gray-600 mb-4">' .
                                     '您的电子邮箱地址不会被公开。 必填项已用 <span class="required text-red-500">*</span> 标注' .
                                     '</p>',
            'comment_notes_after' => '',
            'class_form' => 'comment-form bg-gray-50 rounded-lg p-6 mt-8',
            'class_submit' => 'submit bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
        );
        
        comment_form($comment_form_args);
    endif;
    ?>
</div>

<style>
/* 评论区域额外样式 */
.comment-list {
    list-style: none;
    padding: 0;
}

.comment-body {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.comment-author img {
    border-radius: 50%;
}

.comment-meta {
    font-size: 0.875rem;
    color: #6b7280;
}

.comment-content {
    margin-top: 0.75rem;
    line-height: 1.6;
}

.reply {
    margin-top: 0.75rem;
}

.reply a {
    color: #3b82f6;
    text-decoration: none;
    font-size: 0.875rem;
}

.reply a:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

.children {
    margin-left: 2rem;
    margin-top: 1rem;
}

.comment-form-author,
.comment-form-email,
.comment-form-url {
    margin-bottom: 1rem;
}

.comment-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.comment-form input[type="text"],
.comment-form input[type="email"],
.comment-form input[type="url"] {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.comment-form input[type="text"]:focus,
.comment-form input[type="email"]:focus,
.comment-form input[type="url"]:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-submit {
    margin-top: 1rem;
}

@media (max-width: 640px) {
    .children {
        margin-left: 1rem;
    }
    
    .comments-area {
        padding: 1rem;
    }
}
</style>