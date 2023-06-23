import Ember from 'ember';
import config from 'mtt-blog/config/environment';

const Router = Ember.Router.extend({
    location: config.locationType
});

Router.map(function () {
    this.route('dashboard', {path: '/'});
    this.route('posts');
    this.route('postedit', {path: '/post/:post_id'});
    this.route('postimages', {path: '/post-images/:post_id'});
    this.route('postcreate');
    this.route('tags');
    this.route('category');
    this.route('comments');
    this.route('commentators');
    this.route('images');
    this.route('reply-comment', {path: '/reply-comment/:comment_id'});
    this.route('pygmentsLanguages', {path: '/syntaxes'});
    this.route('pygmentsCode', {path: '/codes'});
    this.route('pygmentsCodeCreate', {path: '/code-create'});
    this.route('pygmentsCodeEdit', {path: '/code-edit/:code_id'});
    this.route('userAgent', {path: '/user-agent'});
    this.route('tracking');
    this.route('telegramUser');
    this.route('telegramUpdate');
    this.route('replyTelegramUpdate', {path: '/reply-telegram/:update_id'});
});

export default Router;
