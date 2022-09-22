<template>
  <div class="module">

    <div class="mb-3 d-flex justify-content-between">
      <h5 class="text-dark align-middle mb-0">{{$t('admin_users')}}</h5>
      <v-button :type="'outline-primary'" :class="{'btn-sm':true}" v-on:click.native="add_user_modal()" >{{ $t('admin_users_add') }}</v-button>
    </div>

    <div class="module-block bg-white rounded mb-4">
      <div class="wrap" v-if="users != null && !users.length < 1 && !error && !loading">
        <ul class="menu-list-module list-group list-group-flush" v-for="(user, index) in users" :key="user.id">
          <li class="list-group-item p-3 d-flex d-flex align-items-center">
            <span class="flex-grow-1">{{ user.name }}</span>
            <div class="btn-group rounded" role="group" aria-label="">
              <v-button type="outline-primary" nativeType="button" class="btn-sm" v-on:click.native="edit_user_modal(index, user.id)">{{$t('admin_edit')}}</v-button>
              <v-button type="outline-danger" nativeType="button" class="btn-sm" v-on:click.native="delete_user(index, user.id)">{{$t('admin_delete')}}</v-button>
            </div>
          </li>
        </ul>
        <div class="d-flex justify-content-center mt-3">
          <paginate
            :page-count="this.page_count"
            :page-range="this.page_range"
            :margin-pages="2"
            :click-handler="fetchData"
            :prev-text="this.$t('admin_paginate_prev')"
            :next-text="this.$t('admin_paginate_next')"
            :container-class="'pagination'"
            :page-link-class="'page-link'"
            :page-class="'page-item'"
            :prev-class="'page-item'"
            :next-class="'page-item'"
            :prev-link-class="'page-link'"
            :next-link-class="'page-link'"
            >
          </paginate>
        </div>
      </div>
      <div class="p-4" v-if="(!users && !error) || error || loading">
        <div class="p-3 text-center" v-if="!users && !error && !loading">{{ $t('not_info') }}</div>
          <div class="p-3 text-center text-danger" v-if="error ">{{ $t('error_info') }}</div>
          <div class="spinner-grow text-primary p-3" role="status" v-if="loading">
            <span class="sr-only">{{ $t('loading_info') }}</span>
          </div>
      </div>
   </div>
   <notifications group="settings"
        class="notifications"
        position="bottom right"
        :max="3"
        :width="400"/>
  </div>
</template>

