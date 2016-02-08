import DS from 'ember-data';

export default DS.Model.extend({
    title: DS.attr('string'),
    url: DS.attr('string'),
    category: DS.belongsTo('category'),
    hidden: DS.attr('boolean'),
    text: DS.attr('string'),
    description: DS.attr('string'),
    timeCreated: DS.attr('date')
});
