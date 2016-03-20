import Ember from 'ember';

export default Ember.Component.extend({
    classNames: ['form-group'],
    didInsertElement() {
        this._super(...arguments);

        function extractLast(term) {
            return term.split(/,\s*/).pop();
        }

        this.$('input').bind(
            'keydown',
            function (event) {
                if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
                    event.preventDefault();
                }
            }
        ).autocomplete({
            source(request, response) {
                var term = extractLast(request.term);
                if (term.length) {
                    $.ajax({
                        url: Routing.generate('tags_autocomplete'),
                        data: {
                            term: term
                        },
                        success(data) {
                            response(data);
                        }
                    });
                }
            },
            select(event, ui) {
                var terms = this.value.split(/,\s*/);
                terms.pop();
                terms.push(ui.item.value);
                terms.push('');
                this.value = terms.join(', ');

                return false;
            },
            focus() {
                return false;
            },
            minLength: 1
        });
    }
});
