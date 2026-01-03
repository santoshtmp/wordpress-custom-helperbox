/**
 * Webpack configuration.
 */

import path from 'path';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import RemoveEmptyScriptsPlugin from 'webpack-remove-empty-scripts';
import autoprefixer from 'autoprefixer';
import { fileURLToPath } from 'url';
import { globSync } from 'glob';

// __dirname replacement for ESM
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

/**
 * Helper to build named entries
 */
const buildEntries = (pattern, baseDir) => {
  const entries = {};

  globSync(pattern).forEach((file) => {
    let ext = path.extname(file).replace('.', ''); // js | scss
    ext = ext === 'scss' ? 'css' : ext;
    const name = `${ext}/` + path
      .relative(baseDir, file)
      .replace(path.extname(file), '');

    entries[name] = path.resolve(__dirname, file);
  });

  return entries;
};

/**
 * JS entries
 */
const jsEntries = {
  'js/helperbox': path.resolve(__dirname, 'src/js/helperbox.js'),
  'js/admin': path.resolve(__dirname, 'src/js/admin.js'),
  'js/login': path.resolve(__dirname, 'src/js/login.js'),
  ...buildEntries('src/block-types/**/*.js', 'src'),
};

/**
 * SCSS entries
 */
const cssEntries = {
  'css/helperbox': path.resolve(__dirname, 'src/scss/helperbox.scss'),
  'css/admin': path.resolve(__dirname, 'src/scss/admin.scss'),
  'css/login': path.resolve(__dirname, 'src/scss/login.scss'),
  ...buildEntries('src/block-types/**/*.scss', 'src'),
};

/**
 * 
 */
export default (env, argv) => {
  const isDev = argv.mode !== 'production';

  return {
    mode: isDev ? 'development' : 'production',

    entry: {
      ...jsEntries,
      ...cssEntries,
    },

    context: path.resolve(__dirname, 'src'),

    output: {
      path: path.resolve(__dirname, 'build'),
      filename: '[name].js',
      clean: true,
    },

    module: {
      rules: [
        // JS
        {
          test: /\.js$/,
          include: [...Object.values(jsEntries)],
          exclude: /node_modules/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: [
                [
                  '@babel/preset-env',
                  {
                    targets: '> 0.25%, not dead',
                  },
                ],
              ],
            },
          },
        },
        // SCSS SASS
        {
          test: /\.(sa|sc|c)ss$/,
          include: [...Object.values(cssEntries)],
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: 'css-loader',
              options: {
                sourceMap: isDev,
              },
            },
            {
              loader: 'postcss-loader',
              options: {
                sourceMap: isDev,
                postcssOptions: {
                  plugins: [
                    autoprefixer(),
                  ],
                },
              },
            },
            {
              loader: 'sass-loader',
              options: {
                sourceMap: isDev,
              },
            },
          ],
        },
        // Images
        {
          test: /\.(png|jpe?g|gif|svg|ico|webp)$/i,
          type: 'asset/resource',
          generator: {
            filename: '[path][name][ext]',
          },
        },
      ],
    },

    plugins: [
      new RemoveEmptyScriptsPlugin(),
      new MiniCssExtractPlugin({
        filename: '[name].css',
      }),
    ],

    devtool: isDev ? 'source-map' : false,

    performance: {
      maxAssetSize: 1048576,
      maxEntrypointSize: 1048576,
      hints: 'warning',
    },

    stats: 'minimal',
  };
};
