import DS from 'ember-data';

export default DS.Model.extend({
    code: DS.attr('string'),
    html: DS.attr('string'),
    language: DS.belongsTo('pygmentsLanguage'),
    languageId: DS.attr('number'),
    createdAt: DS.attr('date')
});
