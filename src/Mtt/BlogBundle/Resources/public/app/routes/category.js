/**
 * Created by morontt.
 * Date: 22.11.14
 * Time: 1:41
 */

MttBlog.CategoryRoute = Ember.Route.extend({
    model: function () {
        return [];
    }
});

MttBlog.CategoryIndexRoute = Ember.Route.extend({
    model: function () {
        return this.modelFor('category');
    }
});
