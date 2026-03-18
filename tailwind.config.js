import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
              "primary-fixed": "#89f5e7",
              "surface-container-highest": "#e0e3e5",
              "secondary-fixed": "#c9e6ff",
              "on-primary-fixed": "#00201d",
              "on-error-container": "#93000a",
              "primary-container": "#008378",
              "secondary-fixed-dim": "#89ceff",
              "surface-bright": "#f7f9fb",
              "surface": "#f7f9fb",
              "tertiary-container": "#b05e3d",
              "surface-tint": "#006a61",
              "surface-container": "#eceef0",
              "on-background": "#191c1e",
              "error": "#ba1a1a",
              "on-tertiary": "#ffffff",
              "error-container": "#ffdad6",
              "surface-variant": "#e0e3e5",
              "on-secondary-container": "#004666",
              "background": "#f7f9fb",
              "inverse-primary": "#6bd8cb",
              "primary-fixed-dim": "#6bd8cb",
              "on-error": "#ffffff",
              "on-tertiary-fixed-variant": "#773215",
              "inverse-on-surface": "#eff1f3",
              "on-tertiary-fixed": "#370e00",
              "tertiary-fixed-dim": "#ffb59a",
              "primary": "#00685f",
              "tertiary": "#924628",
              "surface-container-low": "#f2f4f6",
              "on-secondary": "#ffffff",
              "surface-dim": "#d8dadc",
              "on-secondary-fixed": "#001e2f",
              "tertiary-fixed": "#ffdbce",
              "on-surface": "#191c1e",
              "secondary": "#006591",
              "on-surface-variant": "#3d4947",
              "on-primary-fixed-variant": "#005049",
              "surface-container-high": "#e6e8ea",
              "on-secondary-fixed-variant": "#004c6e",
              "secondary-container": "#39b8fd",
              "surface-container-lowest": "#ffffff",
              "on-primary-container": "#f4fffc",
              "outline": "#6d7a77",
              "on-tertiary-container": "#fffbff",
              "outline-variant": "#bcc9c6",
              "on-primary": "#ffffff",
              "inverse-surface": "#2d3133"
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                headline: ['Manrope', ...defaultTheme.fontFamily.sans],
                body: ['Inter', ...defaultTheme.fontFamily.sans],
                label: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
            },
        },
    },

    plugins: [forms],
};
