import Ember from 'ember';

export default Ember.Route.extend({
    model(params) {
        return this.store.findRecord('pygmentsCode', params.code_id);
    },
    setupController(controller, model) {
        this._super(controller, model);

        var appController = controller.get('appController');
        appController.set('currentLink', 'code');

        Ember.$.ajax({
            url: Routing.generate('language_choices'),
            success(data) {
                var choices = data.reduce(
                    function (prev, curr) {
                        prev.push({value: curr.id, label: curr.name});
                        return prev;
                    },
                    [{value: '', label: ''}]
                );

                controller.set('languageChoices', choices);
            }
        });
    }
});
