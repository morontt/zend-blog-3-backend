import Ember from 'ember';

export function dateFormat(params/*, hash*/) {
    var m = moment(params[0]);
    var result = '';

    if (m.isValid()) {
        result = m.format(params[1]);
    }

    return result;
}

export default Ember.Helper.helper(dateFormat);
