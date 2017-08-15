import Ember from 'ember';

export default Ember.Route.extend({
    model(params) {
        return Ember.RSVP.hash({
            comment: this.store.findRecord('comment', params.comment_id),
            newComment: this.store.createRecord('comment')
        });
    },
    setupController(controller, model) {
        this._super(controller, model);

        var appController = controller.get('appController');
        appController.set('currentLink', 'comments');
    }
});
