import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    actions: {
        back() {
            this.transitionToRoute('posts');
        },
        redirect() {}
    },
    categoryChoices: []
});
