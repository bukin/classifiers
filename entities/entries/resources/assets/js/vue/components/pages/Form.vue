<template>
  <div class="wrapper wrapper-content">
    <div class="ibox">
      <div class="ibox-title">
        <router-link :to="{name: 'inetstudio.classifiers-package.entries.back.resource.index'}" class="btn btn-sm btn-white">
          <i class="fa fa-arrow-left"></i> Вернуться назад
        </router-link>
      </div>
    </div>

    <vue-block-alert
      :alert="page.alert"
      v-show="page.alert.show"
    ></vue-block-alert>

    <div class="ibox">
      <div class="ibox-content" :class="{'sk-loading': isLoading}">
        <div class="sk-spinner sk-spinner-double-bounce">
          <div class="sk-double-bounce1"></div>
          <div class="sk-double-bounce2"></div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="panel-entry float-e-margins" id="mainAccordion">
              <template v-if="isReady">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h5 class="panel-title">
                      <a data-toggle="collapse" data-parent="#mainAccordion" href="#collapseMain" aria-expanded="true">Основная
                        информация</a>
                    </h5>
                  </div>
                  <div id="collapseMain" class="collapse show" aria-expanded="true">
                    <div class="panel-body">
                      <classifiers-package_groups_fields_groups
                        :value="item.model.groups"
                        :errors="page.errors"
                        v-on:update:value="updateField('groups', $event)"
                      />

                      <base-input-text
                        label="Значение"
                        name="value"
                        :value.sync="item.model.value"
                        :errors="page.errors"
                        error-field="value"
                      />

                      <base-input-text
                        label="Алиас"
                        name="alias"
                        :value.sync="item.model.alias"
                        :errors="page.errors"
                        error-field="alias"
                      />

                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
      <div class="ibox-footer">
        <vue-block-buttons
          back="inetstudio.classifiers-package.entries.back.resource.index"
          v-on:save="submit"
        ></vue-block-buttons>
      </div>
    </div>
  </div>
</template>

<script>
import itemMixin from '~admin-panel/mixins/item';
import stateMixin from '~admin-panel/mixins/state';

import {endpoints as apiEndpoints} from '~api_classifiers-package_entries/endpoints';
import {item as entryModel} from '~models_classifiers-package_entries/entry';

export default {
  name: 'classifiers-package_entries_pages_form',

  props: {
    id: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      page: {
        alert: {
          show: false,
          type: '',
          message: ''
        },
        errors: {}
      }
    };
  },

  beforeRouteEnter (to, from, next) {
    if (! to.params.id) {
      next(function(component) {
        component.setModel(entryModel);
        component.stopLoading();
        component.ready();
      });

      return;
    }

    axios(apiEndpoints.show(to.params.id, {include: 'groups'}))
      .then(response => {
        next(function(component) {
          component.setModel(response.data, true);
          component.stopLoading();
          component.ready();
        });
      });
  },

  methods: {
    updateField(field, payload) {
      let component = this;

      let data = _.cloneDeep(payload);

      component.$set(component.item.model, field, data);
    },

    submit() {
      let component = this;

      ! component.id ? component.storeGroup() : component.updateGroup();
    },

    storeGroup() {
      let component = this;

      component.startRequest();

      axios(apiEndpoints.store(component.getJsonApiData(['groups'])))
        .then(response => {
          component.stopLoading();

          if (response.status === 201) {
            component.page.alert = {
              show: true,
              type: 'success',
              message: 'Значение успешно добавлено',
            };

            component.$router.push({
              name: 'inetstudio.classifiers-package.entries.back.resource.edit',
              params: { id: component.item.model.id }
            });
          }
        })
        .catch(error => {
          component.errorRequest();
        });
    },

    updateGroup() {
      let component = this;

      component.startRequest();

      axios(apiEndpoints.update(component.item.model.id, component.getJsonApiData(['groups'])))
        .then(response => {
          component.stopLoading();

          if (response.status === 200) {
            component.page.alert = {
              show: true,
              type: 'success',
              message: 'Значение успешно обновлено',
            };
          }
        })
        .catch(error => {
          component.errorRequest();
        });
    },

    startRequest() {
      let component = this;

      component.startLoading();

      component.page.errors = {};
      component.page.alert = {
        show: false
      };
    },

    errorRequest() {
      let component = this;

      component.stopLoading();

      component.page.errors = error.response.data.errors;
      component.page.alert = {
        show: true,
        type: 'danger',
        message: 'При сохранении произошли ошибки.',
      };
    }
  },

  mixins: [
    itemMixin,
    stateMixin
  ]
};
</script>

<style scoped>
</style>
