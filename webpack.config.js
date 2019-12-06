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
  'app': [
    path.resolve(__dirname, 'assets', 'scripts', 'app.ts'),
    path.resolve(__dirname, 'assets', 'styles', 'style.scss'),
  ],
};

const resolve = {
  app: path.resolve(__dirname, 'assets', 'scripts'),
};

const config = [
  merge.smart(
    { entry },
    setBase(dist, null),
    setResolve(resolve),
    setJS(),
    setCSS(),
    setFiles(null),
    setAnalyzer(),
  ),
];

module.exports = config;
