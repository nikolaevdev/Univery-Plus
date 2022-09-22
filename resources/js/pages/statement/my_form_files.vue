<template>
  <div class="module">
    <div class="mb-3 d-flex text-center p-3 d-flex justify-content-center">
      <h2 class="text-dark align-middle mb-0">{{$t('statement_my_from_files')}}</h2>
    </div>

    <div class="row">
      <div class="col-12 col-md-8 m-auto">
        <div class="module-wrap p-5 bg-white rounded rounded-lg">
          <form class="form-validate statement-form" v-if="!isNull(status) && status >= 1 && !error && !loading">
            <div class="section">
              <div class="form-group">
                <label for="form_identification_document" class="form-label">{{ $t('statement_form_identification_document_file') }}</label>
                <input class="form-control rounded-lg" type="file" ref="file" name="identification_document" id="form_identification_document" v-on:change="statement_file_identification_document">
              </div>
              <div class="form-group">
                <label for="form_document_on_education_file" class="form-label">{{ $t('statement_form_education_file') }}</label>
                <input class="form-control rounded-lg" type="file" ref="file" name="document_on_education_file" id="form_document_on_education_file" v-on:change="statement_file_document_on_education_file">
              </div>
            </div>
          </form> 
          <div class="d-flex p-3 border rounded-lg align-items-center" v-if="isNull(status) && status < 1 && !error && !loading">
            <div class="p-2">
              <div class="alert alert-danger" role="alert">
                {{ $t('statement_form_not_filled') }}
              </div>
              <router-link :to="{ name: 'statement.my_form.complete' }">
                <h5 class="mb-0">{{$t('statement_my_complete')}}</h5>
              </router-link>
            </div>
          </div>
        </div>
        <div class="statement-form-footer p-3 bg-white rounded mb-3 rounded-lg" v-if="!isNull(status) && status >= 1 && !error && !loading">
          <v-button :loading="loading_send" class="btn-primary btn-lg btn-block" v-on:click.native="save_()">
            {{ $t('save') }}
          </v-button>
        </div>
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
    return { title: this.$t('statement_my_from_files') }
  },

  data() {
    return {
      file_identification_document: '',
      file_document_on_education: '',
      loading: false,
      loading_send: false,
      status: null,
      error: null
    }
  },

  created() {
    this.fetchData()
  },

  methods: {
    statement_file_identification_document(e){
      this.file_identification_document = e.target.files[0];
    },
    statement_file_document_on_education_file(e){
      this.file_document_on_education = e.target.files[0];
    },
    save_(event) {

      if(!this.file_identification_document && !this.file_identification_document)
        return false;

      this.loading_send = true;

        const config = { 'content-type': 'multipart/form-data' }
        const formData = new FormData()
        formData.append('file_identification_document', this.file_identification_document)
        formData.append('file_document_on_education', this.file_document_on_education)

        axios.post('/api/statement.my_files_add.post', formData, config)
        .then(response => {

        this.loading_send = false;

          if (!response.data.ok) {
            throw new Error(response.statusText)
          }
  
          if (response.data.ok) {
             this.$router.push({ name: 'statement.my_form' })
          }
        })
        .catch(error => {
          this.loading_send = false;
          Swal.showValidationMessage(
            i18n.t('swal_error_message')
          )
        })
    },
    fetchData() {
      this.error = this.status = null
      this.loading = true

      axios
        .get(`/api/statement.my_form_status.get`)
        .then(response => {
          this.status = response.data.status;
        }) 
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));
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
