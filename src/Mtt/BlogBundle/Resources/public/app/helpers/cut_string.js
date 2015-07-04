/**
 * Created by morontt
 * on 04.07.15.
 */

Ember.Handlebars.registerBoundHelper('cutString', function(str, length) {
    var result = str;

    if (str.length > length) {
        result = str.substring(0, length) + '...';
    }

    return result;
});
