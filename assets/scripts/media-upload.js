import { setAttachment, removeAttachment } from './media-upload/attachment';

jQuery(document).ready(() => {
  const frame = wp.media && wp.media({
    title: 'Select or Upload Media Of Your Chosen Persuasion',
    button: { text: 'Use this media' },
    multiple: false,
  });

  jQuery('.wp-express.field.attachment .btn-upload').click((e) => {
    const id = jQuery(e.currentTarget)
      .parent('.wp-express.field.attachment')
      .attr('data-id');

    e.preventDefault();

    frame.on('select', () => {
      const attachment = frame.state().get('selection').first().toJSON();
      setAttachment(id, attachment);
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
