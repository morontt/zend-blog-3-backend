/**
 * Created by morontt.
 * Date: 15.08.15
 * Time: 16:00
 */

MttBlog.TagIndexView = Ember.View.extend({
    didInsertElement: function () {
        this._super();

        var them = this;

        $('#confirmation-modal #confirmation-modal-confirm').on('click', function () {
            var modal = $('#confirmation-modal');
            var id = modal.attr('data-object-id');

            var models = them.get('controller.model.content');
            models.forEach(function (el) {
                if (el.get('id') == id) {
                    el.destroyRecord().then(function () {
                        modal.modal('hide');
                    });
                }
            });
        });
    }
});
