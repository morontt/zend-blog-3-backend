import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    actions: {
        reply() {
            let comment = this.get('model.comment');
            let newComment = this.get('model.newComment');

            newComment.set('parent', comment);
            newComment.save().then(() => {
                this.transitionToRoute('comments');
            });
        }
    }
});
