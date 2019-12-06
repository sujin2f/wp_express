/* eslint-disable */
const path = require('path');
const webpack = require('webpack');
const CompressionPlugin = require('compression-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');
const ManifestPlugin = require('webpack-manifest-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');

const setBase = (dist, wpThemePath) => {
  return {
    mode: isProduction() ? 'production' : 'development',
    devtool: isProduction() ? false : 'source-map',
    cache: isProduction() ? true : false,
    /*
     * Prevent Conflict from Gutenberg
     */
    externals: {
      lodash: 'lodash',
    },
    output: {
      path: dist,
      filename: '[name].[hash].js',
      publicPath: wpThemePath || '',
    },
    plugins: [
      /*
       * Clean destination before build
       */
      new CleanWebpackPlugin(),
      new ManifestPlugin(),
      new CompressionPlugin({
          test: /\.js$|\.css$|\.html$|\.eot?.+$|\.ttf?.+$|\.woff?.+$|\.svg?.+$/,
      }),
    ],
  };
};

const setJS = () => {
  return {
    module: {
      rules: [
        {
          test: /\.tsx?$/,
          use: 'awesome-typescript-loader',
          exclude: /node_modules/,
        },
      ],
    },
    optimization: {
      minimize: true,
      minimizer: [
        new TerserPlugin({
          parallel: true,
          sourceMap: true,
          // @see https://github.com/webpack-contrib/terser-webpack-plugin#terseroptions
          terserOptions: {
            compress: {
              drop_console: isProduction(),
            },
          },
        }),
      ],
    },
  };
};

const setCSS = () => {
  return {
    optimization: {
      minimize: true,
      minimizer: [
        new OptimizeCssAssetsPlugin({
          cssProcessorOptions: {
            discardComments: {
              removeAll: true,
            },
            map: {
              inline: false,
            },
          },
        }),
      ],
    },
    module: {
      rules: [
        {
          test: /\.s?css$/,
          use: [
            {
              loader: 'file-loader',
              options: {
                name: '[name].[hash].css',
              },
            },
            {
              loader: 'extract-loader'
            },
            {
              loader: 'css-loader',
            },
            {
              loader: 'postcss-loader',
              options: {
                plugins: [require('autoprefixer')],
              },
            },
            {
              loader: 'sass-loader',
            },
          ],
        },
      ],
    },
  };
};

const setFiles = (wpThemePath) => {
  return {
    module: {
      rules: [
        {
          test: /\.(gif|png|jpe?g|svg)$/i,
          loader: 'file-loader',
          options: {
            name: '[name].[hash].[ext]',
            outputPath: 'images',
            publicPath: `${wpThemePath || ''}/images/`,
          },
        },
      ],
    },
  };
};

const setAnalyzer = () => {
  if (isProduction() || isDev()) {
    return {};
  }

  return {
    plugins: [
      new BundleAnalyzerPlugin(),
    ],
  };
};

const setResolve = (resolvePath) => {
  return {
    resolve: {
      extensions: ['.js', '.jsx', '.json', '.ts', '.tsx'],
      alias: { ...resolvePath },
    },
  };
};

const isProduction = () => 'production' === process.env.APP_ENV;
const isDev = () => 'development' === process.env.APP_ENV;

module.exports = {
  setBase,
  setJS,
  setCSS,
  setFiles,
  setAnalyzer,
  setResolve,
};
