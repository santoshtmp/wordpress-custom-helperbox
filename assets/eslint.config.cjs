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
  ...compat.extends("wordpress"),     // This pulls in eslint-config-wordpress rules
  ...compat.extends("prettier"),      // Disables formatting rules that conflict with Prettier

  // Custom overrides (globals, etc.)
  {
    languageOptions: {
      globals: {
        ...globals.browser,   // window, document, etc.
        ...globals.jquery,    // jQuery, $
        wp: "readonly",       // WordPress global
        ace: "readonly",      // Your ACE editor
        helperboxJS: "readonly", // Your localized script data
      },
      ecmaVersion: "latest",
      sourceType: "module",     // For import/export syntax
    },
    rules: {
      "func-names": "off",                // Allow anonymous functions as needed Ex: function() {}
      // Relax some common WordPress-specific rules if needed
      'no-console': 'off',                   // Allow console.log for debugging
      'camelcase': 'off',                    // WordPress often uses snake_case Ex: post_type
      'no-underscore-dangle': 'off',         // WordPress often uses _underscores variables Ex: _e(), _n()
      "consistent-return": "off",           // Allows functions to sometimes not return a value Ex: event handlers
      "no-restricted-syntax": "off",       // Allows for...of etc. if needed Ex: for (const item of items) {}
      "no-alert": "off",                   // You use alert() for JSON errors
      "no-param-reassign": "off",          // Common in media uploaders

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
      "**/*.php",
      // Add any other patterns from your old .ignores or .eslintignore
    ],
  },
];