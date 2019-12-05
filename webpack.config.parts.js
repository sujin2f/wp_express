/* eslint-disable */
const path = require('path');
const webpack = require('webpack');
const FriendlyErrorsWebpackPlugin = require('friendly-errors-webpack-plugin');
const CompressionPlugin = require('compression-webpack-plugin');
const WebpackCleanPlugin = require('webpack-clean');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');
const ManifestPlugin = require('webpack-manifest-plugin');
const TerserPlugin = require('terser-webpack-plugin');

const setBase = (entry, dist, wpThemePath) => {
  const garbage = Object.keys(entry)
    .filter(key => entry[key].endsWith('.scss'))
    .reduce((value, key) => {
      const filename = entry[key].split('/').pop();
      return [
        ...value,
        path.resolve(dist, filename.replace('.scss', '.js')),
        path.resolve(dist, filename.replace('.scss', '.js.map')),
      ];
    }, []);

  return {
    mode: isProduction() ? 'production' : 'development',
    devtool: isProduction() ? false : 'inline-source-map',
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
      /*
       * Clean .js from .sass fater build
       */
      new WebpackCleanPlugin(garbage),
      new FriendlyErrorsWebpackPlugin(),
      new webpack.NoEmitOnErrorsPlugin(),
      new ManifestPlugin(),
      new CompressionPlugin({
          test: /\.js$|\.css$|\.html$|\.eot?.+$|\.ttf?.+$|\.woff?.+$|\.svg?.+$/,
          filename: '[path].gz[query]',
          algorithm: 'gzip',
          threshold: 10240,
          minRatio: 0.8,
      }),
    ],
    optimization: {
      splitChunks: { chunks: 'all' },
    },
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
    plugins: [
      new MiniCssExtractPlugin({
        filename: '[name].[hash].css',
      }),
    ],
    module: {
      rules: [
        {
          test: /\.s?css$/,
          use: [
            {
              loader: MiniCssExtractPlugin.loader,
            },
            {
              loader: 'css-loader',
              options: { sourceMap: true },
            },
            {
              loader: 'postcss-loader',
              options: {
                sourceMap: true,
                plugins: [require('autoprefixer')],
              },
            },
            {
              loader: 'sass-loader',
              options: { sourceMap: true },
            },
          ],
        },
      ],
    },
    optimization: {
      minimizer: [
        new OptimizeCSSAssetsPlugin(),
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
