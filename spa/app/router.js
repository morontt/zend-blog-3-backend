import Ember from 'ember';
import config from './config/environment';

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
    this.route('pygmentsLanguages');
    this.route('pygmentsCode');
});

export default Router;
