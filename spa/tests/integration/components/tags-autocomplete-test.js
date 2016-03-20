import { moduleForComponent, test } from 'ember-qunit';
import hbs from 'htmlbars-inline-precompile';

moduleForComponent('tags-autocomplete', 'Integration | Component | tags autocomplete', {
  integration: true
});

test('it renders', function(assert) {
  // Set any properties with this.set('myProperty', 'value');
  // Handle any actions with this.on('myAction', function(val) { ... });

  this.render(hbs`{{tags-autocomplete}}`);

  assert.equal(this.$().text().trim(), '');

  // Template block usage:
  this.render(hbs`
    {{#tags-autocomplete}}
      template block text
    {{/tags-autocomplete}}
  `);

  assert.equal(this.$().text().trim(), 'template block text');
});
