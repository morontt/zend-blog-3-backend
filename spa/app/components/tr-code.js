import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'tr',
    actions: {
        remove: function () {
            var modal = $('#confirmation-modal');

            modal.attr('data-object-id', this.get('code.id'));
            modal.find('#confirmation-object-name').html(this.get('code.id'));
            modal.modal('show');
        }
    }
});
