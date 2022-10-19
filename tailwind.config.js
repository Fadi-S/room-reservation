/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.ts",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                vazirmatn: ["Vazirmatn", "sans-serif"],
            },
        },
    },
    plugins: [require("@tailwindcss/forms")],
};
