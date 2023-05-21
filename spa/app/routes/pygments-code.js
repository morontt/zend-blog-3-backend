import Ember from 'ember';

export default Ember.Route.extend({
    queryParams: {
        page: {
            refreshModel: true
        }
    },
    model(params) {
        return this.store.query('pygmentsCode', params);
    },
    setupController(controller, model) {
        this._super(controller, model);

        var appController = controller.get('appController');
        appController.set('currentLink', 'code');
    }
});