<script>
import axios from 'axios'
import Form from 'vform'
import { mapGetters } from 'vuex'
import i18n from '~/plugins/i18n'
import Swal from 'sweetalert2'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('admin_user') }
  },

  data() {
	  return {
	    loading: false,
      roles: null,
	    users: null,
	    users_count: 0,
	    error: null,
      page_range: 15,
      page_count: 0
	  }
  },

  created() {
    this.fetchData()
  },
  watch: {
    $route: 'fetchData',
    users: function () {
      this.page_count =  Math.ceil(this.users_count / this.page_range);
    }
  },
  mounted() {
    this.$eventHub.$on('save_user', function (form) {
       this.save_user_form(form);
    }.bind(this));
    
    this.$eventHub.$on('add_user', function (form) {
       this.add_user_form(form);
    }.bind(this));

  },
  methods: {
    fetchData(p = 1) {
      this.error = this.users = null
      this.users_count = 0;
      this.loading = true

      axios
        .get(`/api/admin/users.get?page=${p}`)
        .then(response => {
            this.users = response.data.data;
            this.users_count = response.data.total;
        }) 
	    .catch(error => {
	        console.log(error);
	        this.error = true;
	    })
	    .finally(() => (this.loading = false));

       axios
        .get('/api/admin/access.role.getlist')
        .then(response => {
            this.roles = response.data;
        })
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));
    },
    add_user_modal(){
      this.$modal.show(
        {
          template: `
            <div>
              <div class="modal-header modal-bg">
                <h5>{{$t('admin_users_add')}}</h5>
              </div>
              <div class="modal-body">
                <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="errors.length">
                  <strong>{{$t('admin_errors_form1')}}</strong>
                  <ul>
                    <li v-for="error in errors">{{ error }}</li>
                  </ul>
                </div>
                <form>
                  <div class="form-group">
                    <label for="admin_users_add_name">{{$t('admin_users_add_name')}}</label>
                    <input type="text" class="form-control" id="admin_users_add_name" placeholder="" v-model="name">
                  </div>
                  <div class="form-group">
                    <label for="admin_users_add_email">{{$t('admin_users_add_email')}}</label>
                    <input type="text" class="form-control" id="admin_users_add_email" placeholder="" v-model="email">
                  </div>
                  <div class="form-group">
                    <label for="admin_add_name_role">{{$t('admin_users_add_password')}}</label>
                    <input type="password" class="form-control" id="admin_add_name_role" placeholder="" v-model="password">
                  </div>
                  <div class="form-group">
                    <label for="admin_users_add_password">{{$t('admin_users_add_password_confirmation')}}</label>
                    <input type="password" class="form-control" id="admin_users_add_password_confirmation" placeholder="" v-model="password_confirmation">
                  </div>
                  <label>{{$t('admin_users_add_role')}}</label>
                  <div class="roles-choice modal-bg p-3">
                    <ul class="menu-list-module list-group list-group-flush" v-for="role in roles" :key="role.role_id">
                      <li class="list-group-item p-3 d-flex d-flex align-items-center">
                        <span class="flex-grow-1">{{ role.role_name }}</span>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" :id="checkbox_role_id(role.role_id)" :value="role.role_name" v-model="checked_roles" @change="$emit('change', checked_roles)">
                          <label class="custom-control-label" :for="checkbox_role_id(role.role_id)"></label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <v-button type="outline-primary" nativeType="button" v-on:click.native="add_user()">{{$t('admin_users_add')}}</v-button>
                <v-button type="outline-primary" nativeType="button" v-on:click.native="$emit('close')">{{$t('admin_close')}}</v-button>
              </div>
            </div>
          `,

          data() {
            return {
              errors: [],
              name: '',
              email: '',
              password: '',
              password_confirmation: '',
              checked_roles: []
            }
          },
          props: {
            permissions: Array,
            roles: Array,
          },
          created() {
            this.errors = [];
          },
          methods:{
            checkbox_permission_id(id){
              return 'permissions-add-select-' + id
            },
            checkbox_role_id(id){
              return 'role-add-select-' + id
            },
            add_user(){

              this.errors = [];

              if (this.name && this.email && this.password && this.password_confirmation) {
                this.$eventHub.$emit('add_user', { name: this.name, email: this.email, password: this.password, password_confirmation: this.password_confirmation, checked_roles:this.checked_roles});
                this.$emit('close');
              }

              if (!this.name) {
                this.errors.push(i18n.t('admin_e_message_no_name'));
              }

              if (!this.email) {
                this.errors.push(i18n.t('admin_e_message_no_email'));
              }

              if (!this.password) {
                this.errors.push(i18n.t('admin_e_message_no_password'));
              }

              if (!this.password_confirmation) {
                this.errors.push(i18n.t('admin_e_message_no_password_confirmation'));
              }
          
            }
          },

        },
        {
          permissions: this.permissions,
          roles: this.roles,
        },
        { 
          classes: 'my-3',
          scrollable: true,
          draggable: false,
          height: 'auto',

         }
      )

    },
    delete_user (index, id){
      if(!id || !index || (!id && !index))
        return false;

        Swal.fire({
          title: this.$t('admin_warning2'),
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: this.$t('swal_delete_btn'),
          cancelButtonText: this.$t('swal_cancel_btn')
        }).then((result) => {
          if (result.value) {
            axios.post('/api/admin/users.delete', {
              id: id,
              headers: {
                'Content-Type': 'application/json'
              }
            }).then((response) => {
              Swal.fire({
                icon: 'success',
                title: this.$t('swal_title_success'),
                showConfirmButton: false,
                timer: 1500
              });
             this.$delete(this.users, index);
            })
            .catch(function (error) {
              console.log(error);
            }); 
          }
        });
    },
    add_user_form(form) {

      if(!form)
        return false;

      axios.post('/api/admin/users.add', {

          name: form.name,
          email: form.email,
          password: form.password,
          password_confirmation: form.password_confirmation,
          checked_roles: form.checked_roles,
          headers: {
            'Content-Type': 'application/json'
          }

      }).then(response => {

          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
  
          if (response.data.ok) {

            this.users.push(response.data.user);
            this.users_count++;

            this.$notify({
              group: 'settings',
              type: 'success',
              title: i18n.t('admin_save_message'),
              text:  i18n.t('admin_user_add_message')
            });
          }
        })
        .catch(error => {
          Swal.showValidationMessage(
            i18n.t('swal_error_message')
          )
        })
    },
    save_user(form) {
      if(!form)
        return false;

      axios.post('/api/admin/users.save', {

          name: form.name,
          email: form.email,
          password: form.password,
          password_confirmation: form.password_confirmation,
          checked_roles: form.checked_roles,
          headers: {
            'Content-Type': 'application/json'
          }

      }).then(response => {

          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
  
          if (response.data.ok) {

            this.users.push(response.data.user);

            this.$notify({
              group: 'settings',
              type: 'success',
              title: i18n.t('admin_save_message'),
              text:  i18n.t('admin_user_add_message')
            });
          }
        })
        .catch(error => {
          Swal.showValidationMessage(
            i18n.t('swal_error_message')
          )
        })
    },
    edit_user_modal(index, id){
      if((!index && index <  0) || !id)
        return false;

        let roles_selects = [];
        for (let i = 0; i < this.users[index].user_roles.length; i++) { 
          roles_selects.unshift(this.users[index].user_roles[i]);
        } 

        this.$modal.show(
        {
          template: `
            <div>
              <div class="modal-header modal-bg">
                <h5>{{$t('admin_users_edit')}}</h5>
              </div>
              <div class="modal-body">
                 <div class="alert alert-warning alert-dismissible fade show" role="alert" v-if="errors.length">
                  <strong>{{$t('admin_errors_form1')}}</strong>
                  <ul>
                    <li v-for="error in errors">{{ error }}</li>
                  </ul>
                </div>
                <form>
                  <div class="form-group">
                    <label for="admin_users_edit_name">{{$t('admin_users_edit_name')}}</label>
                    <input type="text" class="form-control" id="admin_users_add_name" placeholder="" v-model="name">
                  </div>
                  <div class="form-group">
                    <label for="admin_users_edit_email">{{$t('admin_users_edit_email')}}</label>
                    <input type="text" class="form-control" id="admin_users_add_email" placeholder="" v-model="email">
                  </div>
                  <div class="form-group">
                    <label for="admin_add_edit_role">{{$t('admin_users_edit_password')}}</label>
                    <input type="password" class="form-control" id="admin_add_name_role" placeholder="" v-model="password">
                  </div>
                  <div class="form-group">
                    <label for="admin_users_edit_password">{{$t('admin_users_edit_password_confirmation')}}</label>
                    <input type="password" class="form-control" id="admin_users_edit_password_confirmation" placeholder="" v-model="password_confirmation">
                  </div>
                  <label>{{$t('admin_users_edit_role')}}</label>
                  <div class="roles-choice modal-bg p-3">
                    <ul class="menu-list-module list-group list-group-flush" v-for="role in edit_roles" :key="role.role_id">
                      <li class="list-group-item p-3 d-flex d-flex align-items-center">
                        <span class="flex-grow-1">{{ role.role_name }}</span>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" :id="checkbox_id(role.role_id)" :value="role.role_name" v-model="checked" @change="$emit('change_edit', checked)">
                          <label class="custom-control-label" :for="checkbox_id(role.role_id)"></label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <v-button type="outline-primary" nativeType="button" v-on:click.native="save_user(edit_index_r, edit_user_id)">{{$t('admin_edit')}}</v-button>
                <v-button type="outline-primary" nativeType="button" v-on:click.native="$emit('close')">{{$t('admin_close')}}</v-button>
              </div>
            </div>
          `,
          data() {
            return {
              errors: [],
              checked: this.edit_initialChecked,
              name: this.edit_name,
              email: this.edit_email,
              password: '',
              password_confirmation: ''
            }
          },
          model: {
            prop: 'edit_initialChecked',
            event: 'change_edit'
          },
          props: {
            edit_initialChecked: Array,
            edit_roles: Array,
            edit_name: String,
            edit_email: String,
            edit_user_id: Number,
            edit_index_r: Number
          },
          methods:{
            checkbox_id(id){
              return 'roles-edit-select-' + id
            },
            save_user(index, id){
              if((!index && index <  0) || !id)
                return false;

              this.errors = [];

              if (this.name && this.email) {
                this.$eventHub.$emit('save_user', { index: index, user_id: id, name: this.name, email: this.email, password: this.password, password_confirmation: this.password_confirmation, checked_roles:this.checked});
                this.$emit('close');
              }

              if (!this.name) {
                this.errors.push(i18n.t('admin_e_message_no_name'));
              }

              if (!this.email) {
                this.errors.push(i18n.t('admin_e_message_no_email'));
              }

            }
          }
        },
        {
          edit_initialChecked: roles_selects,
          edit_roles: this.roles,
          edit_name: this.users[index].name,
          edit_email: this.users[index].email,
          edit_user_id: this.users[index].id,
          edit_index_r: index
        },
        { 
          classes: 'my-3',
          scrollable: true,
          draggable: false,
          height: 'auto',
         }
      )
    },
    save_user_form(form) {

      if(!form)
        return false;

        axios.post('/api/admin/users.save', {
          user_id: form.user_id,
          name: form.name,
          email: form.email,
          password: form.password,
          password_confirmation: form.password_confirmation,
          checked_roles: form.checked_roles,
          headers: {
            'Content-Type': 'application/json'
          }

        }).then(response => {

          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
  
          if (response.data.ok) {

            this.users[form.index].name = form.name;
            this.users[form.index].email = form.email;
            this.users[form.index].user_roles = form.checked_roles;

            this.$notify({
              group: 'settings',
              type: 'success',
              title: i18n.t('admin_save_message'),
              text:  i18n.t('admin_save_message_text')
            });
          }
        })
        .catch(error => {
          Swal.showValidationMessage(
            i18n.t('swal_error_message')
          )
        })
    },
  }
}
</script>
