import DS from 'ember-data';

export default DS.Model.extend({
    message: DS.attr('string'),
    user: DS.belongsTo('telegramUser'),
    replyId: DS.attr('number'),
    createdAt: DS.attr('date'),
});
