import { setAttachment, removeAttachment } from './media-upload/attachment';

jQuery(document).ready(($) => {
  // Upload Button
  $('.wp-express.field.attachment .btn-upload').click((e) => {
    const id = $(e.currentTarget)
      .parent('.wp-express.field.attachment')
      .attr('data-id');

    const single = $(e.currentTarget)[0]
      .hasAttribute('data-single');

    console.log(single);

    // Prepare media library
    const frame = wp.media && wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: { text: 'Select' },
      multiple: !single,
    });


    e.preventDefault();

    frame.on('select', () => {
      const attachments = frame.state().get('selection').models;
      setAttachment(id, attachments, single);
    });

    frame.open();
  });

  jQuery('.wp-express.field.attachment .btn-remove').on('click', (e) => {
    const id = jQuery(e.currentTarget)
      .parent('.wp-express.field.attachment')
      .attr('data-id');
    e.preventDefault();

    removeAttachment(id);
  });

  jQuery('.wp-express.field.attachment .img-container').on('click', (e) => {
    const id = jQuery(e.currentTarget)
      .parent('.wp-express.field.attachment')
      .attr('data-id');
    e.preventDefault();

    removeAttachment(id);
  });
});
