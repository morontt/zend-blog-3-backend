/**
 * Created by morontt.
 * Date: 22.11.14
 * Time: 1:43
 */

MttBlog.CategoryController = Ember.Controller.extend({});

MttBlog.CategoryIndexController = Ember.ArrayController.extend({
    queryParams: ['page'],
    page: 1,
    actions: {
        createCategory: function () {
            if (this.get('enableNewCategoryButton')) {
                var them = this;
                var category_field = $('#category_field');
                var category_parent = $('#category_parent');
                var onSuccess = function (record) {
                    them.set('enableNewCategoryButton', true);
                    them.get('model').addObject(record);

                    $('#modal_new_category').modal('hide');
                    category_field.val('');
                    category_parent.val('');
                };
                var onFail = function () {
                    them.set('enableNewCategoryButton', true);
                };

                this.set('enableNewCategoryButton', false);

                var category = this.store.createRecord('category', {
                    name: category_field.val()
                });

                category.save().then(onSuccess, onFail);
            }
        },
        openModal: function () {
            $('#modal_new_category').modal();
        },
        closeModal: function () {
            $('#modal_new_category').modal('hide');
        }
    },
    sortableCategory: function () {
        return Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
            sortProperties: ['name'],
            content: this.get('model')
        });
    }.property('model'),
    enableNewCategoryButton: true
});
