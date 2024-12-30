module.exports = {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './node_modules/flowbite/**/*.js'
    ],
    theme: {
        extend: {
            transitionProperty: {
                'width': 'width',
                'spacing': 'margin, padding',
            }
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
};