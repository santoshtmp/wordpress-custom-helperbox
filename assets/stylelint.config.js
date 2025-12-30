module.exports = {
    extends: ["stylelint-config-standard-scss"],
    ignoreFiles: [
        "node_modules/**",
        "build/**",
        "**/*.min.css",
        // Add any other patterns
    ],
    rules: {
        "no-empty-source": null,
        "property-no-vendor-prefix": null,         // Allow -webkit-, -moz-, etc.

        // "no-descending-specificity": null,// Completely disable it
        // Or if you want warnings instead of errors:
        // "no-descending-specificity": [true, { severity: "warning" }],

        // Add any custom overrides here if needed later
        // Examples of common relaxations:
        // "selector-class-pattern": null,
        // "scss/dollar-variable-pattern": null,
        // "color-hex-length": "long",
    },
};