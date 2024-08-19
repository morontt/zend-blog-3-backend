import Ember from 'ember';

export default Ember.Component.extend({
    tagName: 'tr',
    isEditing: false,
    userRoles: [
        {value: 'admin', label: 'admin'},
        {value: 'guest', label: 'guest'},
    ],
    actions: {
        edit: function () {
            this.set('isEditing', true);
        },
        save: function () {
            this.get('user').save().then(() => {
                this.set('isEditing', false);
            });
        },
        reset: function () {
            this.get('user').rollbackAttributes();
            this.set('isEditing', false);
        }
    }
});
