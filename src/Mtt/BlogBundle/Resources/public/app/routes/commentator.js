/**
 * Created by morontt.
 * Date: 23.11.14
 * Time: 12:10
 */

MttBlog.CommentatorRoute = Ember.Route.extend({
    model: function () {
        return this.store.find('commentator');
    }
});

MttBlog.CommentatorIndexRoute = Ember.Route.extend({
    model: function () {
        return this.modelFor('commentator');
    }
});
