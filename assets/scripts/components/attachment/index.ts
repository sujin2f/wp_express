// app/components/attachment
import { DOMUtils } from 'app/utils/dom';

export interface IAttachment {
  id: number;
  attributes: {
    url: string;
  };
};

export class Attachment {
  private static DOM = {
    classes: {
      section: 'wp-express.attachment',
      attachmentBlock: 'attachment__items__item',
      upload: 'btn-upload',
      remove: 'btn-remove',
      itemsContainer: 'attachment__items',
      itemContainer: 'attachment__items__item',
    },
    attrs: {
      id: 'data-id',
      single: 'data-single',
    },
  };

  public constructor() {
    const removeButtons = DOMUtils.querySelectorAll(`.${Attachment.DOM.classes.section} .${Attachment.DOM.classes.remove}`);
    this.bindUploadEvent();
    this.bindRemoveEvent(removeButtons);
  }

  /*
   * Bind click events to the Select Image(s) button
   * Opens the WP media library
   */
  private bindUploadEvent(): void {
    const buttons = DOMUtils.querySelectorAll(`.${Attachment.DOM.classes.section} .${Attachment.DOM.classes.upload}`);
    buttons.map((button: Element) => {
      button.addEventListener('click', (e: MouseEvent) => {
        e.preventDefault();

        const target = e.currentTarget as Element;
        const parent = target.closest(`.${Attachment.DOM.classes.section}`);
        const parentId = this.getSectionId(target);
        const isSingle = target.hasAttribute(Attachment.DOM.attrs.single);

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
          const items = parent.querySelectorAll(`.${Attachment.DOM.classes.itemContainer}`);
          DOMUtils.nodes(items).map((item: Element) => {
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
        this.removeAttachment(target);
      });
    });
  }

  private removeAttachment(target: Element): void {
    const parent = this.getSectionId(target);
    const index = this.getAttachmentId(target);

    let selector = `.${Attachment.DOM.classes.section}[${Attachment.DOM.attrs.id}="${parent}"] `;
    selector += `.${Attachment.DOM.classes.itemContainer}[${Attachment.DOM.attrs.id}="${index}"]`;

    const remove = document.querySelector(selector);
    remove.parentNode.removeChild(remove);
  }

  /*
   * Remove container and create items in it
   */
  private renderAttachments(parent:string, attachments: IAttachment[], isSingle:boolean): void {
    let selector = `.${Attachment.DOM.classes.section}[${Attachment.DOM.attrs.id}="${parent}"] `;
    selector += `.${Attachment.DOM.classes.itemsContainer}`;
    document.querySelector(selector).innerHTML = '';

    attachments
      .filter((_:IAttachment, index: number) => isSingle ? (index === 0) : true)
      .forEach((attachment:IAttachment, index:number) => {
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
    container.classList.add(Attachment.DOM.classes.itemContainer);
    container.setAttribute(Attachment.DOM.attrs.id, index.toString());

    const input = document.createElement('input');
    input.setAttribute('name', `${parent}[${index}]`);
    input.setAttribute('type', 'hidden');
    input.value = attachmentId.toString();

    const div = document.createElement('div');
    div.classList.add('img-container');
    div.setAttribute('style', `background-image: url('${url}')`);

    const button = document.createElement('button');
    button.classList.add(Attachment.DOM.classes.remove);

    const span = document.createElement('span');
    span.classList.add('dashicons');
    span.classList.add('dashicons-no');

    button.appendChild(span);

    container.appendChild(input);
    container.append(div);
    container.append(button);

    this.bindRemoveEvent([button]);

    let selector = `.${Attachment.DOM.classes.section}[${Attachment.DOM.attrs.id}="${parent}"] `;
    selector += `.${Attachment.DOM.classes.itemsContainer}`;

    document.querySelector(selector)
      .appendChild(container);
  }

  private getSectionId(target: Element): string {
    return target
      .closest(`.${Attachment.DOM.classes.section}`)
      .getAttribute(Attachment.DOM.attrs.id);
  }

  private getAttachmentId(target: Element): string {
    return target
      .closest(`.${Attachment.DOM.classes.attachmentBlock}`)
      .getAttribute(Attachment.DOM.attrs.id);
  }
};
