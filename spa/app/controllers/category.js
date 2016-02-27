import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    queryParams: ['page'],
    page: 1,
    enableNewCategoryButton: true,
    newCategoryName: '',
    newCategoryParent: '',
    actions: {
        createCategory() {
            if (this.get('enableNewCategoryButton')) {
                this.set('enableNewCategoryButton', false);

                var category = this.store.createRecord('category', {
                    name: this.get('newCategoryName'),
                    parentId: this.get('newCategoryParent')
                });

                category.save().then(
                    () => {
                        this.set('enableNewCategoryButton', true);
                        this.set('newCategoryName', '');
                        this.set('newCategoryParent', '');

                        this.send('closeModal');
                        this.get('target.router').refresh();
                    },
                    () => {
                        this.set('enableNewCategoryButton', true);
                    }
                );
            }
        },
        removeCategory() {
            var category_id = $('#confirmation-modal').attr('data-object-id');
            this.store.peekRecord('category', category_id).destroyRecord();
        },
        openModal() {
            $('#modal_new_category').modal();
        },
        closeModal() {
            $('#modal_new_category').modal('hide');
        }
    },
    parentChoices: []
});
