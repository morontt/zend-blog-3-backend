import DS from 'ember-data';
import config from 'mtt-blog/config/environment';

export default DS.Model.extend({
    name: DS.attr('string'),
    email: DS.attr('string'),
    website: DS.attr('string'),
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
