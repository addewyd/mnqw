﻿// Webpack v4
const path = require('path');

const ExtractTextPlugin = require('extract-text-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
    resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js' // 'vue/dist/vue.common.js' for webpack 1
    }
  },
  entry: { 
    main: './src/index.js'
    },  
    
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: '[name].js'
    }, 

  module: {
    rules: [
    {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: {
            js: 'babel-loader'
          }
        }
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader"
        }
      },
      {
        test: /\.(sass|scss)$/,
        include: path.resolve(__dirname, 'src/scss'),
        use: [
                {
                    loader: 'style-loader', // inject CSS to page
                }, 
                {
                    loader: 'css-loader', // translates CSS into CommonJS modules
                    options: {sourceMap: true}
                }, 
                {
                    loader: 'postcss-loader', // Run post css actions
                    options: {
                        plugins: function () { // post css plugins, can be exported to postcss.config.js
                             return [
                                require('precss'),
                                require('autoprefixer')
                            ];
                        }
                    }
                }, 
                {
                    loader: 'sass-loader', // compiles SASS to CSS
                    options: {
                        sourceMap: true
                    }
                }]
        },
        {
        test: /\.css$/,
        use: ExtractTextPlugin.extract(
          {
            fallback: 'style-loader',
            use: ['css-loader']
          })
      }
    ]
  },
plugins: [ 
    new HtmlWebpackPlugin({
      inject: false,
      hash: true,
      template: './src/index.html',
      filename: 'index.html'
    }),
    new VueLoaderPlugin(),
    new CopyWebpackPlugin([{
        from: './src/cntr',
        to: './cntr'
      },
      {
        from: './src/img',
        to: './img'
      }
    ])

  ],
  mode: 'development'
}
