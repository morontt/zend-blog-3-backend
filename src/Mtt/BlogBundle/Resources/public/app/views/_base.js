/**
 * Created by morontt.
 * Date: 15.08.15
 * Time: 21:44
 */

MttBlog.BaseView = Ember.View.extend({
    confirmDelete: function () {
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
