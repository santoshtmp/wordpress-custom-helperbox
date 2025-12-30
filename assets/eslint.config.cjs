// eslint.config.cjs
const js = require("@eslint/js");
const globals = require("globals");
const { FlatCompat } = require("@eslint/eslintrc");

const compat = new FlatCompat({
  baseDirectory: __dirname,
});

module.exports = [
  // Built-in ESLint recommended rules
  js.configs.recommended,

  // Legacy configs translated properly (these include WordPress rules via eslint-config-wordpress)
  ...compat.extends("airbnb-base"),
  ...compat.extends("wordpress"),     // This pulls in eslint-config-wordpress rules
  ...compat.extends("prettier"),      // Disables formatting rules that conflict with Prettier

  // Custom overrides (globals, etc.)
  {
    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.node,
        wp: "readonly",
        jQuery: "readonly",
        $: "readonly",
      },
      ecmaVersion: "latest",
      sourceType: "module",
    },
    // extends: [
    //   "stylelint-config-standard-scss",   // Good SCSS rules
    //   "stylelint-config-prettier",        // Disable formatting rules handled by Prettier
    // ],
    rules: {
      "func-names": "off",
      // Add any specific overrides here if needed
      // e.g., "import/extensions": "off",  // Common relaxation for WordPress
    },
  },

  // Ignores
  {
    ignores: [
      "node_modules/**",
      "build/**",
      "vendor/**",
      "**/*.min.js",
      // Add any other patterns from your old .ignores or .eslintignore
    ],
  },
];