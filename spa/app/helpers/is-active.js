import Ember from 'ember';

export function isActive(params/*, hash*/) {
  return params[0] === params[1] ? 'active' : '';
}

export default Ember.Helper.helper(isActive);
