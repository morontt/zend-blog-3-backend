/**
 * Created by morontt.
 * Date: 29.11.14
 * Time: 12:39
 */

MttBlog.CommentRoute = Ember.Route.extend({});

MttBlog.CommentIndexRoute = Ember.Route.extend({
    queryParams: {
        page: {
            refreshModel: true
        }
    },
    model: function (params) {
        return this.store.find('comment', params);
    }
});
