<template>
  <div class="module">
  	<div class="mb-3 d-flex justify-content-between">
  		<h5 class="text-dark align-middle mb-0">{{$t('admin_access_right_permissions')}}</h5>
  		<v-button :type="'outline-primary'" :class="{'btn-sm':true}" v-on:click.native="add_permission()">{{ $t('admin_access_right_permissions_add') }}</v-button>
  	</div>

  	<div class="module-block bg-white rounded mb-4">
  		<div class="wrap" v-if="permissions != null && !permissions.length < 1 && !error && !loading">
        <div class="p-3 d-flex d-flex align-items-center" v-if="show_permission_add_Field()">
            <form class="list-group-item-edit form-inline flex-grow-1">
              <div class="form-group mr-2">
                <input type="text" class="form-control form-control-sm" v-model="addFieldValue">
              </div>
            </form>
            <div class="btn-group rounded" role="group" aria-label="">
              <v-button type="primary" nativeType="button" class="btn-sm" v-on:click.native="save_add_permission_Field()">{{$t('admin_add')}}</v-button>
              <v-button type="primary" nativeType="button" class="btn-sm" v-on:click.native="add_permission_close()">{{$t('admin_close')}}</v-button>
            </div>
        </div>
  			<ul class="menu-list-module list-group list-group-flush" v-for="(permission, index) in permissions" :key="permission.id">
          <li class="list-group-item p-3 d-flex d-flex align-items-center">
            <span class="flex-grow-1" v-show="!show_permission_Field(index)" @click="focus_permission_Field(index, permission)">{{ permission.name }}</span>
            <form class="list-group-item-edit form-inline flex-grow-1" v-show="show_permission_Field(index)" @focus="focus_permission_Field(index, permission)" @blur="blur_permission_Field">
              <div class="form-group mr-2">
                <input type="text" class="form-control form-control-sm" v-model="editFieldValue">
              </div>
              <v-button type="primary" nativeType="button" class="btn-sm" v-on:click.native="save_edit_permission_Field(index, permission.id)">{{$t('admin_save')}}</v-button>
            </form>
            <div class="btn-group rounded" role="group" aria-label="">
              <v-button type="outline-danger" nativeType="button" class="btn-sm" v-on:click.native="delete_f(index, permission.id, 1)">{{$t('admin_delete')}}</v-button>
  					</div>
          </li>
        </ul>
  		</div>
  		<div class="p-4" v-if="(!permissions && !error) || error || loading">
            <div class="p-3 text-center" v-if="!permissions && !error && !loading">{{ $t('not_info') }}</div>
	        <div class="p-3 text-center text-danger" v-if="error ">{{ $t('error_info') }}</div>
	        <div class="spinner-grow text-primary p-3" role="status" v-if="loading">
			    <span class="sr-only">{{ $t('loading_info') }}</span>
			</div>
  		</div>
   </div>

  	<div class="mb-3 d-flex justify-content-between">
  		<h5 class="text-dark align-middle mb-0">{{$t('admin_access_right_roles')}}</h5>
  		<v-button :type="'outline-primary'" :class="{'btn-sm':true}" v-on:click.native="add_role()">{{ $t('admin_access_right_roles_add') }}</v-button>
  	</div>

  	<div class="module-block bg-white rounded mb-3">
        <div class="wrap" v-if="roles != null && !roles.length < 1  && !error">
        	<ul class="menu-list-module list-group list-group-flush" v-for="(role, index) in roles" :key="role.role_id">

            <li class="list-group-item p-3 d-flex d-flex align-items-center">
              <div class="flex-grow-1">
                <h5>{{ role.role_name }}</h5>
                <div class="d-flex flex-wrap flex-row mt-3">
                  <span class="badge badge-dark mb-2 mr-2 font-weight-normal" v-for="permission in role.role_permissions" :key="permission.permissions_id">{{ permission.permissions_name }}</span>
                </div>
              </div>
              <div class="btn-group rounded ml-2" role="group" aria-label="">
        				<v-button type="outline-primary" nativeType="button" class="btn-sm" v-on:click.native="edit_box_role(index, role.role_id)">{{$t('admin_edit')}}</v-button>
        				<v-button type="outline-danger" nativeType="button" class="btn-sm" v-on:click.native="delete_f(index, role.role_id, 2)">{{$t('admin_delete')}}</v-button>
        			</div>
            </li>
          </ul>
  		</div>
  		<div class="p-4" v-if="(!roles && !error) || error || loading">
          <div class="p-3 text-center" v-if="!roles && !error && !loading">{{ $t('not_info') }}</div>
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
import { mapGetters } from 'vuex'
import i18n from '~/plugins/i18n'
import Swal from 'sweetalert2'

