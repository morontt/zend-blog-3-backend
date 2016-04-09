import Ember from 'ember';

export function cutString([str, length]) {
    var result = str || '';

    if (result.length > length - 3) {
        result = result.substring(0, length - 3) + '...';
    }

    return result;
}

export default Ember.Helper.helper(cutString);
