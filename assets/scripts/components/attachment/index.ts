// app/components/attachment

import { DOMUtils } from 'app/utils/dom';

interface IAttachment {
  id: number;
  attributes: {
    url: string;
  };
};

export class Attachment {
  private static DOM = {
    upload: '.wp-express.field.attachment .btn-upload',
    remove: '.wp-express.field.attachment .btn-remove',
    itemsContainer: '.attachment__items',
    itemContainer: 'attachment__items__item',
  };

  public constructor() {
    const removeButtons = DOMUtils.querySelectorAll(Attachment.DOM.remove);
    this.bindUploadEvent();
    this.bindRemoveEvent(removeButtons);
  }

  /*
   * Bind click events to the Select Image(s) button
   * Opens the WP media library
   */
  private bindUploadEvent(): void {
    const buttons = DOMUtils.querySelectorAll(Attachment.DOM.upload);
    buttons.map((button: Element) => {
      button.addEventListener('click', (e: MouseEvent) => {
        e.preventDefault();

        const target = e.currentTarget as Element;
        const parent = target.closest('.wp-express.field.attachment');
        const parentId = parent.getAttribute('data-parent');
        const isSingle = target.hasAttribute('data-single');

        // Media library
        const frame = wp.media && wp.media({
          title: 'Select or Upload Media',
          button: {
            text: 'Select',
          },
          multiple: !isSingle,
        });

        // Select existing items in the media library
        frame.on('open', () => {
          const items = parent.querySelectorAll(`.${Attachment.DOM.itemContainer}`);
          Array.prototype.slice.call(items).map((item: Element) => {
            const attachmentId = item.getElementsByTagName('input')[0].value;
            frame.state().get('selection').add(wp.media.attachment(attachmentId));
          })
        });

        // All done. Create attachments
        frame.on('select', () => {
          const attachments = frame.state().get('selection').models as IAttachment[];
          this.renderAttachments(parentId, attachments, isSingle);
        });

        frame.open();
      });
    });
  }

  /*
   * Bind click events to the Remove button
   */
  private bindRemoveEvent(buttons: Element[]): void {
    buttons.map((button: Element) => {
      button.addEventListener('click', (e: MouseEvent) => {
        e.preventDefault();

        const target = e.currentTarget as Element;
        const parent = target.getAttribute('data-parent');
        const index = target.getAttribute('data-index');
        this.removeAttachment(parent, index);
      });
    });
  }

  private removeAttachment(parent: string, index: string): void {
    const selector = `section[data-parent="${parent}"] .${Attachment.DOM.itemContainer}[data-index="${index}"]`;
    const target = document.querySelector(selector);
    target.parentNode.removeChild(target);
  }

  /*
   * Remove container and create items in it
   */
  private renderAttachments(parent:string, attachments: IAttachment[], isSingle:boolean): void {
    document.querySelector(`section[data-parent="${parent}"] ${Attachment.DOM.itemsContainer}`).innerHTML = '';

    attachments
      .filter((_:IAttachment, index: number) => isSingle ? (index === 0) : true)
      .map((attachment:IAttachment, index:number) => {
        this.renderAttachment(
          parent,
          attachment.id,
          attachment.attributes.url,
          index,
        )
      });
  }

  /*
   * Create a single attachement
   */
  private renderAttachment(
    parent: string,
    attachmentId: number,
    url: string,
    index: number
  ): void {
    const container = document.createElement('section');
    container.classList.add(Attachment.DOM.itemContainer);
    container.setAttribute('data-index', index.toString());

    const input = document.createElement('input');
    input.setAttribute('name', `${parent}[${index}]`);
    input.setAttribute('type', 'hidden');
    input.value = attachmentId.toString();

    const div = document.createElement('div');
    div.classList.add('img-container');
    div.setAttribute('style', `background-image: url('${url}')`);

    const button = document.createElement('button');
    button.classList.add('btn-remove');
    button.setAttribute('data-parent', parent);
    button.setAttribute('data-index', index.toString());

    const span = document.createElement('span');
    span.classList.add('dashicons');
    span.classList.add('dashicons-no');

    button.appendChild(span);

    container.appendChild(input);
    container.append(div);
    container.append(button);

    this.bindRemoveEvent([button]);

    document.querySelector(`section[data-parent="${parent}"] ${Attachment.DOM.itemsContainer}`)
      .appendChild(container);
  }
};
