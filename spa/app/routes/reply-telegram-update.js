import Ember from 'ember';

export default Ember.Route.extend({
    model(params) {
        return Ember.RSVP.hash({
            update: this.store.findRecord('telegramUpdate', params.update_id),
            newUpdate: this.store.createRecord('telegramUpdate')
        });
    },
    setupController(controller, model) {
        this._super(controller, model);

        var appController = controller.get('appController');
        appController.set('currentLink', 'telegramUpdates');
    }
});
