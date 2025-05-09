import DS from 'ember-data';

export default DS.Model.extend({
    title: DS.attr('string'),
    url: DS.attr('string'),
    category: DS.belongsTo('category'),
    categoryId: DS.attr('number'),
    hidden: DS.attr('boolean', {defaultValue: true}),
    disableComments: DS.attr('boolean'),
    forceCreatedAt: DS.attr('string'),
    text: DS.attr('string'),
    description: DS.attr('string'),
    tagsString: DS.attr('string'),
    lastUpdate: DS.attr('date'),
    timeCreated: DS.attr('date')
});
