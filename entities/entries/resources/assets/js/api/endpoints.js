export let endpoints = {
    index: function (query) {
        return {
            method: 'get',
            url: route('api.inetstudio.classifiers-package.v1.entries.index', {_query: query || {}}).toString(),
            headers: {
                'Accept': 'application/vnd.api+json'
            }
        };
    },

    show: function (id, query) {
        return {
            method: 'get',
            url: route('api.inetstudio.classifiers-package.v1.entries.show', {entry: id, _query: query || {}}).toString()
        };
    },

    store: function (data, query) {
        return {
            method: 'post',
            url: route('api.inetstudio.classifiers-package.v1.entries.store', {_query: query || {}}).toString(),
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
            url: route('api.inetstudio.classifiers-package.v1.entries.update', {entry: id, _query: query || {}}).toString(),
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
            url: route('api.inetstudio.classifiers-package.v1.entries.destroy', {entry: id}).toString(),
            headers: {
                'Accept': 'application/vnd.api+json'
            }
        };
    },

    groups: {
        show: function (id, query) {
            return {
                method: 'get',
                url: route('api.inetstudio.classifiers-package.v1.entries.groups.show', {entry: id, _query: query || {}}).toString(),
                headers: {
                    'Accept': 'application/vnd.api+json'
                }
            };
        },

        update: function (id) {
            return {
                method: 'patch',
                url: route('api.inetstudio.classifiers-package.v1.entries.groups.update', {entry: id}).toString(),
                headers: {
                    'Accept': 'application/vnd.api+json',
                    'Content-Type': 'application/vnd.api+json'
                }
            };
        },

        attach: function (id) {
            return {
                method: 'post',
                url: route('api.inetstudio.classifiers-package.v1.entries.groups.attach', {entry: id}).toString(),
                headers: {
                    'Accept': 'application/vnd.api+json',
                    'Content-Type': 'application/vnd.api+json'
                }
            };
        },

        detach: function (id) {
            return {
                method: 'delete',
                url: route('api.inetstudio.classifiers-package.v1.entries.groups.detach', {entry: id}).toString(),
                headers: {
                    'Accept': 'application/vnd.api+json'
                }
            };
        }
    }
};
