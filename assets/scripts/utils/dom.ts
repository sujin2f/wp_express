// app/utils/dom

type Nodes = Element | Element[] | NodeList | HTMLCollectionOf<Element>;

export const DOMUtils = {
  nodes: (nodes: Nodes): Element[] => Array.prototype.slice.call(nodes instanceof Element ? [nodes] : nodes),
  querySelectorAll: (query: string): Element[] => DOMUtils.nodes(document.querySelectorAll(query)),
}
