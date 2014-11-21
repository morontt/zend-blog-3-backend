/**
 * Created by morontt on 16.11.14.
 */

window.MttBlog = Ember.Application.create({
    rootElement: '#main-application'
    , LOG_TRANSITIONS: true
    , LOG_VIEW_LOOKUPS: true
});

MttBlog.ApplicationAdapter = DS.ActiveModelAdapter.extend({
    namespace: app_parameters.api_url
});