export default {
    scrollToTop: false,

    metaInfo () {
      return { title: this.$t('admin_access_right') }
    },

    data() {
      return {
          loading: true,
          permissions: null,
          roles: null,
          error: null,
          editField : '',
          editFieldValue : '',
          editFieldFocus: false,
          addFieldValue : '',
          addFieldFocus: false,
          editrolePermissionsSelects : [],
      }
    },

  created() {
    this.fetchData()
  },
  watch: {
    $route: 'fetchData'
  },
  mounted() {
    this.$eventHub.$on('save_edit_role', function (index, id, name_role, checked) {
       this.save_edit_role(index, id, name_role, checked)
    }.bind(this));
    
    this.$eventHub.$on('add_role', function (name_role, checked) {
       this.add_save_role(name_role, checked)
    }.bind(this));

  },
  methods: {
    fetchData() {
      this.error = this.permissions = this.roles = null
      this.users_count = 0;
      this.loading = true
      axios
        .get('/api/admin/access.get')
        .then(response => {
            this.permissions = response.data.permissions;
            this.roles = response.data.roles;
        })
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));
    },
    focus_permission_Field(field, permission){
      this.editField = field;
      this.editFieldFocus = true;
      this.editFieldValue = permission.name;
    },
    blur_permission_Field(){
      this.editField = '';
    },
    show_permission_Field(field){
      return ( this.editField == field && this.editFieldFocus)
    },
    save_edit_permission_Field(index, permission) {

      if(!this.editFieldValue || ( !index && index <  0) || !permission)
        return false;

      axios.post('/api/admin/access.permission.save', {
        id: permission,
        name: this.editFieldValue,
        headers: {
          'Content-Type': 'application/json'
        }
        }).then(response => {
          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
          
          if (response.data.ok) {
            this.permissions[index].name = this.editFieldValue;

            this.editField = '';
            this.editFieldFocus = false;
            this.editFieldValue = '';

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
    show_permission_add_Field(){
      return ( this.addFieldFocus)
    },
    add_permission(){
      this.addFieldFocus = true;
    },
    add_permission_close(){
      this.addFieldFocus = false;
    },
    save_add_permission_Field() {

      if(!this.addFieldValue)
        return addFieldValue;

      axios.post('/api/admin/access.permission.add', {
        name: this.addFieldValue,
        headers: {
          'Content-Type': 'application/json'
        }
        }).then(response => {
          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
          
          if (response.data.ok && response.data.new_id) {
            this.permissions.unshift({id: response.data.new_id, name: this.addFieldValue});
            this.addFieldValue = '';
            this.addFieldFocus = false;

            this.$notify({
              group: 'settings',
              type: 'success',
              title: i18n.t('admin_add_message'),
              text:  i18n.t('admin_add_message_text')
            });
          }
          
        })
        .catch(error => {
          Swal.showValidationMessage(
            i18n.t('swal_error_message')
          )
        })

    },
    edit_box_role(index, id){
      if((!index && index <  0) || !id)
        return false;

        let permissions_selects = [];
        for (let i = 0; i < this.roles[index].role_permissions.length; i++) { 
          permissions_selects.unshift(this.roles[index].role_permissions[i].permissions_name);
        } 

        this.$modal.show(
        {
          template: `
            <div>
              <div class="modal-header modal-bg">
                <h5>{{$t('admin_access_right_roles_edit')}}</h5>
              </div>
              <div class="modal-body">
                <form>
                  <div class="form-group">
                    <label for="admin_edit_name_role">{{$t('admin_role_name')}}</label>
                    <input type="text" class="form-control" id="admin_edit_name_role" placeholder="" v-model="name_role">
                  </div>
                  <label>{{$t('admin_role_permissions')}}</label>
                  <div class="permissions-choice modal-bg p-3">
                    <ul class="menu-list-module list-group list-group-flush" v-for="permission in permissions" :key="permission.id">
                      <li class="list-group-item p-3 d-flex d-flex align-items-center">
                        <span class="flex-grow-1">{{ permission.name }}</span>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" :id="checkbox_id(permission.id)" :value="permission.name" v-model="checked" @change="$emit('change', checked)">
                          <label class="custom-control-label" :for="checkbox_id(permission.id)"></label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <v-button type="outline-primary" nativeType="button" v-on:click.native="save_role(index_r, role_id)">{{$t('admin_edit')}}</v-button>
                <v-button type="outline-primary" nativeType="button" v-on:click.native="$emit('close')">{{$t('admin_close')}}</v-button>
              </div>
            </div>
          `,
          data() {
            return {
              checked: this.initialChecked,
              name_role: this.namerole
            }
          },
          model: {
            prop: 'initialChecked',
            event: 'change'
          },
          props: {
            initialChecked: Array,
            permissions: Array,
            namerole: String,
            role_id: Number,
            index_r: Number
          },
          methods:{
            checkbox_id(id){
              return 'permissions-edit-select-' + id
            },
            save_role(index, id){
              if((!index && index <  0) || !id)
                return false;

              this.$eventHub.$emit('save_edit_role', index, id, this.name_role, this.checked);
              this.$emit('close');

            }
          }
        },
        {
          initialChecked: permissions_selects,
          permissions: this.permissions,
          namerole: this.roles[index].role_name,
          role_id: this.roles[index].role_id,
          index_r: index
        },
        { height: 'auto' }
      )

    },
    save_edit_role(index, role_id, role_name, role_permissions_selects) {
      if((!index && index <  0) || !role_id || !role_name || (!role_permissions_selects))
        return false;

        axios.post('/api/admin/access.role.edit', {
          role_id: role_id,
          role_name: role_name,
          role_permissions_selects: role_permissions_selects,
          headers: {
            'Content-Type': 'application/json'
          }
        }).then(response => {
          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
  
          if (response.data.ok) {

            this.roles[index].role_name = role_name;
            this.roles[index].role_permissions = response.data.role_permissions;

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
    add_save_role(role_name, role_permissions_selects) {
      if(!role_name || (!role_permissions_selects))
        return false;

        axios.post('/api/admin/access.role.add', {
          role_name: role_name,
          role_permissions_selects: role_permissions_selects,
          headers: {
            'Content-Type': 'application/json'
          }
        }).then(response => {
          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
  
          if (response.data.ok) {

            this.roles.unshift(response.data.role_permissions[0])

            this.$notify({
              group: 'settings',
              type: 'success',
              title: i18n.t('admin_add_message'),
              text:  i18n.t('admin_add_message_text')
            });
          }
        })
        .catch(error => {
          Swal.showValidationMessage(
            i18n.t('swal_error_message')
          )
        })
    },
    add_role(){
      this.$modal.show(
        {
          template: `
            <div>
              <div class="modal-header modal-bg">
                <h5>{{$t('admin_access_right_roles_add')}}</h5>
              </div>
              <div class="modal-body">
                <form>
                  <div class="form-group">
                    <label for="admin_add_name_role">{{$t('admin_role_name')}}</label>
                    <input type="text" class="form-control" id="admin_add_name_role" placeholder="" v-model="name_role">
                  </div>
                  <label>{{$t('admin_role_permissions')}}</label>
                  <div class="permissions-choice modal-bg p-3">
                    <ul class="menu-list-module list-group list-group-flush" v-for="permission in permissions" :key="permission.id">
                      <li class="list-group-item p-3 d-flex d-flex align-items-center">
                        <span class="flex-grow-1">{{ permission.name }}</span>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" :id="checkbox_id(permission.id)" :value="permission.name" v-model="checked" @change="$emit('change', checked)">
                          <label class="custom-control-label" :for="checkbox_id(permission.id)"></label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <v-button type="outline-primary" nativeType="button" v-on:click.native="add_role()">{{$t('admin_add')}}</v-button>
                <v-button type="outline-primary" nativeType="button" v-on:click.native="$emit('close')">{{$t('admin_close')}}</v-button>
              </div>
            </div>
          `,

          data() {
            return {
              checked: [],
              name_role: ''
            }
          },
          props: {
            permissions: Array
          },
          methods:{
            checkbox_id(id){
              return 'permissions-add-select-' + id
            },
            add_role(){
              this.$eventHub.$emit('add_role', this.name_role, this.checked);
              this.$emit('close');
            }
          }
        },
        {
          permissions: this.permissions
        },
        { height: 'auto' }
      )

    },
    delete_f (index, id, type){
      if(!type)
        return false;

        Swal.fire({
                title: this.$t('admin_warning2'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: this.$t('swal_delete_btn'),
                cancelButtonText: this.$t('swal_cancel_btn')

            }).then((result) => {

              if(type == 1){

                if (result.value) {

                    axios.post('/api/admin/access.permission.delete', {
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
                  this.$delete(this.permissions, index);
                })
                .catch(function (error) {
                  console.log(error);
                }); 
                }

              } else if (type == 2){

                if (result.value) {
                    axios.post('/api/admin/access.role.delete', {
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
                  this.$delete(this.roles, index);
                })
                .catch(function (error) {
                  console.log(error);
                }); 
                }

              }
            });
    }
  }
}
</script>
