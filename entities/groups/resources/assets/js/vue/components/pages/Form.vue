<template>
  <div class="wrapper wrapper-content">
    <div class="ibox">
      <div class="ibox-title">
        <router-link :to="{name: 'inetstudio.classifiers-package.groups.back.resource.index'}" class="btn btn-sm btn-white">
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
            <div class="panel-group float-e-margins" id="mainAccordion">
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
                      <base-input-text
                        label="Название"
                        name="name"
                        :value.sync="item.model.name"
                        :errors="page.errors"
                        error-field="name"
                      />

                      <base-input-text
                        label="Алиас"
                        name="alias"
                        :value.sync="item.model.alias"
                        :errors="page.errors"
                        error-field="alias"
                      />

                      <base-wysiwyg
                        label="Описание"
                        name="description"
                        :value.sync="item.model.description"
                        :attributes="{
                          'id': 'description',
                          'cols': '50',
                          'rows': '10',
                        }"
                        :errors="page.errors"
                        error-field="description"
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
          back="inetstudio.classifiers-package.groups.back.resource.index"
          v-on:save="submit"
        ></vue-block-buttons>
      </div>
    </div>
  </div>
</template>

<script>
import itemMixin from '~admin-panel/mixins/item';
import stateMixin from '~admin-panel/mixins/state';

import {endpoints as apiEndpoints} from '~api_classifiers-package_groups/endpoints';
import {item as groupModel} from '~models_classifiers-package_groups/group';

export default {
  name: 'classifiers-package_groups_pages_form',

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
        component.setModel(groupModel);
        component.stopLoading();
        component.ready();
      });

      return;
    }

    axios(apiEndpoints.show(to.params.id))
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

      axios(apiEndpoints.store(component.getJsonApiData()))
        .then(response => {
          component.stopLoading();

          if (response.status === 201) {
            component.page.alert = {
              show: true,
              type: 'success',
              message: 'Группа успешно добавлена',
            };

            component.$router.push({
              name: 'inetstudio.classifiers-package.groups.back.resource.edit',
              params: { id: component.item.model.id }
            });
          }
        })
        .catch(error => {
          component.stopLoading();

          component.page.errors = error.response.data.errors;
          component.page.alert = {
            show: true,
            type: 'danger',
            message: 'При сохранении произошли ошибки.',
          };
        });
    },

    updateGroup() {
      let component = this;

      component.startRequest();

      axios(apiEndpoints.update(component.item.model.id, component.getJsonApiData()))
        .then(response => {
          component.stopLoading();

          if (response.status === 200) {
            component.page.alert = {
              show: true,
              type: 'success',
              message: 'Группа успешно обновлена',
            };
          }
        })
        .catch(error => {
          component.stopLoading();

          component.page.errors = error.response.data.errors;
          component.page.alert = {
            show: true,
            type: 'danger',
            message: 'При сохранении произошли ошибки.',
          };
        });
    },

    startRequest() {
      let component = this;

      component.startLoading();

      component.page.errors = {};
      component.page.alert = {
        show: false
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
