import Ember from 'ember';

export default Ember.Component.extend({
    content: null,
    selectedValue: null,
    didInitAttrs() {
        this._super(...arguments);
        var content = this.get('content');

        if (!content) {
            this.set('content', []);
        }
    },
    actions: {
        change() {
            const selectedEl = this.$('select')[0];
            const selectedIndex = selectedEl.selectedIndex;
            const content = this.get('content');
            const selectedValue = content[selectedIndex].value;

            this.set('selectedValue', selectedValue);

            const changeAction = this.get('action');
            if (changeAction) {
                this.sendAction();
            }
        }
    }
});
