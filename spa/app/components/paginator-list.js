import Ember from 'ember';

export default Ember.Component.extend({
    pageLinks: function () {
        var meta = this.get('meta');
        var i, links = [];

        for (i = 1; i <= meta.last; i++) {
            links.push({
                page: i,
                active: (meta.current === i)
            });
        }

        return links;
    }.property('meta')
});
