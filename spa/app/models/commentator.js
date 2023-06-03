import DS from 'ember-data';

export default DS.Model.extend({
    name: DS.attr('string'),
    email: DS.attr('string'),
    website: DS.attr('string'),
    emailHash: DS.attr('string'),
    forceImage: DS.attr('boolean'),
    imageHash: DS.attr('string'),
    gravatarUrl: function () {
        let url;
        if (this.get('imageHash')) {
            url = `${app_parameters.cdn_url}/images/avatar/${this.get('imageHash')}.png`;
        } else {
            let defaults = ['wavatar', 'monsterid'];
            let idx = (this.get('id')) % 2;

            url = `//www.gravatar.com/avatar/${this.get('emailHash')}?d=${defaults[idx]}`;
        }

        return url;
    }.property('id', 'emailHash', 'imageHash')
});
