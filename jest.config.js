module.exports = {
  collectCoverageFrom: [
    '<rootDir>/assets/*.ts',
    "!<rootDir>/assets/**/*.spec.ts",
    "!<rootDir>/assets/**/__*__/*",
  ],
  collectCoverage: true,
  coverageDirectory: "<rootDir>/logs",
  coverageReporters: ["clover"],
  globals: {
    'ts-jest': {
      tsConfig: 'tsconfig.json',
    },
  },
  moduleNameMapper: {
    "app/(.*)": "<rootDir>/assets/scripts/$1",
  },
  modulePathIgnorePatterns: [
      "__mocks__"
  ],
  transform: {
    "^.+\\.(htm|tsx?)$": "ts-jest",
  },
  verbose: true,
};
