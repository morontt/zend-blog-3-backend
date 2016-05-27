import Ember from 'ember';

export default Ember.Component.extend({
    hasErrors: false,
    errors: [],
    actions: {
        closeModal() {
            $('#modal_new_image').modal('hide');
        },
        sendFile() {
            var them = this;
            var request = new XMLHttpRequest();

            request.open('POST', Routing.generate('upload_image'));
            request.send(new FormData(document.getElementById('image-form')));

            request.onreadystatechange = function () {
                if (request.readyState === 4) {
                    if (request.status !== 201) {
                        them.set('hasErrors', true);
                        var error = $.parseJSON(request.responseText);
                        them.set('errors', error.errors);
                    } else {
                        them.set('hasErrors', false);
                        them.set('errors', []);

                        them.$('#description-image').val('');
                        them.$('#upload-image').val('');
                        them.$('#post-image').val('');

                        $('#modal_new_image').modal('hide');
                        them.sendAction();
                    }
                }
            };
        }
    }
});
