/**
 * Created by morontt.
 * Date: 22.11.14
 * Time: 11:51
 */

MttBlog.Category = DS.Model.extend({
    name: DS.attr('string'),
    url: DS.attr('string'),
    parent: DS.belongsTo('category')
});
