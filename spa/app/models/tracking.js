import DS from 'ember-data';

export default DS.Model.extend({
    statusCode: DS.attr('number'),
    ipAddr: DS.attr('string'),
    city: DS.attr('string'),
    region: DS.attr('string'),
    country: DS.attr('string'),
    countryFlag: DS.attr('string'),
    requestUri: DS.attr('string'),
    userAgent: DS.belongsTo('userAgent'),
    articleTitle: DS.attr('string'),
    articleSlug: DS.attr('string'),
    isCDN: DS.attr('boolean'),
    duration: DS.attr('string'),
    method: DS.attr('string'),
    createdAt: DS.attr('date'),
    privateIP: function () {
        return this.get('city') === '-';
    }.property('city'),
});
