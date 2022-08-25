<template>
  <div>
    <base-dropdown
      :label = "label"
      :attributes="preparedAttributes"
      :source="{
        config: axiosConfig,
        transformation: (classifierItem) => {
          return serializer.deserialize({data: classifierItem});
        }
      }"
      :selected="value"
      :errors="errors"
      error-field="groups"
      v-on:update:selected="select($event)"
    />
  </div>
</template>

<script>
import Jsona from 'jsona';
import {endpoints as apiEndpoints} from '~api_classifiers-package_entries/endpoints';

export default {
  name: 'classifiers-package_entries_fields_classifiers',

  props: {
    label: {
      type: String,
      default: ''
    },
    name: {
      type: String,
      required: true
    },
    value: {
      type: Array,
      default() {
        return [];
      }
    },
    group: {
      type: String,
      required: true
    },
    attributes: {
      type: Object,
      default() {
        return {};
      }
    }
  },

  data() {
    let component = this;

    return {
      preparedAttributes: _.merge({label: 'value'}, component.attributes),
      axiosConfig: apiEndpoints.index({
          filter: {
            group: component.group,
            suggestion: ''
          }
      }),
      serializer: new Jsona()
    };
  },

  methods: {
    select(payload) {
      let component = this;

      component.$emit('update:value', payload);
    }
  },

  mixins: [
    window.Admin.vue.mixins['errors']
  ]
};
</script>

<style scoped>

</style>
