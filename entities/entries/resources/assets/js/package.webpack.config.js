const path = require('path');

module.exports = {
  resolve: {
    alias: {
      '~api_classifiers-package_entries': path.resolve(__dirname, 'api/'),
      '~models_classifiers-package_entries': path.resolve(__dirname, 'models/'),
      '~vue_classifiers-package_entries': path.resolve(__dirname, 'vue/')
    }
  }
};
