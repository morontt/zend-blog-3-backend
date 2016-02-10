import Ember from 'ember';

export default Ember.Route.extend({
    setupController(controller, model) {
        this._super(controller, model);

        var appController = controller.get('appController');
        appController.set('currentLink', 'dashboard');
    }
});
