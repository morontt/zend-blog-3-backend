import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    queryParams: ['page'],
    page: 1,
    actions: {
        openModal() {
            $('#modal_new_image').modal();
        },
        remove() {
            var image_id = $('#confirmation-modal').attr('data-object-id');
            this.store.peekRecord('mediaFile', image_id).destroyRecord();
        },
        refresh() {
            this.get('target.router').refresh();
        }
    }
});
