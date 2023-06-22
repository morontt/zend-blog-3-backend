import Ember from 'ember';
import config from 'mtt-blog/config/environment';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    alertClass: 'hide',
    alertMessage: '',
    actions: {
        purgeCache() {
            console.log(config);
            fetch(Routing.generate('purge_cache'), {
                method: 'POST',
            }).then(
                (resp) => {
                    console.log(resp);

                    if (resp.ok) {
                        this.set('alertMessage', 'Кеш очищен');
                        this.set('alertClass', 'alert-success');
                    } else {
                        this.set('alertMessage', 'Ошибка очистки кеша');
                        this.set('alertClass', 'alert-danger');
                    }

                    setTimeout(() => {
                        this.set('alertMessage', '');
                        this.set('alertClass', 'hide');
                    }, 4000);
                }
            );
        }
    }
});
