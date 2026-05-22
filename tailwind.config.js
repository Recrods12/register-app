/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                "bg-red": "#ef4444",
                "bg-green": "#10b981",
            },
        },
    },
    plugins: [],
};
