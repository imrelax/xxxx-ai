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

<div id="comments" class="comments-area bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mt-12">
    <?php if (have_comments()) : ?>
        <div class="comments-header mb-8">
            <h3 class="comments-title text-2xl font-bold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-comments text-blue-600 mr-3"></i>
                <?php
                $comments_number = get_comments_number();
                if ($comments_number == 1) {
                    echo '1 条评论';
                } else {
                    printf('%s 条评论', number_format_i18n($comments_number));
                }
                ?>
            </h3>
            <div class="title-decoration-line mb-6"></div>
        </div>

        <ol class="comment-list space-y-8">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 60,
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
            'title_reply' => '<i class="fas fa-comments text-blue-600 mr-2"></i>发表评论',
            'title_reply_to' => '<i class="fas fa-reply text-blue-600 mr-2"></i>回复 %s',
            'title_reply_before' => '<div class="reply-title-container bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-8 border border-blue-200 shadow-sm"><h3 id="reply-title" class="comment-reply-title text-2xl font-bold text-gray-900 flex items-center">',
            'title_reply_after' => '</h3><div class="mt-2 text-sm text-gray-600 flex items-center"><i class="fas fa-info-circle mr-2"></i>请文明发言，理性讨论</div></div>',
            'cancel_reply_before' => ' <div class="cancel-reply-wrapper inline-flex items-center ml-4">',
            'cancel_reply_after' => '</div>',
            'cancel_reply_link' => '<i class="fas fa-times mr-1"></i>取消回复',
            'class_cancel_reply' => 'inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 hover:text-gray-900 text-xs font-medium rounded-lg transition-all duration-200 border border-gray-300 hover:border-gray-400',
            'label_submit' => '提交评论',
            'comment_field' => '<div class="comment-form-comment mb-6">' .
                              '<label for="comment" class="block text-base font-semibold text-gray-700 mb-3 flex items-center"><i class="fas fa-comment-dots text-blue-600 mr-2"></i>评论内容 <span class="required text-red-500 ml-1">*</span></label>' .
                              '<textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required="required" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical transition-all duration-200 hover:border-gray-300" placeholder="分享您的想法和见解..."></textarea>' .
                              '</div>',
            'must_log_in' => '<div class="must-log-in bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 rounded-lg p-6 mb-6 shadow-sm">' .
                            '<div class="flex items-center">' .
                            '<i class="fas fa-info-circle text-yellow-600 mr-3 text-lg"></i>' .
                            sprintf(
                                '<span class="text-gray-700">您需要 <a href="%s" class="text-blue-600 hover:text-blue-800 font-semibold underline decoration-2 underline-offset-2">登录</a> 后才能发表评论</span>',
                                wp_login_url(apply_filters('the_permalink', get_permalink()))
                            ) . '</div></div>',
            'logged_in_as' => '<div class="logged-in-as bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 rounded-lg p-6 mb-6 shadow-sm">' .
                             '<div class="flex items-center">' .
                             '<i class="fas fa-user-check text-green-600 mr-3 text-lg"></i>' .
                             sprintf(
                                 '<span class="text-gray-700">以 <a href="%1$s" class="text-blue-600 hover:text-blue-800 font-semibold">%2$s</a> 身份登录 · <a href="%3$s" title="退出此账户" class="text-red-600 hover:text-red-800 font-medium">退出</a></span>',
                                 get_edit_user_link(),
                                 wp_get_current_user()->display_name,
                                 wp_logout_url(apply_filters('the_permalink', get_permalink()))
                             ) . '</div></div>',
            'comment_notes_before' => '<div class="comment-notes bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">' .
                                     '<div class="flex items-start">' .
                                     '<i class="fas fa-shield-alt text-blue-600 mr-3 mt-0.5"></i>' .
                                     '<span class="text-sm text-gray-600">您的电子邮箱地址不会被公开。必填项已用 <span class="required text-red-500 font-semibold">*</span> 标注</span>' .
                                     '</div></div>',
            'comment_notes_after' => '',
            'fields' => array(
                'author' => '<div class="comment-form-author mb-6">' .
                           '<label for="author" class="block text-base font-semibold text-gray-700 mb-3 flex items-center"><i class="fas fa-user text-blue-600 mr-2"></i>姓名 <span class="required text-red-500 ml-1">*</span></label>' .
                           '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245" required="required" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300" placeholder="请输入您的姓名" />' .
                           '</div>',
                'email' => '<div class="comment-form-email mb-6">' .
                          '<label for="email" class="block text-base font-semibold text-gray-700 mb-3 flex items-center"><i class="fas fa-envelope text-blue-600 mr-2"></i>邮箱 <span class="required text-red-500 ml-1">*</span></label>' .
                          '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes" required="required" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300" placeholder="请输入您的邮箱地址" />' .
                          '</div>',
                'url' => '<div class="comment-form-url mb-6">' .
                        '<label for="url" class="block text-base font-semibold text-gray-700 mb-3 flex items-center"><i class="fas fa-globe text-blue-600 mr-2"></i>网站</label>' .
                        '<input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-300" placeholder="请输入您的网站地址（可选）" />' .
                        '</div>',
                'cookies' => '<div class="comment-form-cookies-consent flex items-start space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200 mb-6">' .
                           '<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2" /> ' .
                           '<label for="wp-comment-cookies-consent" class="text-sm text-gray-700 leading-relaxed flex-1">在此浏览器中保存我的显示名称、邮箱地址和网站地址，以便下次评论时使用。</label></div>'
            ),
            'class_form' => 'comment-form bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl border border-gray-200 p-8 mt-12 shadow-lg',
            'class_submit' => 'submit bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-200 ease-in-out focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-offset-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5'
        );
        
        comment_form($comment_form_args);
    endif;
    ?>
</div>