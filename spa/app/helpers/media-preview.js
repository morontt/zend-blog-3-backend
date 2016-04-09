import Ember from 'ember';

export function mediaPreview(params) {
    var source = params[0];
    var result = '';

    const pattern = /\.(?:png|jpe?g|gif|bmp|tiff)$/i;
    if (pattern.test(source)) {
        result = Ember.String.htmlSafe(`<img src="${source}" height="60"/>`);
    }

    return result;
}

export default Ember.Helper.helper(mediaPreview);
