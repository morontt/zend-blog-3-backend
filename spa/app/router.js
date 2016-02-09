import Ember from 'ember';
import config from './config/environment';

const Router = Ember.Router.extend({
    location: config.locationType
});

Router.map(function () {
    this.route('dashboard', { path: '/' });
    this.route('posts');
    this.route('tags');
    this.route('category');
    this.route('comments');
    this.route('commentators');
});

export default Router;
