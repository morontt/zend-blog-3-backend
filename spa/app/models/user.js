import DS from 'ember-data';
import config from 'mtt-blog/config/environment';

export default DS.Model.extend({
    username: DS.attr('string'),
    displayName: DS.attr('string'),
    email: DS.attr('string'),
    role: DS.attr('string'),
    isMale: DS.attr('boolean'),
    imageHash: DS.attr('string'),
    createdAt: DS.attr('date'),
    avatarTitle: function () {
        return this.get('imageHash') + '.png';
    }.property('imageHash'),
    avatarUrl: function () {
        return `${config.appParameters.cdnURL}/images/avatar/${this.get('imageHash')}.png`;
    }.property('imageHash')
});
