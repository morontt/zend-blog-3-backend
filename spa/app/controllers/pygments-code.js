import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    queryParams: ['page'],
    page: 1,
    actions: {
        removeCode() {
            let id = $('#confirmation-modal').attr('data-object-id');
            this.store.peekRecord('pygmentsCode', id).destroyRecord();
        }
    }
});
