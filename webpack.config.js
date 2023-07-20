const path = require('path');

module.exports = {
  mode: 'production',
  entry: {
    dashboard: './public/js/dashboard.js',
    confirmDelete: './public/js/confirmDelete.js',
    toggleEdit: './public/js/toggleEdit.js',
  },
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: '[name].bundle.js',
  },
};