<template>
  <div class="module">
  	<div class="mb-3 d-flex justify-content-between">
  		<h5 class="text-dark align-middle mb-0">{{$t('admin_university_information_identification_documents_name')}}</h5>
  		<v-button :type="'outline-primary'" :class="{'btn-sm':true}" v-on:click.native="add_()">{{ $t('admin_add') }}</v-button>
  	</div>

  	 <div class="module-block bg-white rounded mb-4">
  		  <div class="wrap">
          <div class="p-3 d-flex d-flex align-items-center" v-if="show_add_Field()">
            <form class="list-group-item-edit form-inline flex-grow-1">
              <div class="form-group mr-2">
                <input type="text" class="form-control form-control-sm" v-model="addFieldValue">
              </div>
            </form>
            <div class="btn-group rounded" role="group" aria-label="">
              <v-button type="primary" nativeType="button" class="btn-sm" v-on:click.native="save_add_Field()">{{$t('admin_add')}}</v-button>
              <v-button type="primary" nativeType="button" class="btn-sm" v-on:click.native="add_close()">{{$t('admin_close')}}</v-button>
            </div>
          </div>
          <template v-if="!isNull(list_data) && islength(list_data) && !error && !loading">
    			<ul class="menu-list-module list-group list-group-flush" v-for="(record, index) in list_data" :key="record.id">
            <li class="list-group-item p-3 d-flex d-flex align-items-center">
              <span class="flex-grow-1" v-show="!show_Field(index)" @click="focus_Field(index, record)">{{ record.name }}</span>
              <form class="list-group-item-edit form-inline flex-grow-1" v-show="show_Field(index)" @focus="focus_Field(index, record)" @blur="blur_Field">
                <div class="form-group mr-2">
                  <input type="text" class="form-control form-control-sm" v-model="editFieldValue">
                </div>
                <v-button type="primary" nativeType="button" class="btn-sm" v-on:click.native="save_edit_Field(index, record.id)">{{$t('admin_save')}}</v-button>
              </form>
              <div class="btn-group rounded" role="group" aria-label="">
                <v-button type="outline-danger" nativeType="button" class="btn-sm" v-on:click.native="delete_f(index, record.id)">{{$t('admin_delete')}}</v-button>
    					</div>
            </li>
          </ul>
          </template>
    		</div>
  		  <div class="p-4" v-if="isNull(list_data) || !islength(list_data) || error || loading">
          <div class="p-3 text-center" v-if="isNull(list_data) || !islength(list_data) && !error && !loading">{{ $t('not_info') }}</div>
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
import { variables } from '~/mixins/variables'

export default {
  mixins: [variables],

  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('admin_university_information_identification_documents_name') }
  },

    data() {
      return {
          loading: true,
          list_data: null,
          error: null,
          editField : '',
          editFieldValue : '',
          editFieldFocus: false,
          addFieldValue : '',
          addFieldFocus: false,
      }
    },

  created() {
    this.fetchData()
  },
  watch: {
    $route: 'fetchData'
  },
  methods: {
    fetchData() {
      this.error = this.list_data = null
      this.loading = true
      axios
        .get('/api/admin/information.identification_documents_name.get')
        .then(response => {
            this.list_data = response.data;
        })
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));
    },
    focus_Field(field, record){
      this.editField = field;
      this.editFieldFocus = true;
      this.editFieldValue = record.name;
    },
    blur_Field(){
      this.editField = '';
    },
    show_Field(field){
      return ( this.editField == field && this.editFieldFocus)
    },
    save_edit_Field(index, record) {

      if(!this.editFieldValue || ( !index && index <  0) || !record)
        return false;

      axios.post('/api/admin/information.identification_documents_name.save', {
        id: record,
        name: this.editFieldValue,
        headers: {
          'Content-Type': 'application/json'
        }
        }).then(response => {
          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
          
          if (response.data.ok) {
            this.list_data[index].name = this.editFieldValue;

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
    show_add_Field(){
      return ( this.addFieldFocus)
    },
    add_(){
      this.addFieldFocus = true;
    },
    add_close(){
      this.addFieldFocus = false;
    },
    save_add_Field() {

      if(!this.addFieldValue)
        return addFieldValue;

      axios.post('/api/admin/information.identification_documents_name.add', {
        name: this.addFieldValue,
        headers: {
          'Content-Type': 'application/json'
        }
        }).then(response => {

          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
          
          if (response.data.ok && response.data.new_id) {
            this.list_data.unshift({id: response.data.new_id, name: this.addFieldValue});
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
    delete_f (index, id){
      if(!id || index === null)
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

            axios.post('/api/admin/information.identification_documents_name.delete', {
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
              this.$delete(this.list_data, index);
            })
            .catch(function (error) {
              console.log(error);
            }); 
          }
        });
    },
    isNull(data){
      return this.empty(data);
    },
    islength(data){
      return this.isLength(data);
    }
  }
}
</script>
