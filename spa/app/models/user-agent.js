import DS from 'ember-data';

export default DS.Model.extend({
    name: DS.attr('string'),
    bot: DS.attr('boolean'),
    createdAt: DS.attr('date')
});
