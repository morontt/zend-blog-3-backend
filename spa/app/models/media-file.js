import DS from 'ember-data';
import Ember from 'ember';
import config from 'mtt-blog/config/environment';

export default DS.Model.extend({
    path: DS.attr('string'),
    preview: DS.attr('string'),
    originalFilename: DS.attr('string'),
    description: DS.attr('string'),
    fileSize: DS.attr('number'),
    timeCreated: DS.attr('date'),
    lastUpdate: DS.attr('date'),
    post: DS.belongsTo('post'),
    postId: DS.attr('number'),
    defaultImage: DS.attr('boolean'),
    src: Ember.computed('preview', function () {
        return config.appParameters.cdnURL + this.get('preview');
    }),
    size: Ember.computed('fileSize', function () {
        var size = this.get('fileSize');
        var str;
        if (size > 1048576) {
            str = Math.round(size * 100 / 1048576) / 100 + ' MB';
        } else if (size > 1024) {
            str = Math.round(size * 10 / 1024) / 10 + ' KB';
        } else {
            str = size + ' B';
        }

        return str;
    })
});
