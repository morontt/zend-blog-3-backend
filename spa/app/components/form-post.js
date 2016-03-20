import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'form',
    classNames: ['form-horizontal'],
    actions: {
        save() {
            this.get('model').save().then(() => {
                this.sendAction('redirect');
            });
        },
        reset() {
            this.get('model').rollbackAttributes();
            this.sendAction('back');
        }
    }
});
