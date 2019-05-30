module.exports = {
  collectCoverageFrom: [
    'assets/**',
  ],
  verbose: true,
  collectCoverage: true,
  coverageDirectory: "<rootDir>/logs",
  coverageReporters: ["clover"],
};
