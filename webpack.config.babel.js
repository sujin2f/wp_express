/* eslint-disable */
import path from 'path';
import merge from 'webpack-merge';
import parts from './webpack.config.parts';

const dist = path.resolve(__dirname, 'assets', 'dist');

const config = [
  merge.smart(
    {
      // Entry points, resolver path, and output path
      entry: {
        'media-upload': path.resolve(__dirname, 'assets', 'scripts', 'media-upload.js'),
        'meta': path.resolve(__dirname, 'assets', 'styles', 'meta.scss'),
      },
    },
    parts.setBase(dist),
  ),
];

module.exports = config;
