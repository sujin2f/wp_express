import {
  setAttachment,
  bindRemoveAction,
} from './media-upload/attachment';

jQuery(document).ready(($) => {
  // Upload Button
  $('.wp-express.field.attachment .btn-upload').click((e) => {
    const parentId = $(e.currentTarget)
      .parent('.wp-express.field.attachment')
      .attr('data-parent');

    const isSingle = $(e.currentTarget)[0]
      .hasAttribute('data-single');

    e.preventDefault();

    // Prepare media library
    const frame = wp.media && wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: { text: 'Select' },
      multiple: !isSingle,
    });


    frame.on('ready', () => {
      // frame.state().get('selection').add();
    });

    frame.on('select', () => {
      const attachments = frame.state().get('selection').models;
      setAttachment(parentId, attachments, isSingle);
    });

    frame.open();
  });

  bindRemoveAction(jQuery('.wp-express.field.attachment .btn-remove'));
});
