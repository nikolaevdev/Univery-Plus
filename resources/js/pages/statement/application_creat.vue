<template>
  <div class="module mb-3 mb-lg-4">
    <div class="mb-3 d-flex text-center">
      <h1 class="text-dark align-middle mb-0">{{$t('application_creat')}}</h1>
    </div>


    <template v-if="!isNull(list) && islength(list) && !error && !loading">
      <div class="application-stage" v-for="(stage_of_education, index) in list" :key="stage_of_education.id">
        <h2 class="mb-3">{{ stage_of_education.stage_of_education_name}}</h2>
        <div class="row">
          <div class="col-12 col-sm-6 col-lg-4 mb-2 mb-sm-3" v-for="(specialization, index2) in stage_of_education.specialties" :key="specialization.id">
           
            <div class="application-specialization-wrap p-3 rounded-lg" @click="select_specialization(index, index2)" :class="{ selected : select == specialization.id }">
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
              </div>
              
            </div>
          </div>
        </div>
      </div>
      <transition name="specialization-selected-transition">
        <div class="d-flex application-specialization-selected bg-white p-3 rounded-lg align-items-center" v-if="select">
            <div class="p-2 flex-grow-1 text">{{ $t('application_specialization_selected') }} <span class="font-weight-bold">{{select_name}}</span> <span class="badge badge-primary">{{ select_stage_of_education }}</span></div>
            <div class="p-2 bd-highlight"><button type="button" class="btn btn-dark rounded-lg " @click="select_clear()">{{ $t('clear') }}</button></div>
            <div class="p-2 bd-highlight"><button type="button" class="btn btn-primary rounded-lg" @click="select_continue()">{{ $t('continue') }}</button></div>
        </div>
      </transition>
    </template>

    <div class="module-wrap p-4  bg-white rounded mb-3" v-if="isNull(list) || !islength(list) || error || loading">
      <div class="p-3 text-center" v-if="isNull(list) || !islength(list) && !error && !loading">{{ $t('not_info') }}</div>
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
    return { title: this.$t('application_creat') }
  },

  data() {
    return {
      loading: false,
      list: null,
      error: null,
      select: null,
      select_name: null,
      select_stage_of_education: null
    }
  },

  created() {
    this.fetchData()
  },

  methods: {
    fetchData() {
      this.error = this.list = null
      this.loading = true

      axios
        .get(`/api/statement.get_specializations_info.get`)
        .then(response => {
          this.list = response.data;
        }) 
      .catch(error => {
          console.log(error);
          this.error = true;
      })
      .finally(() => (this.loading = false));
    },
    select_specialization(index, index2){

      this.select = this.list[index].specialties[index2].id;
      this.select_name = this.list[index].specialties[index2].name;
      this.select_stage_of_education = this.list[index].stage_of_education_name;

    },
    select_clear(){
      this.select = this.select_name = this.select_stage_of_education = null;
    },
    select_continue(){
      this.$router.push({ name: 'statement.application.step2', params: { select: this.select } })
      
      this.select = this.select_name = this.select_stage_of_education = null;
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
