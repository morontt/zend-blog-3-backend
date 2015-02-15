/**
 * Created by morontt.
 * Date: 15.02.15
 * Time: 20:19
 */

Ember.Handlebars.registerBoundHelper('dateFormat', function(date, format) {
    var m = moment(date);
    var result = '';

    if (m.isValid()) {
        result = m.format(format);
    }

    return result;
});
