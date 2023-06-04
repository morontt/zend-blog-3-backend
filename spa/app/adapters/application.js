import DS from 'ember-data';
import Ember from 'ember';
import config from 'mtt-blog/config/environment';

export default DS.RESTAdapter.extend({
    namespace: config.appParameters.apiURL,
    ajaxError: function(jqXHR) {
        var error = this._super(jqXHR);

        if (jqXHR && jqXHR.status === 422) {
            var jsonErrors = Ember.$.parseJSON(jqXHR.responseText);
            return new DS.InvalidError(jsonErrors);
        } else {
            return error;
        }
    }
});
