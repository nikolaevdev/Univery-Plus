<template>
  <div class="module">
    <h5 class="mb-2 text-dark">{{$t('admin')}}</h5>
    <div class="mb-3 d-flex justify-content-between">
      <h2 class="text-dark align-middle mb-0">{{$t('admin_applications')}}</h2>
      <router-link class="" :to="{ name: 'admin.users' }">
        {{$t('admin_back')}}
      </router-link>
    </div>

    <div class="module-block bg-white rounded mb-4">
      <div class="wrap">
        <vue-bootstrap4-table :rows="applications" :columns="columns" :classes="classes" :config="config" @on-change-query="onChangeQuery" :show-loader="showLoader" :actions="actions" :total-rows="total_rows" @on-status="onStatus" @on-delete="onDelete">
          <template slot="id" slot-scope="props">
            <router-link :to="{ name: 'admin.applications.get', params: { id: props.cell_value } }">
              {{props.cell_value}}
            </router-link>
          </template>   
          <template slot="status" slot-scope="props">
            <span class="badge badge-primary" v-if="props.cell_value == 1">
              {{$t('application_status_1')}} 
            </span>
            <span class="badge badge-warning" v-else-if="props.cell_value == 2">
              {{$t('application_status_2')}} 
            </span>
            <span class="badge badge-success" v-else-if="props.cell_value == 3">
              {{$t('application_status_3')}} 
            </span>
            <span class="badge badge-danger" v-else-if="props.cell_value  == 4">
              {{$t('application_status_4')}} 
            </span>
            <span class="badge badge-secondary" v-else>
              {{$t('application_status_no_info')}} 
            </span>
          </template> 
        </vue-bootstrap4-table>
      </div>
   </div>
  </div>
</template>

<script>
import axios from 'axios'
import Form from 'vform'
import { mapGetters } from 'vuex'
import i18n from '~/plugins/i18n'
import Swal from 'sweetalert2'
import { variables } from '~/mixins/variables'
import VueBootstrap4Table from 'vue-bootstrap4-table'

