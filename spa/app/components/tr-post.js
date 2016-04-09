import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'tr',
    actions: {
        openTarget() {
            window.open(Routing.generate('post_preview', {slug: this.get('post.url')}), '_blank');
        },
        remove: function () {
            var modal = $('#confirmation-modal');

            modal.attr('data-object-id', this.get('post.id'));
            modal.find('#confirmation-object-name').html(this.get('post.title'));
            modal.modal('show');
        }
    }
});
