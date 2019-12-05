/* eslint-disable */
const path = require('path');
const merge = require('webpack-merge');
const {
  setBase,
  setResolve,
  setJS,
  setCSS,
  setFiles,
  setAnalyzer,
} = require('./webpack.config.parts');

const dist = path.resolve(__dirname, 'assets', 'dist');

const entry = {
  'app': path.resolve(__dirname, 'assets', 'scripts', 'app.ts'),
  'meta': path.resolve(__dirname, 'assets', 'styles', 'meta.scss'),
};

const resolve = {
  app: path.resolve(__dirname, 'assets', 'scripts'),
};

const config = [
  merge.smart(
    { entry },
    setBase(entry, dist, null),
    setResolve(resolve),
    setJS(),
    setCSS(),
    setFiles(null),
    setAnalyzer(),
  ),
];

module.exports = config;
