import {
  setAttachment,
  bindRemoveAction,
} from './media-upload/attachment';

jQuery(document).ready(($) => {
  // Upload Button
  $('.wp-express.field.attachment .btn-upload').click((e) => {
    const parent = $(e.currentTarget).parent('.wp-express.field.attachment');
    const parentId = parent.attr('data-parent');
    const isSingle = $(e.currentTarget)[0].hasAttribute('data-single');

    // Prepare media library
    const frame = wp.media && wp.media({
      title: 'Select or Upload Media',
      button: { text: 'Select' },
      multiple: !isSingle,
    });

    frame.on('open', () => {
      parent
        .find('.attachment__items__item')
        .each((_, elm) => {
          const attachmentId = $(elm).find('input').val();

          frame
            .state()
            .get('selection')
            .add(wp.media.attachment(attachmentId));
        });
    })
      .on('select', () => {
        const attachments = frame.state().get('selection').models;
        setAttachment(parentId, attachments, isSingle);
      })
      .open();

    e.preventDefault();
  });

  bindRemoveAction(jQuery('.wp-express.field.attachment .btn-remove'));
});
