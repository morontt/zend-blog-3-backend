import Ember from 'ember';
import config from 'mtt-blog/config/environment';

export default Ember.Route.extend({
    setupController(controller, model) {
        this._super(controller, model);

        var appController = controller.get('appController');
        appController.set('currentLink', 'dashboard');

        var to_time_string = function (ts) {
            if (ts === 0) {
                return '-';
            }

            var m = moment.unix(ts);
            var result = '-';

            if (m.isValid()) {
                result = m.format('DD.MM.YYYY, HH:mm:ss Z');
            }

            return result;
        };

        controller.set('buildTimePhp', to_time_string(config.appParameters.build_info.build_time_php));
        controller.set('buildTimeJs', to_time_string(config.appParameters.build_info.build_time_js));
    }
});
