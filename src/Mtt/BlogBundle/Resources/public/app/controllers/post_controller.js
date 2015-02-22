/**
 * Created by morontt.
 * Date: 15.02.15
 * Time: 18:47
 */

MttBlog.PostController = Ember.Controller.extend({});

MttBlog.PostIndexController = Ember.ArrayController.extend({
    queryParams: ['page'],
    page: 1
});
