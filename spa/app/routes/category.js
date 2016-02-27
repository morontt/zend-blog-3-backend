import Ember from 'ember';

export default Ember.Route.extend({
    queryParams: {
        page: {
            refreshModel: true
        }
    },
    model(params) {
        return this.store.query('category', params);
    },
    setupController(controller, model) {
        this._super(controller, model);

        var appController = controller.get('appController');
        appController.set('currentLink', 'category');

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

                controller.set('parentChoices', choices);
            }
        });
    }
});
