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
        }
    }
});
