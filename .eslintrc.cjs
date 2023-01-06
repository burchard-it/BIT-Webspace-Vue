/* eslint-env node */
require('@rushstack/eslint-patch/modern-module-resolution');

module.exports = {
  'root': true,
  'extends': [
    'plugin:vue/vue3-essential',
    'eslint:recommended',
    '@vue/eslint-config-typescript',
  ],
  parserOptions: {
    ecmaVersion: 'latest',
  },
  'rules': {
    'comma-dangle': ['warn', 'always-multiline'],
    'object-curly-newline': ['warn', {multiline: true, consistent: true}],
    quotes: ['warn', 'single'],
    semi: ['warn', 'always'],
    'space-before-function-paren': ['warn', {
      anonymous: 'never',
      named: 'never',
      asyncArrow: 'always',
    }],
  },
};
