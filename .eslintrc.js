module.exports = {
  env: {
    es6: true,
    browser: true,
    node: true,
  },
  parser: 'babel-eslint',
  parserOptions: {
    ecmaFeatures: {
      jsx: true,
    },
    ecmaVersion: 2018,
    sourceType: 'module',
  },
  globals: {
    $: true,
    axios: true,
    Handsontable: true,
    jQuery: true,
    Qs: true,
    toastr: true,
    Vue: true,
    Vues: true,
  },
  extends: ['eslint:recommended', 'eslint-config-prettier'],
  plugins: ['prettier'],
  rules: {
    'prettier/prettier': ['error'],
    'no-trailing-spaces': ['warn'],
    'no-unused-vars': ['warn'],
    semi: ['error'],
  },
};
