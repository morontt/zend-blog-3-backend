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
                var tag_field = $('#tag_field');
                var onSuccess = function (record) {
                    them.set('enableNewTagButton', true);
                    them.get('model').addObject(record);

                    $('#modal_new_tag').modal('hide');
                    tag_field.val('');
                };
                var onFail = function () {
                    them.set('enableNewTagButton', true);
                };

                this.set('enableNewTagButton', false);

                var tag = this.store.createRecord('tag', {
                    name: tag_field.val()
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
    sortableTags: function () {
        return Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
            sortProperties: ['name'],
            content: this.get('model')
        });
    }.property('model'),
    enableNewTagButton: true
});
