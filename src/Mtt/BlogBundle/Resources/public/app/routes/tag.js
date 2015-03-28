/**
 * Created by morontt.
 * Date: 22.11.14
 * Time: 18:10
 */

MttBlog.TagRoute = Ember.Route.extend({});

MttBlog.TagIndexRoute = Ember.Route.extend({
    queryParams: {
        page: {
            refreshModel: true
        }
    },
    model: function (params) {
        return this.store.find('tag', params);
    }
});
