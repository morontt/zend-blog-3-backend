import Ember from 'ember';

export default Ember.Route.extend({
    model(params) {
        return this.store.findRecord('post', params.post_id);
    },
    setupController(controller, model) {
        this._super(controller, model);

        controller.set('preview', false);

        var appController = controller.get('appController');
        appController.set('currentLink', 'posts');

        Ember.$.ajax({
            url: Routing.generate('category_choices'),
            success(data) {
                var choices = data.reduce(
                    function (prev, curr) {
                        prev.push({value: curr.id, label: curr.name});
                        return prev;
                    },
                    [{value: '', label: ''}]
                );

                controller.set('categoryChoices', choices);
            }
        });
    }
});
