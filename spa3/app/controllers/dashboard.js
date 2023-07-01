import Controller from '@ember/controller';
import { action } from '@ember/object';

export default class DashboardController extends Controller {
    alertClass = 'hide'
    alertMessage = ''

    @action
    purgeCache() {
        fetch(Routing.generate('purge_cache'), {
            method: 'POST',
        }).then(
            (resp) => {
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
