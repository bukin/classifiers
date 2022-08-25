<template>
  <div>
    <base-dropdown
      label = "Группы"
      :attributes="{
        label: 'name',
        placeholder: 'Выберите группы',
        clearable: true,
        multiple: true
      }"
      :source="{
        config: axiosConfig,
        transformation: (groupItem) => {
          return serializer.deserialize({data: groupItem});
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
import {endpoints as apiEndpoints} from '~api_classifiers-package_groups/endpoints';

export default {
  name: 'classifiers-package_groups_fields_groups',

  props: {
    value: {
      type: Array,
      default() {
        return [];
      }
    }
  },

  data() {
    return {
      axiosConfig: apiEndpoints.index({
          filter: {
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
