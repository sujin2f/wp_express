export function removeAttachment(parent, index) {
  jQuery(`section[data-parent="${parent}"] .attachment__items__item[data-index="${index}"]`)
    .remove();
}

export function bindRemoveAction(dom) {
  dom.on('click', (e) => {
    const parent = jQuery(e.currentTarget).attr('data-parent');
    const index = jQuery(e.currentTarget).attr('data-index');

    e.preventDefault();
    removeAttachment(parent, index);
  });
}

function setSingleAttachment(parent, attachmentId, url, index) {
  const container = jQuery('<section />')
    .addClass('attachment__items__item')
    .attr('data-index', index);

  const input = jQuery('<input />')
    .attr({
      name: `${parent}[${index}]`,
      type: 'hidden',
    })
    .val(attachmentId);

  const div = jQuery('<div />')
    .addClass('img-container')
    .css('background-image', `url('${url}')`);


  const button = jQuery('<button />')
    .addClass('btn-remove')
    .attr({
      'data-parent': parent,
      'data-index': index,
    });

  const span = jQuery('<span />')
    .addClass('dashicons dashicons-no');

  button.append(span);

  container
    .append(input)
    .append(div)
    .append(button);

  bindRemoveAction(button);

  jQuery(`section[data-parent="${parent}"] .attachment__items`)
    .append(container);
}

export function setAttachment(parent, attachments, isSingle) {
  jQuery(`section[data-parent="${parent}"] .attachment__items`)
    .html('');

  attachments
    .filter((_, index) => isSingle ? (index === 0) : true) // eslint-disable-line no-confusing-arrow
    .forEach((attachment, index) => setSingleAttachment(
      parent,
      attachment.id,
      attachment.attributes.url,
      index,
    ));
}
