/**
 * Created by morontt.
 * Date: 15.02.15
 * Time: 18:35
 */

MttBlog.Post = DS.Model.extend({
    title: DS.attr('string'),
    url: DS.attr('string'),
    category: DS.belongsTo('category', { async: true }),
    hidden: DS.attr('boolean'),
    text: DS.attr('string'),
    description: DS.attr('string'),
    timeCreated: DS.attr('date')
});
