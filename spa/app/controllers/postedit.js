import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    alertClass: 'hide',
    preview: null,
    actions: {
        back() {
            this.transitionToRoute('posts');
        },
        afterSave() {
            this.set('alertClass', '');
            setTimeout(() => {
                this.set('alertClass', 'hide');
            }, 4000);

            var preview_window = this.get('preview');
            if (preview_window) {
                preview_window.location.reload(true);
            } else {
                preview_window = window.open(
                    Routing.generate('post_preview', {slug: this.get('model.url')}),
                    '_blank'
                );
                this.set('preview', preview_window);
            }
        }
    },
    categoryChoices: []
});
