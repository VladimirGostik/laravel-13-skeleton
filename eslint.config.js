import js from '@eslint/js';
import vue from 'eslint-plugin-vue';
import vueParser from 'vue-eslint-parser';
import tsParser from '@typescript-eslint/parser';
import tsPlugin from '@typescript-eslint/eslint-plugin';
import prettier from 'eslint-config-prettier';

const browserAndNodeGlobals = {
    window: 'readonly',
    document: 'readonly',
    console: 'readonly',
    process: 'readonly',
    setTimeout: 'readonly',
    clearTimeout: 'readonly',
    setInterval: 'readonly',
    clearInterval: 'readonly',
    fetch: 'readonly',
    URL: 'readonly',
    URLSearchParams: 'readonly',
    CustomEvent: 'readonly',
    Event: 'readonly',
    EventListener: 'readonly',
    HTMLElement: 'readonly',
    HTMLInputElement: 'readonly',
    HTMLSelectElement: 'readonly',
    route: 'readonly',
};

export default [
    {
        ignores: [
            'node_modules/**',
            'vendor/**',
            'public/**',
            'resources/js/types/generated.d.ts',
            'storage/**',
            'bootstrap/cache/**',
        ],
    },
    js.configs.recommended,
    ...vue.configs['flat/recommended'],
    {
        files: ['**/*.{ts,js}'],
        languageOptions: {
            parser: tsParser,
            ecmaVersion: 'latest',
            sourceType: 'module',
            globals: browserAndNodeGlobals,
        },
        plugins: { '@typescript-eslint': tsPlugin },
        rules: {
            'no-unused-vars': 'off',
            '@typescript-eslint/no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
            'no-restricted-syntax': [
                'error',
                {
                    selector: "CallExpression[callee.name='ref']",
                    message:
                        "Do not use Vue 'ref' for inter-component state or application logic. Use props/emits, Inertia shared props, Pinia, or composables. 'ref' is allowed only for unavoidable imperative DOM access (template refs).",
                },
            ],
        },
    },
    {
        files: ['**/*.vue'],
        languageOptions: {
            parser: vueParser,
            parserOptions: {
                parser: tsParser,
                ecmaVersion: 'latest',
                sourceType: 'module',
                extraFileExtensions: ['.vue'],
            },
            globals: browserAndNodeGlobals,
        },
        plugins: { '@typescript-eslint': tsPlugin },
        rules: {
            'vue/multi-word-component-names': 'off',
            'vue/no-multiple-template-root': 'off',
            'vue/html-indent': 'off',
            'vue/html-self-closing': 'off',
            'vue/max-attributes-per-line': 'off',
            'vue/singleline-html-element-content-newline': 'off',
            'vue/attributes-order': 'off',
            'vue/no-v-html': 'off',
            'vue/no-v-text-v-html-on-component': 'off',
            'no-unused-vars': 'off',
            '@typescript-eslint/no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
            'no-restricted-syntax': [
                'error',
                {
                    selector: "CallExpression[callee.name='ref']",
                    message:
                        "Do not use Vue 'ref' for inter-component state or application logic. Use props/emits, Inertia shared props, Pinia, or composables. 'ref' is allowed only for unavoidable imperative DOM access (template refs).",
                },
            ],
        },
    },
    prettier,
];
