import Ember from 'ember';

export default Ember.Component.extend({
    actions: {
        confirm() {
            this.$('#confirmation-modal').modal('hide');
            this.sendAction();
        }
    }
});
