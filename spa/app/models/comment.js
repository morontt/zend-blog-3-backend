import DS from 'ember-data';

export default DS.Model.extend({
    text: DS.attr('string'),
    commentator: DS.belongsTo('commentator'),
    commentatorId: DS.attr('number'),
    username: DS.attr('string'),
    email: DS.attr('string'),
    website: DS.attr('string'),
    emailHash: DS.attr('string'),
    ipAddr: DS.attr('string'),
    disqusId: DS.attr('number'),
    city: DS.attr('string'),
    region: DS.attr('string'),
    country: DS.attr('string'),
    createdAt: DS.attr('date'),
    gravatarUrl: function () {
        var defaults = ['wavatar', 'monsterid'];
        var idx = (this.get('commentatorId')) % 2;

        return '//www.gravatar.com/avatar/' + this.get('emailHash') + '?d=' + defaults[idx];
    }.property('commentatorId', 'emailHash')
});
