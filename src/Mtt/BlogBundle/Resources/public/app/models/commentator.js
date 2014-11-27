/**
 * Created by morontt.
 * Date: 23.11.14
 * Time: 12:05
 */

MttBlog.Commentator = DS.Model.extend({
    name: DS.attr('string'),
    email: DS.attr('string'),
    website: DS.attr('string'),
    disqusId: DS.attr('number'),
    emailHash: DS.attr('string'),
    gravatarUrl: function () {
        var defaults = ['wavatar', 'monsterid'];
        var idx = (this.get('id')) % 2;

        return '//www.gravatar.com/avatar/' + this.get('emailHash') + '?d=' + defaults[idx];
    }.property('id', 'emailHash')
});
