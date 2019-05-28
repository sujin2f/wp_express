import $ from 'jquery';
global.$ = global.jQuery = $;
import { setAttachment, removeAttachment } from '../../assets/scripts/media-upload/attachment';

document.body.innerHTML = `
  <section class="wp-express field attachment" data-id="1">
    <div class="img-container" />
    <input type="hidden" name="attachment_id" />
    <button class="btn-upload" />
    <button class="btn-remove" />
  </section>`;

test( "Set Attachment Test", () => {
  const attachment = {
    id: 3,
    url: '/image.jpg',
  };

  setAttachment(1, attachment);

  expect($('.wp-express .img-container').css('background-image')).toEqual('url(/image.jpg)');
  expect(parseInt($('.wp-express input[name="attachment_id"]').val())).toEqual(3);
  expect($('.wp-express .btn-upload').hasClass('hidden')).toEqual(true);
  expect($('.wp-express .btn-remove').hasClass('hidden')).toEqual(false);
});

test( "Remove Attachment Test", () => {
  removeAttachment(1);

  expect($('.wp-express .img-container').attr('style')).toEqual('');
  expect($('.wp-express .img-container').hasClass('hidden')).toEqual(true);
  expect($('.wp-express input[name="attachment_id"]').val()).toEqual('');
  expect($('.wp-express .btn-upload').hasClass('hidden')).toEqual(false);
  expect($('.wp-express .btn-remove').hasClass('hidden')).toEqual(true);
});
