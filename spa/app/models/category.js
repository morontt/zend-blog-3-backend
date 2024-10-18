import DS from 'ember-data';

export default DS.Model.extend({
    name: DS.attr('string'),
    url: DS.attr('string'),
    depth: DS.attr('number'),
    parentId: DS.attr('number'),
    parent: DS.belongsTo('category', { inverse: null }),
    depthPrefix: function () {
        let depth = this.get('depth');
        let prefix = '';
        if (depth > 1) {
            prefix = '..'.repeat(depth - 1);
        }

        return prefix;
    }.property('depth')
});
