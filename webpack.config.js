const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const CopyPlugin = require('copy-webpack-plugin');
const fs = require('fs');

// Get all block directories
const getBlockDirectories = () => {
    return fs.readdirSync(path.resolve(__dirname, 'src'))
        .filter(file => fs.statSync(path.resolve(__dirname, 'src', file)).isDirectory());
};

// Create entry points for all blocks
const blockEntries = getBlockDirectories().reduce((entries, blockName) => ({
    ...entries,
    [blockName]: `./src/${blockName}/index.js`
}), {});

module.exports = {
    ...defaultConfig,
    entry: blockEntries,
    output: {
        path: path.resolve(__dirname, 'build'),
        filename: '[name]/index.js'
    },
    plugins: [
        ...defaultConfig.plugins,
        new CopyPlugin({
            patterns: [
                {
                    from: 'src/*/style.css',
                    to: ({ context, absoluteFilename }) => {
                        const blockName = path.basename(path.dirname(absoluteFilename));
                        return `${blockName}/style.css`;
                    },
                    noErrorOnMissing: true
                },
                {
                    from: 'src/*/editor.css',
                    to: ({ context, absoluteFilename }) => {
                        const blockName = path.basename(path.dirname(absoluteFilename));
                        return `${blockName}/editor.css`;
                    },
                    noErrorOnMissing: true
                },
                {
                    from: 'src/*/block.json',
                    to: ({ context, absoluteFilename }) => {
                        const blockName = path.basename(path.dirname(absoluteFilename));
                        return `${blockName}/block.json`;
                    },
                    noErrorOnMissing: true
                }
            ]
        })
    ]
}; 