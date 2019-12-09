export class Input {
  private static DOM = {
    itemsContainer: 'input__items',
    itemContainer: 'input__items__item',
  };

  public constructor() {
    const inputs = document.querySelectorAll(`.${Input.DOM.itemsContainer}[data-multiple] input`);
    Array.prototype.slice.call(inputs).map((input: HTMLInputElement) => {
      this.bindTextEvent(input);
    });
  }

  private bindTextEvent(input: HTMLInputElement): void {
    input.addEventListener('keyup', (_: KeyboardEvent) => {
      const container = input.closest(`.wp-express.${Input.DOM.itemsContainer}`);
      const inputs = container.getElementsByTagName('input');
      const hasValue = Array.prototype.slice.call(inputs)
        .filter((target: HTMLInputElement) => target.value);
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
    const index = parseInt(container.getAttribute('data-next-index'), 10);
    const nextIndex = index + 1;
    container.setAttribute('data-next-index', (nextIndex).toString());

    const input = container.getElementsByTagName('input')[0].cloneNode(true) as HTMLInputElement;
    const id = container.getAttribute('data-id');
    input.setAttribute('name', `${id}[${index}]`)
    input.value = '';

    this.bindTextEvent(input);
    container.append(input);
  }
};
