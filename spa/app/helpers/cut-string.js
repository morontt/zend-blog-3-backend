import Ember from 'ember';

export function cutString([str, length]) {
    var result = str;

    if (str.length > length) {
        result = str.substring(0, length) + '...';
    }

    return result;
}

export default Ember.Helper.helper(cutString);