export default {

  components: {
    VueBootstrap4Table
  },

  mixins: [variables],

  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('admin_applications') }
  },

  data() {
	  return {
	    loading: false,
      applications: [],
	    error: null,
      columns: [{
          label: this.$t('admin_applications_list_id'),
          name: "id",
          filter: {
            type: "simple",
            placeholder: "1"
          },
          sort: true,
        },
        {
          label: this.$t('admin_applications_user'),
          name: "user_full",
          sort: true,
          filter: {
            type: "simple",
            placeholder: this.$t('admin_applications_user_placeholder'),
          }
        },
        {
          label: this.$t('admin_applications_name'),
          name: "indexer",
          sort: true,
          filter: {
            type: "simple",
            placeholder: "2021-01-qwerty12"
          },
        },
        {
          label: this.$t('admin_applications_list_status'),
          name: "status",
          filter: {
            type: "select",
            placeholder:  this.$t('admin_applications_list_status_enter'),
            mode: "multi",
            options:[
                  {
                    "name" : this.$t('admin_applications_status_1'),
                    "value" : "1"
                  },
                  {
                    "name" : this.$t('admin_applications_status_2'),
                    "value" : "2"
                  },
                  {
                    "name" : this.$t('admin_applications_status_3'),
                    "value" : "3"
                  },
                  {
                    "name" : this.$t('admin_applications_status_4'),
                    "value" : "4"
                  }
            ]
          },
          sort: true,
        }],
        classes: {
            row : {
                "text-primary" : function(row) {
                    return row.status == 1
                },
                "text-warning" : function(row) {
                    return row.status == 2
                },
                "text-success" : function(row) {
                    return row.status == 3
                },
                "text-danger" : function(row) {
                    return row.status == 4
                }
            }
        },
        actions: [
            {
                btn_text: this.$t('admin_applications_edit_status'),
                event_name: "on-status",
                class: "btn btn-primary",
            },
            {
                btn_text: this.$t('admin_applications_delete'),
                event_name: "on-delete",
                class: "btn btn-danger",
            }
        ],
        config: {
          checkbox_rows: true,
          rows_selectable: true,
          pagination: true, // default true
          pagination_info: true, // default true
          num_of_visibile_pagination_buttons: 7, // default 5
          per_page: 10, // default 10
          per_page_options:  [5,  10,  20,  30],
          server_mode: true, // by default false
          loaderText: this.$t('loading'), // by default 'Loading...'
          show_refresh_button: false, // default is also true
          show_reset_button:  false,
        },
        queryParams: {
          sort: [],
          filters: [],
          global_search: "",
          per_page: 10,
          page: 1,
        },
        total_rows: 0,
        showLoader: true,
	    }
    },

  created() {
    this.fetchData()
  },
  mounted() {
    this.fetchData();

    this.$eventHub.$on('edit_status', function (ids, status) {
       this.edit_status_save(ids, status)
    }.bind(this));

  },
  methods: {
    onChangeQuery(queryParams) {
      this.queryParams = queryParams;
      this.showLoader = true;
      this.fetchData();
    },
    fetchData() {
      let self = this;
      axios.get('/api/admin/applications.get', {
        params: {
          "queryParams": this.queryParams,
          "page": this.queryParams.page
        }
      })
      .then(function(response) {
        self.applications = response.data.data;
        self.total_rows = response.data.total;
        self.showLoader = false;
      })
      .catch(function(error) {
        self.showLoader = false;
        console.log(error);
       });
    },
    onStatus(payload) {

      if(payload.selectedItems.length > 0){

        let select_items = [];

        payload.selectedItems.forEach(function(item){
          select_items.push(item.id);
        });

        let self = this;

        this.$modal.show(
        {
          template: `
              <div>
                <div class="modal-header modal-bg">
                  <h5>{{$t('admin_applications_edit_status_form')}}</h5>
                </div>
                <div class="modal-body">
                  <form>
                    <div class="form-group">
                      <label for="admin_edit_status">{{$t('admin_applications_status')}}</label>
                      <select name="select" class="form-control" id="admin_edit_status" v-model="status"> 
                        <option value="1">{{$t('admin_applications_status_1')}}</option>
                        <option value="2">{{$t('admin_applications_status_2')}}</option>
                        <option value="3">{{$t('admin_applications_status_3')}}</option>
                        <option value="4">{{$t('admin_applications_status_4')}}</option>
                      </select>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <v-button type="outline-primary" nativeType="button" v-on:click.native="save_edit(ids_lists)">{{$t('admin_edit')}}</v-button>
                  <v-button type="outline-primary" nativeType="button" v-on:click.native="$emit('close')">{{$t('admin_close')}}</v-button>
                </div>
              </div>
            `,
            data() {
              return {
                status: '',
                ids_lists: this.ids,
              }
            },
            props: {
              ids: Array
            },
            methods:{
              save_edit(ids){
                if(!ids && ids <  0)
                  return false;
                this.$eventHub.$emit('edit_status',  this.ids_lists, this.status);
                this.$emit('close');

              }
            }
          },
          {
            ids: select_items,
          },
          { height: 'auto' }
        )
      }

    },
    edit_status_save(ids, status) {
      if((!status && status <  0) || !ids)
        return false;

        let self = this;

        axios.post('/api/admin/applications.status.edit', {
          ids: ids,
          status: status,
          headers: {
            'Content-Type': 'application/json'
          }
        }).then(response => {
          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
  
          if (response.data.ok) {

            self.applications.forEach(function(item, index, array) {
              if(ids.indexOf( item.id ) !== -1){
                self.applications[index].status = status;
              }
            });

            Swal.fire({
              title: self.$t('swal_title_success'),
              showConfirmButton: false,
              timer: 1500
            });
          }
        })
        .catch(error => {
          Swal.showValidationMessage(
            i18n.t('swal_error_message')
          )
        })
    },
    onDelete(payload) {

      if(payload.selectedItems.length > 0){

        let select_items = [];

        payload.selectedItems.forEach(function(item){
          select_items.push(item.id);
        });

        let self = this;

        Swal.fire({
          title: self.$t('admin_warning2'),
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: self.$t('swal_delete_btn'),
          cancelButtonText: self.$t('swal_cancel_btn')

        }).then((result) => {

          if (result.value) {

            axios.post('/api/admin/applications.delete', {
            ids: select_items,
            headers: {
              'Content-Type': 'application/json'
            }

          }).then((response) => {

              Swal.fire({
                icon: 'success',
                title: self.$t('swal_title_success'),
                showConfirmButton: false,
                timer: 1500
              });
              self.applications.forEach(function(item, index, array) {
                if(select_items.indexOf( item.id ) !== -1) 
                  self.applications.splice(index, 1);

              });

            })
            .catch(function (error) {
              console.log(error);
            }); 
          }
        });

      }
    }
  }
}
</script>
