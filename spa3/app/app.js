import Application from '@ember/application';
import Resolver from 'ember-resolver';
import loadInitializers from 'ember-load-initializers';
import config from 'mtt-blog/config/environment';

let App = Application.extend({
    modulePrefix: config.modulePrefix,
    podModulePrefix: config.podModulePrefix,
    Resolver: Resolver,
    rootElement: '#main-application',
});

loadInitializers(App, config.modulePrefix);

export default App;
