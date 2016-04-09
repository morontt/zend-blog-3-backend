import Ember from 'ember';

export default Ember.Component.extend({
    didInsertElement() {
        this.$('.table').on('change', 'input[type="checkbox"]', function () {
            var el = $(this);
            if (el.prop('checked')) {
                var id = el.attr('id');
                $('.table input:checked').not('#' + id).each(function (idx, ch) {
                    $(ch).trigger('click');
                });
            }
        });
    },
    actions: {
        save() {
            this.get('model').forEach(function (el) {
                if (el.get('hasDirtyAttributes')) {
                    el.save();
                }
            });
        },
        reset() {
            this.get('model').forEach(function (el) {
                if (el.get('hasDirtyAttributes')) {
                    el.rollbackAttributes();
                }
            });
        }
    }
});
