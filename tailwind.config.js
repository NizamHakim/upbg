import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
  // prettier-ignore
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php", 
    "./storage/framework/views/*.php", 
    "./resources/views/**/*.blade.php", 
    "./resources/js/CustomSelect.js",
    "./public/js/**/*.js"
  ],

  theme: {
    extend: {
      fontFamily: {
        poppins: ["Poppins", "sans-serif"],
      },
      colors: {
        upbg: "#0866b7",
        "upbg-light": "#0d8af6",
        "upbg-dark": "#004c8c",
        "gray-mild": "#f0f1f2",
      },
      outlineWidth: {
        0.5: "0.5px",
        1.5: "1.5px",
      },
      maxWidth: {
        "8xl": "88rem",
        "9xl": "96rem",
        "10xl": "104rem",
        "11xl": "112rem",
      },
      gridTemplateColumns: {
        16: "repeat(16, minmax(0, 1fr))",
      },
      boxShadow: {
        "full-mild": "rgba(0, 0, 0, 0.24) 0px 3px 8px",
      },
      keyframes: {
        "notification-progress": {
          "0%": { width: "100%" },
          "100%": { width: "0%" },
        },
      },
      animation: {
        "notification-progress": "notification-progress 5s linear",
      },
    },
  },

  plugins: [],
};
