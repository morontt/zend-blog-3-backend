/**
 * Created by morontt.
 * Date: 15.08.15
 * Time: 21:27
 */

MttBlog.TrCategoryComponent = Ember.Component.extend({
    tagName: 'tr',
    isEditing: false,
    actions: {
        editCategory: function () {
            this.set('isEditing', true);
        },
        saveCategory: function () {
            var them = this;
            this.get('category').save().then(function () {
                them.set('isEditing', false);
            });
        },
        resetChanges: function () {
            this.get('category').rollback();
            this.set('isEditing', false);
        },
        removeCategory: function () {
            var modal = $('#confirmation-modal');

            modal.attr('data-object-id', this.get('category.id'));
            modal.find('#confirmation-object-name').html(this.get('category.name'));
            modal.modal('show');
        }
    }
});
