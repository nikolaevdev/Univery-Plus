<template>
  <div class="module">
    <div class="mb-3 d-flex justify-content-between">
      <h5 class="text-dark align-middle mb-0">{{$t('admin_university_information_specialties')}}</h5>
      <v-button :type="'outline-primary'" :class="{'btn-sm':true}" v-on:click.native="add_specialties_modal()" >{{ $t('admin_add') }}</v-button>
    </div>

    <div class="module-block bg-white rounded mb-4">
      <div class="wrap"v-if="!isNull(specialties) && islength(specialties) && !error && !loading">
        <ul class="menu-list-module list-group list-group-flush" v-for="(specialization, index) in specialties" :key="specialization.id">
          <li class="list-group-item p-3 d-flex d-flex align-items-center">
            <span class="flex-grow-1">{{ specialization.name }} <span class="badge badge-secondary">{{ specialization.stage_of_education_name }}</span></span>
            <div class="btn-group rounded" role="group" aria-label="">
              <v-button type="outline-primary" nativeType="button" class="btn-sm" v-on:click.native="edit_specialties_modal(index, specialization.id)">{{$t('admin_edit')}}</v-button>
              <v-button type="outline-danger" nativeType="button" class="btn-sm" v-on:click.native="delete_specialties(index, specialization.id)">{{$t('admin_delete')}}</v-button>
            </div>
          </li>
        </ul>
      </div>
      <div class="p-4" v-if="isNull(specialties) || !islength(specialties) || error || loading">
        <div class="p-3 text-center" v-if="isNull(specialties) || !islength(specialties) && !error && !loading">{{ $t('not_info') }}</div>
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
import { variables } from '~/mixins/variables'

