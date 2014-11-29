/**
 * Created by morontt.
 * Date: 29.11.14
 * Time: 12:39
 */

MttBlog.CommentRoute = Ember.Route.extend({
    model: function () {
        return this.store.find('comment');
    }
});

MttBlog.CommentIndexRoute = Ember.Route.extend({
    model: function () {
        return this.modelFor('comment');
    }
});
