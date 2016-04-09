import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    queryParams: ['page'],
    page: 1,
    enableNewTagButton: true,
    newTagName: '',
    actions: {
        createTag() {
            if (this.get('enableNewTagButton')) {
                this.set('enableNewTagButton', false);

                var tag = this.store.createRecord('tag', {
                    name: this.get('newTagName')
                });

                tag.save().then(
                    () => {
                        this.set('enableNewTagButton', true);
                        this.set('newTagName', '');

                        this.send('closeModal');
                        this.get('target.router').refresh();
                    },
                    () => {
                        this.set('enableNewTagButton', true);
                    }
                );
            }
        },
        removeTag() {
            var tag_id = $('#confirmation-modal').attr('data-object-id');
            this.store.peekRecord('tag', tag_id).destroyRecord();
        },
        openModal() {
            $('#modal_new_tag').modal();
        },
        closeModal() {
            $('#modal_new_tag').modal('hide');
        }
    }
});
