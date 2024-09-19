<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 21:17
 */

namespace App;

final class Events
{
    public const REPLY_COMMENT = 'mtt_blog.reply_comment';
    public const DELETE_COMMENT = 'mtt_blog.delete_comment';
    public const CODE_SNIPPET_UPDATED = 'mtt_blog.code_updated';
    public const EXTERNAL_USER_CREATED = 'mtt_user.external_user.created';
    public const USER_UPDATED = 'mtt_user.user_updated';
}
