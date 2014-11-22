/**
 * Created by morontt.
 * Date: 22.11.14
 * Time: 18:10
 */

MttBlog.TagRoute = Ember.Route.extend({
    model: function () {
        return this.store.find('tag');
    }
});

MttBlog.TagIndexRoute = Ember.Route.extend({
    model: function () {
        return this.modelFor('tag');
    }
});
