import DS from 'ember-data';

export default DS.Model.extend({
    title: DS.attr('string'),
    url: DS.attr('string'),
    category: DS.belongsTo('category'),
    categoryId: DS.attr('number'),
    hidden: DS.attr('boolean', {defaultValue: true}),
    text: DS.attr('string'),
    description: DS.attr('string'),
    tagsString: DS.attr('string'),
    timeCreated: DS.attr('date')
});
