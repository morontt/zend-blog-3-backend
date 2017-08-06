import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'tr',
    isEditing: false,
    actions: {
        edit: function () {
            this.set('isEditing', true);
        },
        save: function () {
            this.get('comment').save().then(() => {
                this.set('isEditing', false);
            });
        },
        reset: function () {
            this.get('comment').rollbackAttributes();
            this.set('isEditing', false);
        },
        remove: function () {
            var modal = $('#confirmation-modal');

            modal.attr('data-object-id', this.get('comment.id'));
            modal.find('#confirmation-object-name').html(this.get('comment.id'));
            modal.modal('show');
        }
    }
});
