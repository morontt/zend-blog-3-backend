import DS from 'ember-data';
import config from 'mtt-blog/config/environment';

export default DS.Model.extend({
    text: DS.attr('string'),
    commentator: DS.belongsTo('commentator'),
    commentatorId: DS.attr('number'),
    username: DS.attr('string'),
    email: DS.attr('string'),
    website: DS.attr('string'),
    imageHash: DS.attr('string'),
    ipAddr: DS.attr('string'),
    city: DS.attr('string'),
    region: DS.attr('string'),
    country: DS.attr('string'),
    countryFlag: DS.attr('string'),
    parent: DS.belongsTo('comment', { inverse: null }),
    deleted: DS.attr('boolean', {defaultValue: false}),
    createdAt: DS.attr('date'),
    userAgent: DS.attr('string'),
    bot: DS.attr('boolean'),
    privateIP: function () {
        return this.get('city') === '-';
    }.property('city'),
    avatarTitle: function () {
        return this.get('imageHash') + '.png';
    }.property('imageHash'),
    avatarUrl: function () {
        return `${config.appParameters.cdnURL}/images/avatar/${this.get('imageHash')}.png`;
    }.property('imageHash')
});
