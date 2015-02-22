/**
 * Created by morontt.
 * Date: 15.02.15
 * Time: 18:46
 */

MttBlog.PostRoute = Ember.Route.extend({});

MttBlog.PostIndexRoute = Ember.Route.extend({
    queryParams: {
        page: {
            refreshModel: true
        }
    },
    model: function (params) {
        return this.store.find('post', params);
    }
});
