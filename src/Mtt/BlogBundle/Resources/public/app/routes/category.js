/**
 * Created by morontt.
 * Date: 22.11.14
 * Time: 1:41
 */

MttBlog.CategoryRoute = Ember.Route.extend({});

MttBlog.CategoryIndexRoute = Ember.Route.extend({
    queryParams: {
        page: {
            refreshModel: true
        }
    },
    model: function (params) {
        return this.store.find('category', params);
    }
});
