// eslint.config.js
// Flat Config para ESLint 9+ com Vue 3

import js from '@eslint/js';
import pluginVue from 'eslint-plugin-vue';
import globals from 'globals';

export default [
    {
        // Ignorar arquivos/pastas
        ignores: [
            'node_modules/**',
            'dist/**',
            'public/**',
            'vendor/**',
            'storage/**',
            'bootstrap/cache/**',
            '.git/**',
        ],
    },

    {
        // Configuração para arquivos JavaScript
        files: ['**/*.js'],
        ...js.configs.recommended,
        languageOptions: {
            ecmaVersion: 2021,
            sourceType: 'module',
            globals: {
                ...globals.browser,
                ...globals.node,
            },
        },
        rules: {
            'no-unused-vars': 'warn',
            'no-console': 'off',
        },
    },

    {
        // Configuração para arquivos Vue
        files: ['**/*.vue'],
        ...pluginVue.configs['flat/recommended'],
        languageOptions: {
            ecmaVersion: 2021,
            sourceType: 'module',
            parserOptions: {
                parser: '@babel/eslint-parser',
                requireConfigFile: false,
            },
            globals: {
                ...globals.browser,
            },
        },
        rules: {
            'vue/multi-word-component-names': 'off',
            'vue/no-unused-components': 'warn',
            'vue/no-unused-vars': 'warn',
            'no-unused-vars': 'warn',
            'no-console': 'off',
        },
    },
];