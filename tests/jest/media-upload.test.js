// https://www.grzegorowski.com/jest-tests-with-rewire-plugin/
// https://jestjs.io/docs/en/tutorial-jquery

function sum(a, b) {
  return a + b;
}
test( "hello test", () => {
  expect(sum(1, 2)).toBe(3);
});
