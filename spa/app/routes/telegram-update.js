import Ember from 'ember';

export default Ember.Route.extend({
    queryParams: {
        page: {
            refreshModel: true
        }
    },
    model(params) {
        return this.store.query('telegramUpdate', params);
    },
    setupController(controller, model) {
        this._super(controller, model);

        let appController = controller.get('appController');
        appController.set('currentLink', 'telegramUpdates');
    }
});
