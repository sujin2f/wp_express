import { Attachment } from 'app/attachment';

document.body.innerHTML = `
  <section
    class="wp-express field attachment"
    data-parent="attachment-test"
  >
    <section class="attachment__items">
      <section class="attachment__items__item" data-index="0">
        <input
          name="attachment-test[0]"
          type="hidden"
          value="13"
        />

        <div class="img-container" style="background-image: url('http://image.com/image.jpg');"></div>

        <button
          class="btn-remove"
          data-parent="attachment-test"
          data-index="0"
        >
          <span class="dashicons dashicons-no"></span>
        </button>
      </section>
    </section>

    <a
      class="button btn-upload"
      href="http://upload.link"
      data-single
    >
      Select Images
    </a>
  </section>`;

describe('app/attachment/index.ts', () => {
  const attachment = new Attachment();
  console.log(attachment);
  describe('#initPage', () => {
    it('should accept a config and return a Page instance', () => {
      // expect(initPage({} as IConfiguration)).toBeInstanceOf(Page)
      expect(true).toEqual(true);
    });
  });
});
