/**
 * Created by morontt.
 * Date: 22.11.14
 * Time: 1:43
 */

MttBlog.CategoryController = Ember.Controller.extend({});

MttBlog.CategoryIndexController = Ember.ArrayController.extend({
    queryParams: ['page'],
    page: 1
});
