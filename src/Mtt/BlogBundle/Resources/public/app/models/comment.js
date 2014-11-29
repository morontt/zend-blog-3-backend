/**
 * Created by morontt.
 * Date: 29.11.14
 * Time: 12:29
 */

MttBlog.Comment = DS.Model.extend({
    text: DS.attr('string'),
    commentator: DS.belongsTo('commentator'),
    ipAddr: DS.attr('string'),
    disqusId: DS.attr('number'),
    createdAt: DS.attr('date')
});
