import Ember from 'ember';

export default Ember.Controller.extend({
    appController: Ember.inject.controller('application'),
    queryParams: ['page'],
    page: 1,
    enableLangButton: true,
    newLangName: '',
    newLangLexer: '',
    actions: {
        createLang() {
            if (this.get('enableLangButton')) {
                this.set('enableLangButton', false);

                var lang = this.store.createRecord('pygmentsLanguage', {
                    name: this.get('newLangName'),
                    lexer: this.get('newLangLexer')
                });

                lang.save().then(
                    () => {
                        this.set('enableLangButton', true);
                        this.set('newLangName', '');
                        this.set('newLangLexer', '');

                        this.send('closeModal');
                        this.get('target.router').refresh();
                    },
                    () => {
                        this.set('enableLangButton', true);
                    }
                );
            }
        },
        removeLang() {
            var lang_id = $('#confirmation-modal').attr('data-object-id');
            this.store.peekRecord('pygmentsLanguage', lang_id).destroyRecord();
        },
        openModal() {
            $('#modal_new_lang').modal();
        },
        closeModal() {
            $('#modal_new_lang').modal('hide');
        }
    }
});
