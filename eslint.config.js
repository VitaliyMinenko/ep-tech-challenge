// eslint.config.js
/** @type {import('eslint').Linter.FlatConfig} */
const config = [
    {
        languageOptions: {
            parserOptions: {
                ecmaVersion: 12,
                sourceType: 'module',
            },
            globals: {
                browser: true,
                es2021: true,
            },
        },
        rules: {
        },
    },
    {
        files: ['./resources/**/*.vue'],
        languageOptions: {
            parser: '@babel/eslint-parser',
        },
        plugins: {
            vue: 'eslint-plugin-vue',
        },
        rules: {
        },
    },
];

module.exports = config;
