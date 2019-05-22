export function setAttachment(id, attachment) {
  jQuery(`section[data-id="${id}"] .img-container`)
    .attr('style', `background-image: url('${attachment.url}');`)
    .removeClass('hidden');
  jQuery(`section[data-id="${id}"] input[type="hidden"]`).val(attachment.id);
  jQuery(`section[data-id="${id}"] .btn-upload`).addClass('hidden');
  jQuery(`section[data-id="${id}"] .btn-remove`).removeClass('hidden');
}

export function removeAttachment(id) {
  jQuery(`section[data-id="${id}"] .img-container`)
    .attr('style', '')
    .addClass('hidden');
  jQuery(`section[data-id="${id}"] input[type="hidden"]`).val('');
  jQuery(`section[data-id="${id}"] .btn-upload`).removeClass('hidden');
  jQuery(`section[data-id="${id}"] .btn-remove`).addClass('hidden');
}
