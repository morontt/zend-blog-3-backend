import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'tr',
    isEditing: false,
    actions: {
        openTarget() {
            window.open(Routing.generate('post_preview', {slug: this.get('mediaFile.post.url')}), '_blank');
        },
        edit: function () {
            this.set('isEditing', true);
        },
        save: function () {
            this.get('mediaFile').save().then(() => {
                this.set('isEditing', false);
            });
        },
        reset: function () {
            this.get('mediaFile').rollbackAttributes();
            this.set('isEditing', false);
        },
        remove: function () {
            var modal = $('#confirmation-modal');

            modal.attr('data-object-id', this.get('mediaFile.id'));
            modal.find('#confirmation-object-name').html(this.get('mediaFile.originalFilename'));
            modal.modal('show');
        }
    }
});
