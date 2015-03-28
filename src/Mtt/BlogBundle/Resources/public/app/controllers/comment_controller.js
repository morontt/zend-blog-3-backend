/**
 * Created by morontt.
 * Date: 29.11.14
 * Time: 12:41
 */

MttBlog.CommentController = Ember.Controller.extend({});

MttBlog.CommentIndexController = Ember.ArrayController.extend({
    queryParams: ['page'],
    page: 1
});
