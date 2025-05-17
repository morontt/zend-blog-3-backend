import Ember from 'ember';

export default Ember.Component.extend({
    hasErrors: false,
    errors: [],
    actions: {
        closeModal() {
            $('#modal_avatar').modal('hide');

            this.set('hasErrors', false);
            this.set('errors', []);
        },
        sendFile() {
            var them = this;
            var request = new XMLHttpRequest();

            request.open('POST', Routing.generate('upload_avatar'));
            request.send(new FormData(document.getElementById('avatar-form')));

            request.onreadystatechange = function () {
                if (request.readyState === 4) {
                    if (request.status !== 201) {
                        them.set('hasErrors', true);
                        var error = $.parseJSON(request.responseText);
                        them.set('errors', error.errors);
                    } else {
                        them.set('hasErrors', false);
                        them.set('errors', []);

                        them.$('#upload-image').val('');
                        them.$('#commentator_id').val('');

                        $('#modal_avatar').modal('hide');

                        them.sendAction();
                    }
                }
            };
        }
    }
});
