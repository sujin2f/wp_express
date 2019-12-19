import { Attachment } from 'app/components/attachment';
import { DOMUtils } from 'app/utils/dom';

const mediaConstructor = jest.fn();
const mediaAttachment = jest.fn();
const mediaStateGetAdd = jest.fn();
const mediaOpen = jest.fn();
const mediaCallback: {[key: string]: () => {}} = {};

const media = {
  attachment: (attachmentId: number) => mediaAttachment(attachmentId),
  state: () => {
    return {
      get: (_: string) => {
        return {
          add: () => mediaStateGetAdd(),
          models: [
            {
              id: 1,
              attributes: {
                url: 'http://url.com/new_picture1.jpg'
              },
            },
            {
              id: 2,
              attributes: {
                url: 'http://url.com/new_picture2.jpg'
              },
            },
            {
              id: 3,
              attributes: {
                url: 'http://url.com/new_picture3.jpg'
              },
            },
          ], // IAttachment[]
        };
      },
    };
  },
  open: () => mediaOpen(),
  on: (key: string, cb: () => {}) => {
    mediaCallback[key] = cb;
  },
};

const wp = {
  media: (props: any) => {
    mediaConstructor(props);
    // @ts-ignore
    wp.media = media;
    return media;
  },
};

(window as any).wp = { ...wp };

const html = `
  <section
    class="wp-express field attachment"
    data-id="attachment-test"
  >
    <section class="attachment__items" id="container">
      <section class="attachment__items__item" data-id="0">
        <input
          name="attachment-test[0]"
          type="hidden"
          value="13"
        />

        <div class="img-container" style="background-image: url('http://image.com/image.jpg');"></div>

        <button class="btn-remove" id="remove">
          <span class="dashicons dashicons-no"></span>
        </button>
      </section>
    </section>

    <a
      id="upload"
      class="button btn-upload"
      href="http://upload.link"
      data-single
    >
      Select Images
    </a>
  </section>`;

describe('app/attachment/index.ts', () => {
  describe('#init', () => {
    document.body.innerHTML = html;
    new Attachment();
    document.getElementById('upload').click();

    it('Constructor called', () => {
      expect(mediaConstructor).toHaveBeenCalled();
    });

    it('Events assigned', () => {
      expect(mediaCallback).toHaveProperty('open');
      expect(mediaCallback).toHaveProperty('select');

      // wp.media({ ... }).open() called
      expect(mediaOpen).toHaveBeenCalled();
    });

    it('wp.media({ ... }).open() called', () => {
      expect(mediaOpen).toHaveBeenCalled();
    });
  });

  describe('#remove', () => {
    it('bindRemoveEvent(), removeAttachment()', () => {
      document.body.innerHTML = html;
      new Attachment();
      document.getElementById('remove').click();

      // Item removed
      expect(document.getElementById('container').innerHTML.trim()).toEqual('');
    });
  });

  describe('#add attachment(s)', () => {
    it('single', () => {
      document.body.innerHTML = html;
      new Attachment();
      mediaCallback['select']();
      const style = document.querySelector('#container .img-container').getAttribute('style');

      expect(style).toEqual('background-image: url(\'http://url.com/new_picture1.jpg\')');
    });

    it('multiple', () => {
      document.body.innerHTML = html;
      const attachemnt = new Attachment();
      const attachments = media.state().get('').models;
      // TODO Better way?
      (attachemnt as any).renderAttachments('attachment-test', attachments, false);

      const containers = DOMUtils.querySelectorAll('#container .img-container');
      if (0 === containers.length) {
        fail('Multiple attachments selection has failed.');
      }

      containers.forEach((element: Element, index: number) => {
        const style = element.getAttribute('style');
        const idx = index + 1;

        expect(style).toEqual(`background-image: url('http://url.com/new_picture${idx}.jpg')`);
      });
    });
  });
});
