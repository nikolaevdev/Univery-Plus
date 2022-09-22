<template>
  <div class="module mb-3 mb-lg-4">
    <div class="mb-3 d-flex text-center">
      <h1 class="text-dark align-middle mb-2">{{$t('application_creat')}}</h1>
    </div>

    <template v-if="!isNull(specialization) && islength(specialization) && !error && !loading">

      <div class="row">
        <div class="col-12 col-md-8">
          <div class="module-wrap p-5 bg-white rounded rounded-lg">
            <form class="form-validate statement-form">
              <div class="section mb-3">
                <div class="application-create-specialization-step2">
                  <div class="application-specialization-wrap p-3 rounded-lg">
                    <div class="application-specialization-top d-flex flex-column mb-2">
                      <div class="application-specialization-fac text-primary">{{ specialization.faculties_name}}</div>
                      <div class="application-specialization-name text-dark">{{ specialization.name}}</div>
                    </div>
                    <div class="application-specialization-bottom d-flex flex-column">
                      <div class="application-specialization-forms">
                        <div class="application-specialization-form">{{ $t('application_creat_form') }}</div>
                        <div class="d-flex flex-row flex-wrap">
                          <span class="badge badge-primary mr-1 mb-1" v-if="specialization.full_time">{{ $t('application_creat_form_1') }}</span>
                          <span class="badge badge-primary mr-1 mb-1" v-if="specialization.full_time_correspondence_course">{{ $t('application_creat_form_2') }}</span>
                          <span class="badge badge-primary mr-1 mb-1" v-if="specialization.correspondence_course">{{ $t('application_creat_form_3') }}</span>
                          <span class="badge badge-primary mr-1 mb-1" v-if="specialization.correspondence_course_distance">{{ $t('application_creat_form_4') }}</span>
                        </div>
                      </div>
                      <div class="application-specialization-uses">
                        <div class="application-specialization-label">{{ $t('application_creat_uses') }}</div>
                        <div class="d-flex flex-row flex-wrap">
                          <span class="badge badge-primary mr-1 mb-1" v-for="(use, index) in specialization.uses" :key="use.id">{{ use.name }}</span>
                        </div>
                      </div>
                      <div class="application-specialization-info">
                        <div class="application-specialization-label">{{ $t('application_creat_description') }}</div>
                        <div class="d-flex flex-row flex-wrap">
                          <p class="font-italic mb-0">{{ specialization.description}}</p>
                        </div>
                      </div>
                      <div class="application-specialization-info">
                        <div class="application-specialization-label">{{ $t('application_creat_direction_code') }}</div>
                        <div class="d-flex flex-row flex-wrap">
                          <p class="font-italic mb-0">{{ specialization.direction_code}}</p>
                        </div>
                      </div>
                      <div class="application-specialization-info">
                        <div class="application-specialization-label">{{ $t('application_creat_duration_of_training') }}</div>
                        <div class="d-flex flex-row flex-wrap">
                          <p class="font-italic mb-0">{{ specialization.duration_of_training}}</p>
                        </div>
                      </div>
                    </div>
              
                  </div>

                </div>
              </div>
              <div class="section">
                <div class="form-group">
                  <label for="form_identification_document" class="form-label">{{ $t('application_form_statement_file') }}</label>
                  <input class="form-control rounded-lg" type="file" ref="file" name="identification_document" id="form_statement_file" v-on:change="statement_file">
                </div>
              </div>
            </form>
         </div>
         <div class="statement-form-footer p-3 bg-white rounded mb-3 rounded-lg">
          <v-button :loading="loading_send" class="btn-primary btn-lg btn-block" v-on:click.native="send_()">
            {{ $t('send') }}
          </v-button>
          </div>
        </div>
      </div>
     
    </template>

    <div class="module-wrap p-4  bg-white rounded mb-3" v-if="isNull(specialization) || !islength(specialization) || error || loading">
      <div class="p-3 text-center" v-if="isNull(specialization) || !islength(specialization) && !error && !loading">{{ $t('not_info') }}</div>
      <div class="p-3 text-center text-danger" v-if="error ">{{ $t('error_info') }}</div>
      <div class="spinner-grow text-primary p-3" role="status" v-if="loading">
        <span class="sr-only">{{ $t('loading_info') }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
import i18n from '~/plugins/i18n'
import Swal from 'sweetalert2'
import { variables } from '~/mixins/variables'

export default {

  mixins: [variables],

  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('statement') }
  },

  data() {
    return {
      loading: false,
      list: null,
      error: null,
      specialization: null,
      specialization_id: null,
      file_statement: '',
      loading_send: false,
    }
  },

  created() {
    this.fetchData()
  },

  methods: {
    fetchData() {
      this.error = this.list = null
      this.loading = true

       if(this.isInt(this.$route.params.select)){

          axios
            .get(`/api/statement.get_specialization_info.get`, { params: { specialization: this.$route.params.select } } )
            .then(response => {
              this.specialization = response.data;
              this.specialization_id = response.data.id;
            }) 
          .catch(error => {
              console.log(error);
              this.error = true;
          })
          .finally(() => (this.loading = false));

        } else {
          this.$router.push({ name: 'statement.application.creat' })
        }
    },
    send_(event) {

      if(!this.file_statement)
        return false;

      this.loading_send = true;

        const config = { 'content-type': 'multipart/form-data' }
        const formData = new FormData()
        formData.append('specialization_id', this.specialization_id)
        formData.append('file_statement', this.file_statement)

        axios.post('/api/statement.application_step2.post', formData, config)
        .then(response => {

        this.loading_send = false;

          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
  
          if (response.data) {
             this.$router.push({ name: 'statement.application.my', params: { id: this.response.data.id } })
          }
        })
        .catch(error => {
          this.loading_send = false;
          Swal.showValidationMessage(
            i18n.t('swal_error_message')
          )
        })
    },
    statement_file(e){
      this.file_statement = e.target.files[0];
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
