/**
 * Created by morontt.
 * Date: 23.11.14
 * Time: 12:10
 */

MttBlog.CommentatorRoute = Ember.Route.extend({});

MttBlog.CommentatorIndexRoute = Ember.Route.extend({
    queryParams: {
        page: {
            refreshModel: true
        }
    },
    model: function (params) {
        return this.store.find('commentator', params);
    }
});
