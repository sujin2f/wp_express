module.exports = {
  collectCoverageFrom: [
    '<rootDir>/assets/*.ts',
    "!<rootDir>/assets/**/*.spec.ts",
    "!<rootDir>/assets/**/__*__/*",
  ],
  verbose: true,
  collectCoverage: true,
  coverageDirectory: "<rootDir>/logs",
  coverageReporters: ["clover"],
  modulePathIgnorePatterns: [
      "__mocks__"
  ],
};