export default {

  mixins: [variables],

  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('admin_university_information_specialties') }
  },

  data() {
	  return {
	    loading: false,
      uses: null,
	    faculty: null,
      specialties: null,
	    specialties_count: 0,
	    error: null,
      page: 1,
	  }
  },

  created() {
    this.fetchData()
  },
  watch: {
    $route: 'fetchData',
  },
  mounted() {
    this.$eventHub.$on('save_specialties', function (form) {
       this.save_specialties_form(form);
    }.bind(this));
    
    this.$eventHub.$on('add_specialties', function (form) {
       this.add_specialties_form(form);
    }.bind(this));

  },
  methods: {
    fetchData() {
      this.error = this.specialties = this.uses = this.faculty = this.stage_of_education =  null;
      this.specialties_count = 0;
      this.loading = true

      axios
        .get(`/api/admin/specialties.get`)
        .then(response => {
            this.specialties = response.data.data;
        }) 
	    .catch(error => {
	        console.log(error);
	        this.error = true;
	    })
	    .finally(() => (this.loading = false));

      axios
        .get('/api/admin/information.use.get_use_list')
        .then(response => {
            this.uses = response.data;
        })
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));

      axios
        .get('/api/admin/information.faculty.getlist')
        .then(response => {
            this.faculties = response.data;
        })
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));

      axios
        .get('/api/admin/specialties.stage_of_education')
        .then(response => {
            this.stage_of_education = response.data;
        })
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));

      
    },
    add_specialties_modal(){
      this.$modal.show(
        {
          template: `
            <div>
              <div class="modal-header modal-bg">
                <h5>{{$t('admin_specialties_add')}}</h5>
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
                    <label for="admin_specialties_add_name">{{$t('admin_specialties_form_name')}}</label>
                    <input type="text" class="form-control" id="admin_specialties_add_name" placeholder="" v-model="name">
                  </div>
                  <div class="form-group">
                    <label for="admin_specialties_add_description">{{$t('admin_specialties_form_description')}}</label>
                    <textarea class="form-control" id="admin_specialties_add_name" placeholder="" v-model="description" rows="5"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="admin_specialties_add_stage_of_education">{{$t('admin_specialties_form_stage_of_education')}}</label>
                    <select v-model="stage_of_education_select" class="form-control">
                      <option v-for="stage in stage_of_education" :key="stage.id" id="admin_specialties_add_stage_of_education" :value="stage.id">{{stage.name}}</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="admin_specialties_add_faculty">{{$t('admin_specialties_form_faculty')}}</label>
                    <select v-model="faculties_select" class="form-control">
                      <option v-for="faculty in faculties_s" :key="faculty.id" id="admin_specialties_add_faculty" :value="faculty.id">{{faculty.name}}</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="admin_specialties_add_direction_code">{{$t('admin_specialties_form_direction_code')}}</label>
                    <input type="text" class="form-control" id="admin_specialties_add_direction_code" placeholder="" v-model="direction_code">
                  </div>
                  <div class="form-group">
                    <label for="admin_specialties_add_duration_of_training">{{$t('admin_specialties_form_duration_of_training')}}</label>
                    <input type="text" class="form-control" id="admin_specialties_add_duration_of_training" placeholder="" v-model="duration_of_training">
                  </div>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="admin_specialties_add_full_time" v-model="full_time">
                    <label class="custom-control-label" for="admin_specialties_add_full_time">{{$t('admin_specialties_form_full_time')}}</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="admin_specialties_add_full_time_correspondence_course" v-model="full_time_correspondence_course">
                    <label class="custom-control-label" for="admin_specialties_add_full_time_correspondence_course">{{$t('admin_specialties_form_full_time_correspondence_course')}}</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="admin_specialties_add_correspondence_course" v-model="correspondence_course">
                    <label class="custom-control-label" for="admin_specialties_add_correspondence_course" >{{$t('admin_specialties_form_correspondence_course')}}</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    
                    <input type="checkbox" class="custom-control-input" id="admin_specialties_add_correspondence_course_distance" v-model="full_time_correspondence_course_distance">
                    <label class="custom-control-label" for="admin_specialties_add_correspondence_course_distance">{{$t('admin_specialties_form_correspondence_course_distance')}}</label>
                
                  </div>

                  <label>{{$t('admin_specialties_form_entrance_exams')}}</label>
                  <div class="roles-choice modal-bg p-3">
                    <ul class="menu-list-module list-group list-group-flush" v-for="use in checkbox_use" :key="use.rid">
                      <li class="list-group-item p-3 d-flex d-flex align-items-center">
                        <span class="flex-grow-1">{{ use.name }}</span>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" :id="checkbox_use_id(use.id)" :value="use.id" v-model="checked_uses" @change="$emit('change', checked_uses)">
                          <label class="custom-control-label" :for="checkbox_use_id(use.id)"></label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <v-button type="outline-primary" nativeType="button" v-on:click.native="add_()">{{$t('admin_specialties_add')}}</v-button>
                <v-button type="outline-primary" nativeType="button" v-on:click.native="$emit('close')">{{$t('admin_close')}}</v-button>
              </div>
            </div>
          `,

          data() {
            return {
              errors: [],
              name: '',
              description: '',
              direction_code: '',
              duration_of_training: '',
              full_time: '',
              full_time_correspondence_course: '',
              correspondence_course: '',
              full_time_correspondence_course_distance: '',
              faculties_select: '',
              stage_of_education_select: '',
              stage_of_education: this.stage_of_education_s,
              faculties_s: this.faculties,
              checkbox_use: this.uses,
              checked_uses: []
            }
          },
          props: {
            faculties: Array,
            uses: Array,
            stage_of_education_s: Array
          },
          created() {
            this.errors = [];
          },
          methods:{
            checkbox_use_id(id){
              return 'faculties-add-select-' + id
            },
            add_(){

              this.errors = [];

              if (this.name && this.description && this.direction_code && this.duration_of_training && ( this.full_time || this.full_time_correspondence_course || this.correspondence_course || this.full_time_correspondence_course_distance || this.faculties_select || stage_of_education) ) {
                this.$eventHub.$emit('add_specialties', { name: this.name, description: this.description, direction_code: this.direction_code, duration_of_training: this.duration_of_training,full_time: this.full_time, full_time_correspondence_course: this.full_time_correspondence_course, correspondence_course: this.correspondence_course, full_time_correspondence_course_distance: this.full_time_correspondence_course_distance, faculties: this.faculties_select, stage_of_education_select: this.stage_of_education_select, checked_uses:this.checked_uses});
                this.$emit('close');
              }

              if (!this.name) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_name'));
              }

              if (!this.description) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_description'));
              }

              if (!this.direction_code) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_direction_code'));
              }

              if (!this.stage_of_education_select) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_stage_of_education'));
              }

              if (!this.duration_of_training) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_duration_of_training'));
              }

              if (!this.full_time || !this.full_time_correspondence_course || !this.correspondence_course || !this.full_time_correspondence_course_distance || !this.faculties) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_form_of_training'));
              }
          
            }
          },

        },
        {
          faculties: this.faculties,
          uses: this.uses,
          stage_of_education_s: this.stage_of_education,
        },
        { 
          classes: 'my-3',
          scrollable: true,
          draggable: false,
          height: 'auto',

         }
      )

    },
    delete_specialties (index, id){
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
            axios.post('/api/admin/specialties.delete', {
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
             this.$delete(this.specialties, index);
            })
            .catch(function (error) {
              console.log(error);
            }); 
          }
        });
    },
    add_specialties_form(form) {

      if(!form)
        return false;

      axios.post('/api/admin/specialties.add', {

          name: form.name,
          description: form.description,
          direction_code: form.direction_code,
          duration_of_training: form.duration_of_training,
          full_time: form.full_time,
          full_time_correspondence_course: form.full_time_correspondence_course,
          correspondence_course: form.correspondence_course,
          full_time_correspondence_course_distance: form.full_time_correspondence_course_distance,
          faculty: form.faculties,
          stage_of_education: form.stage_of_education_select,
          checked_uses: form.checked_uses,
          headers: {
            'Content-Type': 'application/json'
          }

      }).then(response => {

          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
  
          if (response.data.ok) {

            this.specialties.push(response.data.specialization);
            this.specialties_count++;

            this.$notify({
              group: 'settings',
              type: 'success',
              title: i18n.t('admin_save_message'),
              text:  i18n.t('admin_specialties_add_message')
            });
          }
        })
        .catch(error => {
          Swal.showValidationMessage(
            i18n.t('swal_error_message')
          )
        })
    },
    save_specialties_form(form) {
      if(!form)
        return false;

      axios.post('/api/admin/specialties.save', {

          specialization_id: form.id,
          name: form.name,
          description: form.description,
          direction_code: form.direction_code,
          duration_of_training: form.duration_of_training,
          full_time: form.full_time,
          full_time_correspondence_course: form.full_time_correspondence_course,
          correspondence_course: form.correspondence_course,
          full_time_correspondence_course_distance: form.full_time_correspondence_course_distance,
          faculty: form.faculties,
          stage_of_education: form.stage_of_education,
          checked_uses: form.checked_uses,
          headers: {
            'Content-Type': 'application/json'
          }

      }).then(response => {

          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
  
          if (response.data.ok) {

            this.specialties[form.index].name = form.name;
            this.specialties[form.index].description = form.description;
            this.specialties[form.index].direction_code = form.direction_code;
            this.specialties[form.index].duration_of_training = form.duration_of_training;
            this.specialties[form.index].full_time = form.full_time;
            this.specialties[form.index].full_time_correspondence_course = form.full_time_correspondence_course;
            this.specialties[form.index].full_time_correspondence_course_distance = form.full_time_correspondence_course_distance;
            this.specialties[form.index].faculties = form.faculties;
            this.specialties[form.index].uses = form.checked_uses;

            this.$notify({
              group: 'settings',
              type: 'success',
              title: i18n.t('admin_save_message'),
              text:  i18n.t('admin_specialties_save_message')
            });
          }
        })
        .catch(error => {
          Swal.showValidationMessage(
            i18n.t('swal_error_message')
          )
        })
    },
    edit_specialties_modal(index, edit_specialization_id){
      if(!edit_specialization_id || index === null)
        return false;

        let uses_selects = [];
        for (let i = 0; i < this.specialties[index].uses.length; i++) { 
          uses_selects.unshift(this.specialties[index].uses[i].id);
        } 

        this.$modal.show(
        {
          template: `
            <div>
              <div class="modal-header modal-bg">
                <h5>{{$t('admin_specialties_edit')}}</h5>
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
                    <label for="admin_specialties_add_name">{{$t('admin_specialties_form_name')}}</label>
                    <input type="text" class="form-control" id="admin_specialties_add_name" placeholder="" v-model="name">
                  </div>
                  <div class="form-group">
                    <label for="admin_specialties_add_description">{{$t('admin_specialties_form_description')}}</label>
                    <textarea class="form-control" id="admin_specialties_add_name" placeholder="" v-model="description" rows="5"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="admin_specialties_add_stage_of_education">{{$t('admin_specialties_form_stage_of_education')}}</label>
                    <select v-model="stage_of_education_select" class="form-control">
                      <option v-for="stage in stage_of_education" :key="stage.id" id="admin_specialties_add_stage_of_education" :value="stage.id">{{stage.name}}</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="admin_specialties_add_faculty">{{$t('admin_specialties_form_description')}}</label>
                    <select v-model="faculties_select" id="admin_specialties_add_faculty" class="form-control">
                      <option :value="faculty.id" v-for="faculty in faculties" :key="faculty.id">{{faculty.name}}</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="admin_specialties_add_direction_code">{{$t('admin_specialties_form_direction_code')}}</label>
                    <input type="text" class="form-control" id="admin_specialties_add_direction_code" placeholder="" v-model="direction_code">
                  </div>
                  <div class="form-group">
                    <label for="admin_specialties_add_duration_of_training">{{$t('admin_specialties_form_duration_of_training')}}</label>
                    <input type="text" class="form-control" id="admin_specialties_add_duration_of_training" placeholder="" v-model="duration_of_training">
                  </div>

                   <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="admin_specialties_add_full_time" v-model="full_time">
                    <label class="custom-control-label" for="admin_specialties_add_full_time">{{$t('admin_specialties_form_full_time')}}</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="admin_specialties_add_full_time_correspondence_course" v-model="full_time_correspondence_course">
                    <label class="custom-control-label" for="admin_specialties_add_full_time_correspondence_course">{{$t('admin_specialties_form_full_time_correspondence_course')}}</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="admin_specialties_add_correspondence_course" v-model="correspondence_course">
                    <label class="custom-control-label" for="admin_specialties_add_correspondence_course" >{{$t('admin_specialties_form_correspondence_course')}}</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    
                    <input type="checkbox" class="custom-control-input" id="admin_specialties_add_correspondence_course_distance" v-model="full_time_correspondence_course_distance">
                    <label class="custom-control-label" for="admin_specialties_add_correspondence_course_distance">{{$t('admin_specialties_form_correspondence_course_distance')}}</label>
                
                  </div>

                  <label>{{$t('admin_specialties_form_entrance_exams')}}</label>
                  <div class="roles-choice modal-bg p-3">
                    <ul class="menu-list-module list-group list-group-flush" v-for="use in edit_uses" :key="use.rid">
                      <li class="list-group-item p-3 d-flex d-flex align-items-center">
                        <span class="flex-grow-1">{{ use.name }}</span>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" :id="checkbox_id(use.id)" :value="use.id" v-model="checked_uses" @change="$emit('change', checked_uses)">
                          <label class="custom-control-label" :for="checkbox_id(use.id)"></label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <v-button type="outline-primary" nativeType="button" v-on:click.native="save_(edit_index_r, edit_specialization_id)">{{$t('admin_edit')}}</v-button>
                <v-button type="outline-primary" nativeType="button" v-on:click.native="$emit('close')">{{$t('admin_close')}}</v-button>
              </div>
            </div>
          `,
          data() {
            return {
              errors: [],
              id: edit_specialization_id,
              name: this.edit_name,
              description: this.edit_description,
              direction_code: this.edit_direction_code,
              duration_of_training: this.edit_duration_of_training,
              full_time: this.edit_full_time,
              full_time_correspondence_course: this.edit_full_time_correspondence_course,
              correspondence_course: this.edit_correspondence_course,
              full_time_correspondence_course_distance: this.edit_full_time_correspondence_course_distance,
              stage_of_education_select: this.edit_stage_of_education_selected,
              stage_of_education: this.edit_stage_of_education_s,
              faculties: this.edit_faculties,
              faculties_select: this.edit_faculties_selected,
              checked_uses: this.edit_initialChecked,
            }
          },
          model: {
            prop: 'edit_initialChecked',
            event: 'change'
          },
          props: {
            edit_initialChecked: Array,
            edit_uses: Array,
            edit_faculties: Array,
            edit_faculties_selected: Number,
            edit_name: String,
            edit_description: String,
            edit_direction_code: String,
            edit_duration_of_training: String,
            edit_full_time: Number,
            edit_full_time_correspondence_course: Number,
            edit_correspondence_course: Number,
            edit_full_time_correspondence_course_distance: Number,
            edit_specialization_id: Number,
            edit_index_r: Number,
            edit_stage_of_education_selected: Number,
            edit_stage_of_education_s: Array
          },
          methods:{
            checkbox_id(id){
              return 'faculties-edit-select-' + id
            },
            save_(index, id){
              if((!index && index <  0) || !id)
                return false;

              this.errors = [];

              if (this.name && this.description && this.direction_code && this.duration_of_training && ( this.full_time || this.full_time_correspondence_course || this.correspondence_course || this.full_time_correspondence_course_distance || this.faculties_select || this.stage_of_education) ) {
                this.$eventHub.$emit('save_specialties', { id: this.id, name: this.name, description: this.description, direction_code: this.direction_code, duration_of_training: this.duration_of_training,full_time: this.full_time, full_time_correspondence_course: this.full_time_correspondence_course, correspondence_course: this.correspondence_course, full_time_correspondence_course_distance: this.full_time_correspondence_course_distance, faculties: this.faculties_select, stage_of_education: this.stage_of_education_select, checked_uses:this.checked_uses});
                  this.$emit('close');
              }

              if (!this.name) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_name'));
              }

              if (!this.description) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_description'));
              }

              if (!this.direction_code) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_direction_code'));
              }

              if (!this.stage_of_education_selected) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_stage_of_education'));
              }

              if (!this.duration_of_training) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_duration_of_training'));
              }

              if (!this.full_time || !this.full_time_correspondence_course || !this.correspondence_course || !this.full_time_correspondence_course_distance || !this.faculties) {
                this.errors.push(i18n.t('admin_e_message_specialties_no_form_of_training'));
              }

            }
          }
        },
        {
          edit_initialChecked: uses_selects,
          edit_uses: this.uses,
          edit_faculties: this.faculties,
          edit_faculties_selected: this.specialties[index].faculty,
          edit_name: this.specialties[index].name,
          edit_description: this.specialties[index].description,
          edit_direction_code: this.specialties[index].direction_code,
          edit_duration_of_training: this.specialties[index].duration_of_training,
          edit_full_time: this.specialties[index].full_time,
          edit_full_time_correspondence_course: this.specialties[index].full_time_correspondence_course,
          edit_correspondence_course: this.specialties[index].correspondence_course,
          edit_full_time_correspondence_course_distance: this.specialties[index].full_time_correspondence_course_distance,
          edit_specialization_id: this.specialties[index].id,
          edit_stage_of_education_s: this.stage_of_education,
          edit_stage_of_education_selected: this.specialties[index].stage_of_education,
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
    isNull(data){
      return this.empty(data);
    },
    islength(data){
      return this.isLength(data);
    }
  }
}
</script>
