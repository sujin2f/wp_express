import $ from 'jquery';
global.$ = global.jQuery = $;

const test123 = require('../../assets/scripts/media-upload').test123;

// https://www.grzegorowski.com/jest-tests-with-rewire-plugin/
// https://jestjs.io/docs/en/tutorial-jquery

// require('../../wordpress/wp-includes/js/media-models');

/*
window.$ = require('../../wordpress/wp-includes/js/jquery/jquery');
require('../../wordpress/wp-includes/js/jquery/jquery-migrate.min');
*/

function sum(a, b) {
  return a + b;
}

// const test123 = require('../../assets/scripts/media-upload').__get__('test');

test( "hello test", () => {
  document.body.innerHTML = `
    <div class="wp-express field attachment" data-id="1">
      <span id="span" />
      <button class="btn-upload" />
    </div>
  `;

  // require('../../assets/scripts/media-upload');

  $('.btn-upload').click();



  // expect($('#span').text()).toEqual('a');


  expect(sum(1, 2)).toBe(3);
});

test('test123', () => {
  expect(test123()).toEqual(true);
});
