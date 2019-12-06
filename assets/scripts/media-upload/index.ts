interface Attachment {
  id: number;
  attributes: {
    url: string;
  };
};

export class MediaLibrary {
  private static DOM = {
    upload: '.wp-express.field.attachment .btn-upload',
    remove: '.wp-express.field.attachment .btn-remove',
    itemContainer: 'attachment__items__item',
  };

  public constructor() {
    const buttons = document.querySelectorAll(MediaLibrary.DOM.remove);
    this.bindClickEvent();
    this.bindRemoveEvent(Array.prototype.slice.call(buttons));
  }

  private bindClickEvent(): void {
    const buttons = document.querySelectorAll(MediaLibrary.DOM.upload);
console.log(buttons);
    Array.prototype.slice.call(buttons).map((button: Element) => {
      button.addEventListener('click', (e: MouseEvent) => {
        e.preventDefault();

        const target = e.currentTarget as Element;
        const parent = target.closest('.wp-express.field.attachment');
        const parentId = parent.getAttribute('data-parent');
        const isSingle = target.hasAttribute('data-single');

        // Prepare media library
        const frame = wp.media && wp.media({
          title: 'Select or Upload Media',
          button: {
            text: 'Select',
          },
          multiple: !isSingle,
        });

        frame.on('open', () => {
          const items = parent.querySelectorAll(`.${MediaLibrary.DOM.itemContainer}`);
console.log(items);
          Array.prototype.slice.call(items).map((value: Element) => {
            const attachmentId = value.getElementsByTagName('input');
            frame.state().get('selection').add(wp.media.attachment(attachmentId));
          })
        });

        frame.on('select', () => {
          const attachments = frame.state().get('selection').models as Attachment[];
          this.renderAttachments(parentId, attachments, isSingle);
        });

        frame.open();
      });
    });
  }

  private bindRemoveEvent(buttons: Element[]): void {
console.log(buttons);
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
    const selector = `section[data-parent="${parent}"] .${MediaLibrary.DOM.itemContainer}[data-index="${index}"]`;
    const targets = document.querySelectorAll(selector);
    Array.prototype.slice.call(targets).each((target: Element) => {
      target.parentNode.removeChild(target);
    });
  }

  private renderAttachments(parent:string, attachments: Attachment[], isSingle:boolean): void {
console.log(parent, attachments, isSingle);
    document.querySelector(`section[data-parent="${parent}"] .attachment__items`).innerHTML = '';

    attachments
      .filter((_:Attachment, index: number) => isSingle ? (index === 0) : true)
      .map((attachment:Attachment, index:number) => this.renderAttachment(
        parent,
        attachment.id,
        attachment.attributes.url,
        index,
      ));
  }

  private renderAttachment(
    parent: string,
    attachmentId: number,
    url: string,
    index: number
  ): void {
    const container = document.createElement('section');
    container.classList.add(MediaLibrary.DOM.itemContainer);
    container.setAttribute('data-index', index.toString());

    const input = document.createElement('input');
    input.setAttribute('name', `${parent}[${index}]`);
    input.value = attachmentId.toString();

    const div = document.createElement('div');
    div.classList.add('img-container');
    input.setAttribute('style', `backgroundImage: url('${url}')`);

    const button = document.createElement('button');
    button.classList.add('btn-remove');
    button.setAttribute('data-parent', parent);
    button.setAttribute('data-index', index.toString());

    const span = document.createElement('span');
    span.classList.add('dashicons dashicons-no');

    button.appendChild(span);

    container.appendChild(input);
    container.append(div);
    container.append(button);

    this.bindRemoveEvent([button]);

    document.querySelector(`section[data-parent="${parent}"] .attachment__items`)
      .appendChild(container);
  }
};
