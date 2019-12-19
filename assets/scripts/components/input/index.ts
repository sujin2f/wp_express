// app/components/input
import { DOMUtils } from 'app/utils/dom';

export class Input {
  private static DOM = {
    classes: {
      itemsContainer: 'input__items',
    },
    attrs: {
      multiple: 'data-multiple',
      next: 'data-next',
    }
  };

  public constructor() {
    const inputs = DOMUtils.querySelectorAll(`.${Input.DOM.classes.itemsContainer}[${Input.DOM.attrs.multiple}] input`);
    inputs.map((input: HTMLInputElement) => {
      this.bindTextEvent(input);
    });
  }

  private bindTextEvent(input: HTMLInputElement): void {
    input.addEventListener('keyup', (_: KeyboardEvent) => {
      const container = input.closest(`.wp-express.${Input.DOM.classes.itemsContainer}`);
      const inputs = DOMUtils.nodes(container.getElementsByTagName('input'));
      const hasValue = inputs.filter((target: HTMLInputElement) => target.value);
      const emptyInputs = inputs.length - hasValue.length;

      // Remove input when the empty values are more than one
      if ('' === input.value && 1 < emptyInputs) {
        input.parentNode.removeChild(input);
        return;
      }

      // Add an empty input
      if (0 === emptyInputs) {
        this.addInput(container);
      }
    });
  }

  /*
   * Add an empty input
   */
  private addInput(container: Element): void {
    const index = parseInt(container.getAttribute(Input.DOM.attrs.next), 10);
    const nextIndex = index + 1;
    container.setAttribute(Input.DOM.attrs.next, (nextIndex).toString());

    const input = container.getElementsByTagName('input')[0].cloneNode(true) as HTMLInputElement;
    const id = container.getAttribute('data-id');
    input.setAttribute('name', `${id}[${index}]`)
    input.value = '';

    this.bindTextEvent(input);
    container.append(input);
  }
};
