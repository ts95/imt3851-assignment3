var path = require('path');
var webpack = require('webpack');

module.exports = {
    entry: './assets/js/index.js',
    output: {
        path: path.resolve('./public/js'),
        filename: 'bundle.js',
    },
    devtool: 'source-map',
    plugins: [
        new webpack.optimize.UglifyJsPlugin({
            minimize: true,
            compress: {
                warnings: false,
            },
        }),
    ],
    module: {
        preLoaders: [
            {
                test: /\.js$/,
                loaders: ['babel-loader'],
                exclude: /node_modules/,
            }
        ],
        loaders: [
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
            },
            {
                test: /\.(scss|sass)$/,
                loaders: ['sass'],
            },
            {
                test: /\.vue$/,
                loader: 'vue',
            }
        ],
    },
    vue: {
        loaders: {
            js: 'babel',
        },
    },
};
