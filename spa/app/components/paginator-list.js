import Ember from 'ember';

export default Ember.Component.extend({
    hasLinks: function () {
        let meta = this.get('meta');

        return meta.last && meta.last > 1;
    }.property('meta'),
    pageLinks: function () {
        let meta = this.get('meta');
        let i, links = [];

        if (meta.last) {
            for (i = 1; i <= meta.last; i++) {
                links.push({
                    page: i,
                    active: (meta.current === i)
                });
            }
        }

        return links;
    }.property('meta')
});
