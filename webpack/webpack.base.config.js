const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const webpack = require('webpack');
const autoprefixer = require('autoprefixer');
const { ManifestPlugin } = require('webpack-manifest-plugin');
const AssetsPlugin = require('assets-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { VueLoaderPlugin } = require('vue-loader');
const TerserPlugin = require('terser-webpack-plugin');
const Entry = require('./Entry');
const fs = require('fs');

const PATHS = {
    src: path.join(__dirname, '../src'),
    dist: path.join(__dirname, '../dist'),
}

const PAGES_DIR = `${PATHS.src}/pug/contacts`
const PAGES = fs.readdirSync(PAGES_DIR).filter(fileName => fileName.endsWith('.pug'))
const PUBLIC_PATH = path.join(__dirname, '..', 'public', 'assets');

module.exports = {
    stats: {
        colors: true,
        hash: true,
        timings: true,
        assets: true,
        chunks: true,
        chunkModules: false,
        modules: false,
        children: false,
    },
    performance: {
        hints: "warning",
    },
    bail: true,
    entry: {
        ...Entry.create(),
    },
    output: {
        path: PUBLIC_PATH,
        filename: 'js/[name].js',
        chunkFilename: 'js/[name].[chunkhash:8].chunk.js',
        publicPath: '/assets/',
    },
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                terserOptions: {
                    keep_classnames: true,
                    keep_fnames: true
                }
            })
        ],
        splitChunks: {
            chunks: 'all',
            name: false,
        }
        // splitChunks: {
        //     cacheGroups: {
        //         vendor: {
        //             name: 'vendors',
        //             test: /node_modules/,
        //             chunks: 'all',
        //             enforce: true
        //         }
        //     }
        // }
    },
    plugins: [
        new CleanWebpackPlugin(),
        new VueLoaderPlugin(),
        ...PAGES.map(page => new HtmlWebpackPlugin({
            template: `${PAGES_DIR}/${page}`,
            filename: `./${page.replace(/\.pug/, '.html')}`
        })),
        // new HtmlWebpackPlugin({
        //     //template: `${PATHS.src}/index.html`,
        //     //filename: './index.html'
        //     template: `${PAGES_DIR}/${page}`,
        //     filename: `./${page.replace(/\.pug/,'.html')}`
        // }),
        new AssetsPlugin({
            filename: 'assets.json',
            path: PUBLIC_PATH,
            prettyPrint: true,
            entrypoints: true
        }),
        new MiniCssExtractPlugin({
            filename: 'css/[name].[chunkhash:8].css',
            chunkFilename: 'css/[name].[chunkhash:8].css',
        }),
        // new MiniCssExtractPlugin({
        //     filename: `[name].[hash].css`
        // }),
        // new ManifestPlugin(),
        new webpack.LoaderOptionsPlugin({
            options: {
                postcss: [
                    autoprefixer()
                ]
            }
        }),
        new CopyWebpackPlugin({
            patterns: [
                { from: `${PATHS.src}/img`, to: `img` },
                { from: `${PATHS.src}/static`, to: `static` },
            ]
        })

    ],
    module: {
        rules: [
            {
                test: /\.(png|jpg|gif|svg|jpeg)$/,
                loader: 'file-loader',
                options: {
                    name: `img/[name].[ext]`
                    /*  outputPath: '/img',
                     publicPath: '/img' */

                }
            },
            {
                test: /\.(woff(2)?|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
                loader: 'file-loader',
                options: {
                    name: `fonts/[name].[ext]`

                }
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: 'babel-loader'
            },
            {
                test: /\.s?css/i,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            // you can specify a publicPath here
                            // by default it uses publicPath in webpackOptions.output
                            publicPath: '/assets/',
                        },
                    },
                    'css-loader',
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: [
                                    'autoprefixer',
                                    'cssnano'
                                ]
                            }
                        }
                    },
                    'sass-loader'
                ]
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            // {
            //     test: /\.vue$/,
            //     loader: 'vue-loader',
            //     options: {
            //         loader: {
            //             scss: 'vue-style-loader!css-loader!sass-loader'
            //         }
            //     }
            // },
            {
                test: /\.pug$/,
                oneOf: [{
                    resourceQuery: /^\?vue/,
                    use: 'pug-plain-loader'
                }, {
                    use: {
                        loader: 'pug-loader',
                        options: {
                            pretty: true,
                        },
                    }
                }],
            },
            // {
            //     test:/\.s[ac]ss$/,
            //     use: [
            //         'style-loader',
            //         MiniCssExtractPlugin.loader,
            //         {
            //             loader: 'css-loader',
            //             options: {sourceMap: true}
            //         },
            //         {
            //             loader: 'postcss-loader',
            //             options: {
            //                 postcssOptions:{
            //                     plugins:[
            //                         'autoprefixer',
            //                         'cssnano'
            //                     ]
            //                 }
            //             }
            //         },
            //         {
            //             loader: 'sass-loader',
            //             options: {sourceMap: true}
            //         }
            //     ]
            // },
        ]
    }
}