import DS from 'ember-data';

export default DS.Model.extend({
    statusCode: DS.attr('number'),
    ipAddr: DS.attr('string'),
    requestUri: DS.attr('string'),
    userAgent: DS.belongsTo('userAgent'),
    articleTitle: DS.attr('string'),
    articleSlug: DS.attr('string'),
    isCDN: DS.attr('boolean'),
    createdAt: DS.attr('date')
});
