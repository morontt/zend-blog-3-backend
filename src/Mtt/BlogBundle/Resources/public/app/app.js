/**
 * Created by morontt on 16.11.14.
 */

var application_options = {
    rootElement: '#main-application'
};

if (app_parameters.environment === 'dev') {
    application_options.LOG_TRANSITIONS = true;
    application_options.LOG_VIEW_LOOKUPS = true;
}

window.MttBlog = Ember.Application.create(application_options);

MttBlog.ApplicationAdapter = DS.ActiveModelAdapter.extend({
    namespace: app_parameters.api_url
});
