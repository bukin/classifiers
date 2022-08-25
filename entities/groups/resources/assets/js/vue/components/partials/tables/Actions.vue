<template>
  <div class="btn-nowrap">
    <router-link :to="{name: 'inetstudio.classifiers-package.groups.back.resource.edit', params: {id: itemProp.id}}" class="btn btn-xs btn-default m-r-xs">
      <i class="fa fa-pencil-alt"></i>
    </router-link>
    <a href="#" class="btn btn-xs btn-danger delete" @click.prevent="destroy">
      <i class="fa fa-times"></i>
    </a>
  </div>
</template>

<script>

import {endpoints as apiEndpoints} from '~api_classifiers-package_groups/endpoints';
import Swal from 'sweetalert2';

export default {
  name: 'classifiers-package_groups_partials_tables_actions',
  props: {
    itemProp: {
      type: Object,
      required: true
    }
  },
  methods: {
    destroy() {
      let component = this;

      Swal.fire({
        title: "Вы уверены?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Отмена",
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Да, удалить"
      }).then((result) => {
        if (result.value) {
          axios(apiEndpoints.destroy(component.itemProp.id))
              .then(response => {
                if (response.status === 204) {
                  component.$emit('component-event', {
                    event: 'itemDestroyed',
                    data: {
                      item: component.itemProp
                    }
                  });

                  Swal.fire({
                    title: "Запись удалена",
                    icon: "success"
                  });

                  return;
                } else if (response.status === 404) {
                  component.alertError('Запись не найдена');

                  return;
                }

                component.alertError('При удалении произошла ошибка');
              })
              .catch(error => {
                component.alertError('При удалении произошла ошибка');
              });
        }
      });
    },
    alertError(title) {
      Swal.fire({
        title: "Ошибка",
        text: title,
        icon: "error"
      });
    }
  }
};
</script>

<style scoped>

</style>
