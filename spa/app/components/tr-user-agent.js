import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'tr',
    isEditing: false,
    actions: {
        edit: function () {
            this.set('isEditing', true);
        },
        save: function () {
            this.get('agent').save().then(() => {
                this.set('isEditing', false);
            });
        },
        reset: function () {
            this.get('agent').rollbackAttributes();
            this.set('isEditing', false);
        }
    }
});
