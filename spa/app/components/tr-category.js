import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'tr',
    isEditing: false,
    actions: {
        edit() {
            this.set('isEditing', true);
        },
        save() {
            this.get('category').save().then(() => {
                this.set('isEditing', false);
            });
        },
        reset() {
            this.get('category').rollbackAttributes();
            this.set('isEditing', false);
        },
        remove() {
            var modal = $('#confirmation-modal');

            modal.attr('data-object-id', this.get('category.id'));
            modal.find('#confirmation-object-name').html(this.get('category.name'));
            modal.modal('show');
        }
    }
});
