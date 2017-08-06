import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    queryParams: ['page'],
    page: 1,
    actions: {
        removeComment() {
            var comment_id = $('#confirmation-modal').attr('data-object-id');
            this.store.peekRecord('comment', comment_id).destroyRecord();
        }
    }
});
