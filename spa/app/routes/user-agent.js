import Ember from 'ember';

export default Ember.Route.extend({
    queryParams: {
        page: {
            refreshModel: true
        }
    },
    model(params) {
        return this.store.query('userAgent', params);
    },
    setupController(controller, model) {
        this._super(controller, model);

        let appController = controller.get('appController');
        appController.set('currentLink', 'userAgents');
    }
});
