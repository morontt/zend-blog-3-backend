import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    alertClass: 'hide',
    alertMessage: '',
    actions: {
        reply() {
            let update = this.get('model.newUpdate');

            update.set('replyId', this.get('model.update.id'));
            update.save().then(() => {
                this.set('alertMessage', 'Сообщение отправлено :)');
                this.set('alertClass', 'alert-success');

                setTimeout(() => {
                    this.set('alertMessage', '');
                    this.set('alertClass', 'hide');

                    this.transitionToRoute('telegramUpdate');
                }, 4000);
            }, () => {
                this.set('alertMessage', 'Ошибка :(');
                this.set('alertClass', 'alert-danger');

                setTimeout(() => {
                    this.set('alertMessage', '');
                    this.set('alertClass', 'hide');
                }, 4000);
            });
        }
    }
});
