import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    queryParams: ['page'],
    page: 1,
    actions: {
        refresh() {
            this.get('target.router').refresh();
        }
    }
});
