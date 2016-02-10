import DS from 'ember-data';
import Ember from 'ember';

export default DS.RESTAdapter.extend({
    namespace: app_parameters.api_url,
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
