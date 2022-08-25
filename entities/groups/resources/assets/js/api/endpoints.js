export let endpoints = {
    index: function (query) {
        return {
            method: 'get',
            url: route('api.inetstudio.classifiers-package.v1.groups.index', {_query: query || {}}).toString(),
            headers: {
                'Accept': 'application/vnd.api+json'
            }
        };
    },

    show: function (id, query) {
        return {
            method: 'get',
            url: route('api.inetstudio.classifiers-package.v1.groups.show', {group: id, _query: query || {}}).toString()
        };
    },

    store: function (data, query) {
        return {
            method: 'post',
            url: route('api.inetstudio.classifiers-package.v1.groups.store', {_query: query || {}}).toString(),
            data: data,
            headers: {
                'Accept': 'application/vnd.api+json',
                'Content-Type': 'application/vnd.api+json'
            }
        };
    },

    update: function (id, data, query) {
        return {
            method: 'patch',
            url: route('api.inetstudio.classifiers-package.v1.groups.update', {group: id, _query: query || {}}).toString(),
            data: data,
            headers: {
                'Accept': 'application/vnd.api+json',
                'Content-Type': 'application/vnd.api+json'
            }
        };
    },

    destroy: function (id) {
        return {
            method: 'delete',
            url: route('api.inetstudio.classifiers-package.v1.groups.destroy', {group: id}).toString(),
            headers: {
                'Accept': 'application/vnd.api+json'
            }
        };
    },

    entries: {
        show: function (id, query) {
            return {
                method: 'get',
                url: route('api.inetstudio.classifiers-package.v1.groups.entries.show', {group: id, _query: query || {}}).toString(),
                headers: {
                    'Accept': 'application/vnd.api+json'
                }
            };
        },

        update: function (id) {
            return {
                method: 'patch',
                url: route('api.inetstudio.classifiers-package.v1.groups.entries.update', {group: id}).toString(),
                headers: {
                    'Accept': 'application/vnd.api+json',
                    'Content-Type': 'application/vnd.api+json'
                }
            };
        },

        attach: function (id) {
            return {
                method: 'post',
                url: route('api.inetstudio.classifiers-package.v1.groups.entries.attach', {group: id}).toString(),
                headers: {
                    'Accept': 'application/vnd.api+json',
                    'Content-Type': 'application/vnd.api+json'
                }
            };
        },

        detach: function (id) {
          return {
              method: 'delete',
              url: route('api.inetstudio.classifiers-package.v1.groups.entries.detach', {group: id}).toString(),
              headers: {
                  'Accept': 'application/vnd.api+json'
              }
          };
        }
    }
};
