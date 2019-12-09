// app/utils/dom

type Nodes = Element | Element[] | NodeList;

export const DOMUtils {
  nodes(nodes: Nodes): Node[] => Array.prototype.slice.call(nodes instanceof Element ? [nodes] : nodes),
  querySelectorAll(query: string): Node[] => DOMUtils.nodes(document.querySelectorAll(Attachment.DOM.remove)),
}
