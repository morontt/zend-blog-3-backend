import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    actions: {
        back() {
            this.transitionToRoute('posts');
        },
        afterSave() {
            var model = this.get('model');
            if (model.get('id')) {
                this.transitionToRoute('postedit', model);
            }
        }
    },
    categoryChoices: []
});
