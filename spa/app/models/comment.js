import DS from 'ember-data';

export default DS.Model.extend({
    text: DS.attr('string'),
    commentator: DS.belongsTo('commentator'),
    ipAddr: DS.attr('string'),
    disqusId: DS.attr('number'),
    createdAt: DS.attr('date')
});
