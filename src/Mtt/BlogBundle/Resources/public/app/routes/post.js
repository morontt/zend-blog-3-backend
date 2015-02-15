/**
 * Created by morontt.
 * Date: 15.02.15
 * Time: 18:46
 */

MttBlog.PostRoute = Ember.Route.extend({
    model: function () {
        return this.store.find('post');
    }
});

MttBlog.PostIndexRoute = Ember.Route.extend({
    model: function () {
        return this.modelFor('post');
    }
});
