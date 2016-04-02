import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'tr',
    isEditing: false,
    actions: {
        edit: function () {
            this.set('isEditing', true);
        },
        save: function () {
            this.get('commentator').save().then(() => {
                this.set('isEditing', false);
            });
        },
        reset: function () {
            this.get('commentator').rollbackAttributes();
            this.set('isEditing', false);
        }
    }
});
