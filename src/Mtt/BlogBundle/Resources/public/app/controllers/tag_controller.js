/**
 * Created by morontt.
 * Date: 22.11.14
 * Time: 18:06
 */

MttBlog.TagController = Ember.Controller.extend({});

MttBlog.TagIndexController = Ember.ArrayController.extend({
    queryParams: ['page'],
    page: 1,
    actions: {
        createTag: function () {
            if (this.get('enableNewTagButton')) {
                var them = this;
                var onSuccess = function () {
                    them.set('enableNewTagButton', true);
                    $('#modal_new_tag').modal('hide');
                };
                var onFail = function () {
                    them.set('enableNewTagButton', true);
                };

                this.set('enableNewTagButton', false);

                var tag = this.store.createRecord('tag', {
                    name: $('#tag_field').val()
                });

                tag.save().then(onSuccess, onFail);
            }
        },
        openModal: function () {
            $('#modal_new_tag').modal();
        },
        closeModal: function () {
            $('#modal_new_tag').modal('hide');
        }
    },
    enableNewTagButton: true
});
