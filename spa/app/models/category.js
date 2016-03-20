import DS from 'ember-data';

export default DS.Model.extend({
    name: DS.attr('string'),
    url: DS.attr('string'),
    parentId: DS.attr('number'),
    parent: DS.belongsTo('category', { inverse: null })
});
