const glob = require('glob');
const path = require('path');
const common = require('./webpack.common');
const { merge } = require('webpack-merge');
const PurgeCSSPlugin = require('purgecss-webpack-plugin');

module.exports = merge(common, {
    mode: 'production',
    plugins: [
        new PurgeCSSPlugin({
            paths: glob.sync(path.join(__dirname, '../', 'templates') + '/**/*', { nodir: true }),
        })
    ],
});
