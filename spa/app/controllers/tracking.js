import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    queryParams: ['page'],
    page: 1,
    actions: {
        openTarget(track) {
            window.open(Routing.generate('post_preview', {slug: track.get('articleSlug')}), '_blank');
        },
    }
});
