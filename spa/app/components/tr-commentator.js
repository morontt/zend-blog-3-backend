import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'tr',
    isEditing: false,
    actions: {
        edit: function () {
            this.set('isEditing', true);
        },
        save: function () {
            this.get('commentator').save().then(() => {
                this.set('isEditing', false);
            });
        },
        showUploader: function () {
            var modal = $('#modal_avatar');

            modal.find('#commentator_id').val(this.get('commentator.id'));
            modal.find('#object-name').html(this.get('commentator.name'));
            modal.modal('show');
        },
        reset: function () {
            this.get('commentator').rollbackAttributes();
            this.set('isEditing', false);
        }
    }
});
