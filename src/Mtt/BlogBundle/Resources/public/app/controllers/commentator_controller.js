/**
 * Created by morontt.
 * Date: 23.11.14
 * Time: 12:09
 */

MttBlog.CommentatorController = Ember.Controller.extend({});

MttBlog.CommentatorIndexController = Ember.ArrayController.extend({
    queryParams: ['page'],
    page: 1
});
