/**
 * Created by morontt.
 * Date: 22.11.14
 * Time: 18:11
 */

MttBlog.Tag = DS.Model.extend({
    name: DS.attr('string'),
    url: DS.attr('string'),
    newName: DS.attr('string'),
    newUrl: DS.attr('string')
});
