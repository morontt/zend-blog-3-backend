/**
 * Created by morontt.
 * Date: 16.11.14
 * Time: 18:11
 */

MttBlog.DashboardRoute = Ember.Route.extend({
    model: function () {
        return [];
    }
});

MttBlog.DashboardIndexRoute = Ember.Route.extend({
    model: function () {
        return this.modelFor('dashboard');
    }
});
