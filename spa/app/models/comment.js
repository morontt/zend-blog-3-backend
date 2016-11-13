import DS from 'ember-data';

export default DS.Model.extend({
    text: DS.attr('string'),
    commentator: DS.belongsTo('commentator'),
    ipAddr: DS.attr('string'),
    disqusId: DS.attr('number'),
    city: DS.attr('string'),
    region: DS.attr('string'),
    country: DS.attr('string'),
    createdAt: DS.attr('date')
});
