function uploadButton() {
  jQuery('.wp_express.btn-upload').click((e) => {
    const id = jQuery(e.currentTarget).attr('data-id');
    e.preventDefault();

    const frame = wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: { text: 'Use this media' },
      multiple: false,
    });

    frame.on('select', () => {
      const attachment = frame.state().get('selection').first().toJSON();
      jQuery(`#${id}-custom-img-container`)
        .append(`<img src="${attachment.url}" width="150" />`);
      jQuery(`#${id}-custom-img-id`).val(attachment.id);

      jQuery(`#${id}-upload-custom-img`).addClass('hidden');
      jQuery(`#${id}-delete-custom-img`).removeClass('hidden');
    });

    frame.open();
  });
}

function removeButton() {
  jQuery('.wp_express.btn-remove').on('click', (e) => {
    const id = jQuery(e.currentTarget).attr('data-id');
    e.preventDefault();

    jQuery(`#${id}-custom-img-container`).html('');
    jQuery(`#${id}-custom-img-id`).val('');

    jQuery(`#${id}-upload-custom-img`).removeClass('hidden');
    jQuery(`#${id}-delete-custom-img`).addClass('hidden');
  });
}

jQuery(document).ready(() => {
  uploadButton();
  removeButton();
});
