import Ember from 'ember';

export default Ember.Route.extend({
    model(params) {
        return this.store.query('mediaFile', params);
    },
    setupController(controller, model) {
        this._super(controller, model);

        controller.set('postId', this.paramsFor('postimages').post_id);

        var appController = controller.get('appController');
        appController.set('currentLink', 'posts');
    }
});
