/**
 * Created by morontt
 * on 04.07.15.
 */

MttBlog.TrTagComponent = Ember.Component.extend({
    tagName: 'tr',
    isEditing: false,
    actions: {
        editTag: function () {
            this.set('isEditing', true);
        },
        saveTag: function () {
            var them = this;
            this.get('tag').save().then(function () {
                them.set('isEditing', false);
            });
        },
        resetChanges: function () {
            this.get('tag').rollback();
            this.set('isEditing', false);
        },
        removeTag: function () {
            $('#confirmation-modal').modal('show');
        }
    }
});
