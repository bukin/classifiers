<template>
  <div class="wrapper wrapper-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="ibox float-e-margins">
          <div class="ibox-title">
            <router-link :to="{name: 'inetstudio.classifiers-package.entries.back.resource.create'}" class="btn btn-sm btn-primary btn-lg">Добавить</router-link>
          </div>
          <div class="ibox-content" v-bind:class="{'sk-loading': isLoading}">
            <div class="sk-spinner sk-spinner-double-bounce">
              <div class="sk-double-bounce1"></div>
              <div class="sk-double-bounce2"></div>
            </div>
            <template v-if="isReady">
              <datatable
                  :attributesProp="datatable.attributes"
                  :optionsProp="datatable.options"
                  :eventsProp="datatable.events"
                  ref="table"
              ></datatable>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import stateMixin from '~admin-panel/mixins/state';

export default {
    name: 'classifiers-package_entries_pages_index',
    components: {
      'datatable': () => import('~admin-panel/components/blocks/datatables/Datatable')
    },
    data() {
      return {
        datatable: {
          attributes: {
            id: 'classifiers-package_entries_table'
          },
          options: {},
          events: {}
        }
      }
    },
    methods: {
      updateTable() {
        let component = this;

        component.$refs.table.update();
      }
    },
    created: function() {
      let component = this;

      const url = route('inetstudio.classifiers-package.entries.back.tables.index.table');

      axios.get(url, {})
        .then(response => {
          if (! response.data.errors) {
            component.datatable.options = _.merge(component.datatable.options, response.data.meta.options);

            component.stopLoading();
            component.ready();
          }
        });
    },
    mixins: [
      stateMixin
    ]
  };
</script>

<style scoped>
</style>
