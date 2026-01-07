// stylelint.config.cjs  ← Must be this exact name for auto-loading

/** @type {import('stylelint').Config} */
module.exports = {
    extends: [
        "stylelint-config-standard-scss",     // Modern SCSS rules
        "stylelint-config-prettier-scss",     // Disable formatting conflicts if using Prettier
    ],

    rules: {
        "no-empty-source": null,
        "property-no-vendor-prefix": null,         // Allow -webkit-, -moz-, etc.
        // Optional overrides for WordPress/Tailwind projects
        "scss/at-rule-no-unknown": [
            true,
            {
                ignoreAtRules: ["tailwind", "apply", "variants", "responsive", "screen"],
            },
        ],
        "declaration-block-no-duplicate-properties": true,
        "no-descending-specificity": null,  // Often too strict with Tailwind
        // Optional: more Tailwind-friendly relaxations
        "selector-class-pattern": null, // Tailwind generates long random classes
        "value-keyword-case": null,     // Tailwind uses lowercase

        "selector-class-pattern": null,  // Disables kebab-case for classes
        "selector-id-pattern": null,     // Disables kebab-case for IDs

        // "no-descending-specificity": null,// Completely disable it
        // Or if you want warnings instead of errors:
        // "no-descending-specificity": [true, { severity: "warning" }],

        // Add any custom overrides here if needed later
        // Examples of common relaxations:
        // "selector-class-pattern": null,
        // "scss/dollar-variable-pattern": null,
        // "color-hex-length": "long",
    },
    ignoreFiles: [
        "node_modules/**",
        "build/**",
        "**/*.min.css",
        // Add any other patterns
    ],
};