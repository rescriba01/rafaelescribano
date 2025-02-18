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
    [`blocks/${blockName}/index`]: `./src/${blockName}/index.js`
}), {});

// Add theme JavaScript entries
const themeEntries = {
    'js/gsap-config': './assets/js/modules/gsap-config.js',
    'js/animations': './assets/js/animations.js',
    'js/patterns/intro-with-links': './assets/js/patterns/intro-with-links.js'
};

// Merge all entries
const allEntries = {
    ...blockEntries,
    ...themeEntries
};

module.exports = {
    ...defaultConfig,
    entry: allEntries,
    output: {
        path: path.resolve(__dirname, 'build'),
        filename: '[name].js',
        clean: true
    },
    resolve: {
        ...defaultConfig.resolve,
        alias: {
            ...defaultConfig.resolve?.alias,
            '@modules': path.resolve(__dirname, 'assets/js/modules')
        }
    },
    module: {
        ...defaultConfig.module,
        rules: [
            // Handle block JSX files
            {
                test: /\.(js|jsx)$/,
                include: path.resolve(__dirname, 'src'),
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            '@babel/preset-env',
                            '@babel/preset-react'
                        ],
                        plugins: [
                            '@babel/plugin-transform-runtime'
                        ]
                    }
                }
            },
            // Handle theme JavaScript files
            {
                test: /\.js$/,
                include: path.resolve(__dirname, 'assets/js'),
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            }
        ]
    },
    plugins: [
        ...defaultConfig.plugins.filter(plugin => 
            // Remove the default CopyPlugin to avoid conflicts
            plugin.constructor.name !== 'CopyPlugin'
        ),
        new CopyPlugin({
            patterns: [
                // Copy block styles
                {
                    from: 'src/*/style.css',
                    to: ({ absoluteFilename }) => {
                        const blockName = path.basename(path.dirname(absoluteFilename));
                        return `blocks/${blockName}/style.css`;
                    },
                    noErrorOnMissing: true,
                    transform(content) {
                        return content;
                    }
                },
                // Copy editor styles
                {
                    from: 'src/*/editor.css',
                    to: ({ absoluteFilename }) => {
                        const blockName = path.basename(path.dirname(absoluteFilename));
                        return `blocks/${blockName}/editor.css`;
                    },
                    noErrorOnMissing: true,
                    transform(content) {
                        return content;
                    }
                },
                // Copy block.json files and update paths
                {
                    from: 'src/*/block.json',
                    to: ({ absoluteFilename }) => {
                        const blockName = path.basename(path.dirname(absoluteFilename));
                        return `blocks/${blockName}/block.json`;
                    },
                    noErrorOnMissing: true,
                    transform(content, absoluteFilename) {
                        const blockJson = JSON.parse(content.toString());
                        const blockName = path.basename(path.dirname(absoluteFilename));
                        
                        // Update file paths to match build directory structure
                        if (blockJson.editorScript) {
                            blockJson.editorScript = `file:./index.js`;
                        }
                        if (blockJson.script) {
                            blockJson.script = `file:./index.js`;
                        }
                        if (blockJson.style) {
                            blockJson.style = `file:./style.css`;
                        }
                        if (blockJson.editorStyle) {
                            blockJson.editorStyle = `file:./editor.css`;
                        }
                        
                        return JSON.stringify(blockJson, null, 2);
                    }
                }
            ]
        })
    ],
    optimization: {
        ...defaultConfig.optimization,
        splitChunks: {
            cacheGroups: {
                gsap: {
                    test: /[\\/]node_modules[\\/]gsap[\\/]/,
                    name: 'vendors/gsap',
                    chunks: 'all'
                }
            }
        }
    }
}; 