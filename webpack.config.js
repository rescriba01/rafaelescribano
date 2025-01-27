const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
    ...defaultConfig,
    entry: {
        'link-list': './src/link-list/index.js'
        // Add more blocks here as needed
    },
    output: {
        path: path.resolve(__dirname, 'build'),
        filename: '[name]/index.js'
    }
}; 