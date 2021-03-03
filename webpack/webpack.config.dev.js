const { merge } = require('webpack-merge');
const baseWebpackConfig = require('./webpack.base.config');
const webpack = require('webpack');
const path = require('path');

const buildWebpackConfig = merge(baseWebpackConfig, {
    mode: 'development',
    devtool: 'cheap-module-eval-source-map',
    plugins: [
        new webpack.SourceMapDevToolPlugin({
            filename: '[file].map'
        }),
    ]
})
module.exports = new Promise((resolve, reject) => {
    resolve(buildWebpackConfig)
})
