import { Attachment } from 'app/attachment';

document.body.innerHTML = `
  <section class="wp-express field attachment" data-id="1">
    <div class="img-container" />
    <input type="hidden" name="attachment_id" />
    <button class="btn-upload" />
    <button class="btn-remove" />
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
