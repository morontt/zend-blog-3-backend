import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    alertClass: 'hide',
    actions: {
        back() {
            this.transitionToRoute('pygmentsCode');
        },
        afterSave() {
            this.set('alertClass', '');
            setTimeout(() => {
                this.set('alertClass', 'hide');
            }, 4000);
        }
    },
    languageChoices: []
});
