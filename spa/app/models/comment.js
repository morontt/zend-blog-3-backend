import DS from 'ember-data';

export default DS.Model.extend({
    text: DS.attr('string'),
    commentator: DS.belongsTo('commentator'),
    commentatorId: DS.attr('number'),
    username: DS.attr('string'),
    email: DS.attr('string'),
    website: DS.attr('string'),
    emailHash: DS.attr('string'),
    imageHash: DS.attr('string'),
    ipAddr: DS.attr('string'),
    city: DS.attr('string'),
    region: DS.attr('string'),
    country: DS.attr('string'),
    countryCode: DS.attr('string'),
    parent: DS.belongsTo('comment', { inverse: null }),
    deleted: DS.attr('boolean', {defaultValue: false}),
    createdAt: DS.attr('date'),
    gravatarUrl: function () {
        let url;
        if (this.get('imageHash')) {
            url = `${app_parameters.cdn_url}/images/avatar/${this.get('imageHash')}.png`;
        } else {
            let defaults = ['wavatar', 'monsterid'];
            let idx = (this.get('commentatorId')) % 2;

            url = `//www.gravatar.com/avatar/${this.get('emailHash')}?d=${defaults[idx]}`;
        }

        return url;
    }.property('commentatorId', 'emailHash', 'imageHash')
});
