/** @type {import('tailwindcss').Config} */
const colors = require("tailwindcss/colors");
const colorClasses = {
    color1: "#011140",
    color2: "#397373",
    color3: "#6595BF",
    color4: "#C9EBF2",
    pink: "#ffb4b4",
    black: "#000000",
    dark: "#161616",
    white: "#FFFFFF",
    red: "#BF0404",
    green: "#02731E",
    grayBlue: "#c7d2da",
    gray: colors.gray,
    ligthGreen: "#54ffaf",
    redLight: "#ff6464",
    background: "#ececec",
    transparent: "transparent",
    zelle: "#6a1ccd",
    binance: "#F3BA2F",
<<<<<<< HEAD
    orange:"#f38f04",
=======
    orange: "#be5c00",
>>>>>>> ce26a355f8e6b9e91753fcf1301e86f55bda2977
};
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.svelte",
    ],
    safelist: [
        ...Object.keys(colorClasses).map((color) => `border-${color}`),
        ...Object.keys(colorClasses).map((color) => `hover:bg-${color}`),
    ],
    theme: {
        colors: {
            color1: "#011140",
            color2: "#397373",
            color3: "#6595BF",
            color4: "#C9EBF2",
            pink: "#ffb4b4",
            black: "#000000",
            dark: "#161616",
            white: "#FFFFFF",
            red: "#BF0404",
            green: "#02731E",
            grayBlue: "#c7d2da",
            gray: colors.gray,
            ligthGreen: "#54ffaf",
            redLight: "#ff6464",
            background: "#ececec",
            transparent: "transparent",
            zelle: "#6a1ccd",
            binance: "#F3BA2F",
<<<<<<< HEAD
            orange:"#f38f04",
=======
            orange: "#be5c00",
>>>>>>> ce26a355f8e6b9e91753fcf1301e86f55bda2977
        },
        extend: {},
    },
    plugins: [],
};
