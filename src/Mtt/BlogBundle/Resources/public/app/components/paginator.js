/**
 * Created by morontt.
 * Date: 27.02.15
 * Time: 1:16
 */

MttBlog.PaginatorListComponent = Ember.Component.extend({
    pageLinks: function () {
        var meta = this.get('meta');
        var i, links = [];

        for (i = 1; i <= meta.last; i++) {
            links.push({
                page: i,
                active: (meta.current == i)
            });
        }

        return links;
    }.property('meta')
});
