/**
 * Created by morontt.
 * Date: 22.11.14
 * Time: 18:06
 */

MttBlog.TagController = Ember.Controller.extend({});

MttBlog.TagIndexController = Ember.ArrayController.extend({
    queryParams: ['page'],
    page: 1
});
