import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'tr',
    isEditing: false,
    actions: {
        editCategory() {
            this.set('isEditing', true);
        },
        saveCategory() {
            this.get('category').save().then(() => {
                this.set('isEditing', false);
            });
        },
        resetChanges() {
            this.get('category').rollbackAttributes();
            this.set('isEditing', false);
        },
        removeCategory() {
            var modal = $('#confirmation-modal');

            modal.attr('data-object-id', this.get('category.id'));
            modal.find('#confirmation-object-name').html(this.get('category.name'));
            modal.modal('show');
        }
    }
});
