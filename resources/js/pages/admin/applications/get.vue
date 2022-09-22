<template>
  <div class="module mb-3 mb-lg-4">
    <div class="mb-3 d-flex py-3 flex-column">
      <h5 class="mb-2 text-dark">
        <router-link class="" :to="{ name: 'admin.users' }">
          {{$t('admin')}}
        </router-link>
      </h5>
      <div class="mb-3 d-flex justify-content-between">
        <h2 class="text-dark align-middle mb-2">{{$t('admin_application')}} {{indexer}}</h2>
      </div>
      <div class="align-middle statement-my-time d-flex flex-row">
        <div class="mr-3">
          {{$t('statement_my_from_creat')}} <span class="badge badge-secondary">{{created_at}}</span>
        </div>
        <div class="mr-3">
          {{$t('statement_my_from_last_edit_time')}} <span class="badge badge-secondary">{{updated_at}}</span>
        </div>
        <div class="mr-3">
          {{$t('statement_my_status')}} 
          <span class="badge badge-secondary" v-if="status == 1">
            {{$t('application_status_1')}} 
          </span>
          <span class="badge badge-warning" v-else-if="status == 2">
            {{$t('application_status_2')}} 
          </span>
          <span class="badge badge-success" v-else-if="status == 3">
            {{$t('application_status_3')}} 
          </span>
          <span class="badge badge-danger" v-else-if="status  == 4">
            {{$t('application_status_4')}} 
          </span>
          <span class="badge badge-secondary" v-else>
            {{$t('application_status_no_info')}} 
          </span>
        </div>
      </div>
    </div>

    <template v-if="!isNull(specialization) && islength(specialization) && !error && !loading">

      <div class="row" v-if="!isNull(form) && islength(form) && !error && !loading">
        <div class="col-12 col-md-8">
          <div class="module-wrap p-4  bg-white rounded rounded-lg">
            <div>
              <div class="section mb-5">

                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_name') }}</h5>
                  <span class="text">{{form.name}}</span>
                </div>

                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_surname') }}</h5>
                  <span class="text">{{form.surname}}</span>
                </div>

                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_patronymic') }}</h5>
                  <span class="text">{{form.patronymic}}</span>
                </div>

                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_sex') }}</h5>
                  <span class="text" v-if="form.sex == 1">{{ $t('statement_form_sex_1') }}</span>
                  <span class="text" v-if="form.sex == 2">{{ $t('statement_form_sex_2') }}</span>
                  <span class="text" v-else>{{ $t('statement_form_sex_0') }}</span>
                </div>

                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_checkword') }}</h5>
                  <span class="text">{{form.checkword}}</span>
                </div>

                <div class="form-row">
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_birthday') }}</h5>
                      <span class="text">{{form.birthday}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_birthmonth') }}</h5>
                      <span class="text">{{form.birthmonth}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_birthyear') }}</h5>
                      <span class="text">{{form.birthyear}}</span>
                    </div>
                  </div>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_telephone') }}</h5>
                  <span class="text">{{form.telephone}}</span>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_telephone2') }}</h5>
                  <span class="text">{{form.telephone2}}</span>
                </div>
              </div>
   
              <div class="section mb-5">
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_identification_document_id') }}</h5>
                  <select v-model="form.identification_document_id" class="form-control rounded-lg text" disabled>
                    <option v-for="document in identity_documents" :key="document.id" id="admin_specialties_add_faculty" :value="document.id">{{document.name}}</option>
                  </select>
                </div>
                <div class="form-row">
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_identification_document_series') }}</h5>
                      <span class="text">{{form.identification_document_series}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_identification_document_number') }}</h5>
                      <span class="text">{{form.identification_document_number}}</span>
                    </div>
                  </div>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_identification_division_code') }}</h5>
                  <span class="text">{{form.identification_division_code}}</span>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_identification_issued_by') }}</h5>
                  <span class="text">{{form.identification_issued_by}}</span>
                </div>
                <div class="form-row">
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_identification_day') }}</h5>
                      <span class="text">{{form.identification_day}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_identification_month') }}</h5>
                      <span class="text">{{form.identification_month}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_identification_year') }}</h5>
                      <span class="text">{{form.identification_year}}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="section mb-5">
                <div class="form-group statement-form-group">
                    <h5>{{ $t('statement_form_document_on_education_id') }}</h5>
                    <select v-model="form.document_on_education_id" class="form-control rounded-lg text" name="document_on_education_id" disabled>
                      <option v-for="document in documents_on_education" :key="document.id" id="admin_specialties_add_faculty" :value="document.id">{{document.name}}</option>
                    </select>
                </div>
                <div class="form-row">
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_document_on_education_series') }}</h5>
                      <span class="text">{{form.document_on_education_series}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_document_on_education_number') }}</h5>
                      <span class="text">{{form.document_on_education_number}}</span>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_document_on_education_day_of_issue') }}</h5>
                      <span class="text">{{form.document_on_education_day_of_issue}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_document_on_education_month') }}</h5>
                      <span class="text">{{form.document_on_education_month}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_document_on_education_year') }}</h5>
                      <span class="text">{{form.document_on_education_year}}</span>
                    </div>
                  </div>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_document_on_education_name_of_organization') }}</h5>
                  <span class="text">{{form.document_on_education_name_of_organization}}</span>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_document_on_education_organization_city') }}</h5>
                  <span class="text">{{form.document_on_education_organization_city}}</span>
                </div>
                <div class="form-row">
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_document_on_education_year_of_receipt') }}</h5>
                      <span class="text">{{form.document_on_education_year_of_receipt}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_document_on_education_year_of_completion') }}</h5>
                      <span class="text">{{form.document_on_education_year_of_completion}}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="section mb-5">
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_сitizenship') }}</h5>
                  <span class="text">{{form.сitizenship}}</span>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_сitizenship_region') }}</h5>
                  <span class="text">{{form.сitizenship_region}}</span>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_сitizenship_city') }}</h5>
                  <span class="text">{{form.сitizenship_city}}</span>
                </div>
                <div class="form-row">
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_сitizenship_street') }}</h5>
                      <span class="text">{{form.сitizenship_street}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_сitizenship_house') }}</h5>
                      <span class="text">{{form.сitizenship_house}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_сitizenship_building') }}</h5>
                      <span class="text">{{form.сitizenship_building}}</span>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_сitizenship_flat') }}</h5>
                      <span class="text">{{form.сitizenship_flat}}</span>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group statement-form-group">
                      <h5>{{ $t('statement_form_postalcode') }}</h5>
                      <span class="text">{{form.postalcode}}</span>
                    </div>
                  </div>
                </div>
                
              </div>

              <div class="section mb-5">
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_liable_for_military_service') }}</h5>
                  <span class="text" v-if="form.liable_for_military_service">{{ $t('yes') }}</span>
                  <span class="text" v-else>{{ $t('no') }}</span>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_comment') }}</h5>
                  <span class="text font-italic">{{form.comment}}</span>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_dormitories') }}</h5>
                  <span class="text" v-if="form.dormitories">{{ $t('yes') }}</span>
                  <span class="text" v-else>{{ $t('no') }}</span>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_benefits') }}</h5>
                  <span class="text" v-if="form.benefits">{{ $t('yes') }}</span>
                  <span class="text" v-else>{{ $t('no') }}</span>
                </div>
                <div class="form-group statement-form-group">
                  <h5>{{ $t('statement_form_individual_achievements') }}</h5>
                  <span class="text" v-if="form.individual_achievements">{{ $t('yes') }}</span>
                  <span class="text" v-else>{{ $t('no') }}</span>
                </div>
              </div>

              <form class="form-validate statement-form section mb-2">
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
              </form>

            </div> 
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="module-wrap p-4  bg-white rounded rounded-lg">
            <div class="statement-form">
              <div class="section">
                <div class="form-group statement-form-group">
                  <h5 class="mb-2">{{ $t('statement_form_identification_document') }}</h5>
                  <button type="button" class="btn btn-primary" @click="document_identification_download()">{{ $t('statement_form_document_download') }}</button>
                </div>
                <div class="form-group statement-form-group">
                  <h5 class="mb-2">{{ $t('statement_form_document_on_education') }}</h5>
                  <button type="button" class="btn btn-primary" @click="document_education_download()">{{ $t('statement_form_document_download') }}</button>
                </div>
                <div class="form-group statement-form-group">
                  <h5 class="mb-2">{{ $t('application_form_statement_file') }}</h5>
                  <button type="button" class="btn btn-primary" @click="application_form_statement_download(application.id)">{{ $t('application_form_statement_file_download') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     
    </template>

    <div class="module-wrap p-4  bg-white rounded mb-3" v-if=" ((isNull(specialization) || !islength(specialization)) && (isNull(application) || !islength(application))) || error || loading">
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
    return { title: this.$t('admin_applications') }
  },

  data() {
    return {
      form: new Form({
        name: '',
        surname: '',
        patronymic: '',
        sex: '',
        checkword: '',
        birthday: '',
        birthmonth: '',
        birthyear: '',
        telephone: '',
        telephone2: '',
        identification_document_id: '',
        identification_document_series: '',
        identification_document_number: '',
        identification_division_code: '',
        identification_issued_by: '',
        document_on_education_id: '',
        identification_document_url: '',
        document_on_education_level_id: '',
        identification_day: '',
        identification_month: '',
        identification_year: '',
        document_on_education_id: '',
        document_on_education_series: '',
        document_on_education_number: '',
        document_on_education_day_of_issue: '',
        document_on_education_month: '',
        document_on_education_year: '',
        document_on_education_name_of_organization: '',
        document_on_education_organization_region: '',
        document_on_education_organization_city: '',  
        document_on_education_year_of_receipt: '',
        document_on_education_year_of_completion: '',
        document_on_education_file_url: '',
        сitizenship: '',
        сitizenship_region: '',
        сitizenship_city: '',
        сitizenship_street: '',
        сitizenship_house: '',  
        сitizenship_building: '',
        сitizenship_flat: '',
        postalcode: '',
        сitizenship_region_actual: '',
        сitizenship_city_actual: '',
        сitizenship_street_actual: '',
        сitizenship_house_actual: '',
        сitizenship_building_actual: '',  
        postalcode_actual: '',
        liable_for_military_service: '',
        comment: '',
        dormitories: '',
        benefits: '',
        individual_achievements: '',
      }),
      loading: false,
      list: null,
      error: null,
      specialization: null,
      application: null,
      indexer: null,
      created_at: null,
      updated_at: null,
      file_statement: '',
      documents_on_education: null,
      identity_documents: null,
      document_on_education_level: null, 
      status: null,
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

       if(this.isInt(this.$route.params.id)){

          axios
            .get(`/api/admin/applications.getinfo`, { params: { id: this.$route.params.id } } )
            .then(response => {
              this.specialization = response.data.specialization;
              this.application = response.data.application;
              this.indexer = response.data.application.indexer;

              this.form.name = response.data.form.name;
              this.form.surname= response.data.form.surname;
              this.form.patronymic = response.data.form.patronymic;
              this.form.sex = response.data.form.sex;
              this.form.checkword = response.data.form.checkword;
              this.form.birthday = response.data.form.birthday;
              this.form.birthmonth = response.data.form.birthmonth;
              this.form.birthyear = response.data.form.birthyear;
              this.form.telephone = response.data.form.telephone;
              this.form.telephone2 = response.data.form.telephone2;
              this.form.identification_document_id = response.data.form.identification_document_id;
              this.form.identification_document_series = response.data.form.identification_document_series;
              this.form.identification_document_number = response.data.form.identification_document_number;
              this.form.identification_division_code = response.data.form.identification_division_code;
              this.form.identification_issued_by = response.data.form.identification_issued_by;
              this.form.identification_day = response.data.form.identification_day;
              this.form.identification_month = response.data.form.identification_month;
              this.form.identification_year = response.data.form.identification_year;
              this.form.document_on_education_id = response.data.form.document_on_education_id;
              this.form.document_on_education_series = response.data.form.document_on_education_series;
              this.form.document_on_education_number = response.data.form.document_on_education_number;
              this.form.document_on_education_day_of_issue = response.data.form.document_on_education_day_of_issue;
              this.form.document_on_education_month = response.data.form.document_on_education_month;
              this.form.document_on_education_year = response.data.form.document_on_education_year;
              this.form.document_on_education_name_of_organization = response.data.form.document_on_education_name_of_organization;
              this.form.document_on_education_organization_region = response.data.form.document_on_education_organization_region;
              this.form.document_on_education_organization_city = response.data.form.document_on_education_organization_city;
              this.form.document_on_education_year_of_receipt = response.data.form.document_on_education_year_of_receipt;
              this.form.document_on_education_year_of_completion = response.data.form.document_on_education_year_of_completion;
              this.form.document_on_education_file_url = response.data.form.document_on_education_file_url;
              this.form.сitizenship = response.data.form.сitizenship;
              this.form.сitizenship_region = response.data.form.сitizenship_region;
              this.form.сitizenship_city = response.data.form.сitizenship_city;
              this.form.сitizenship_street = response.data.form.сitizenship_street;
              this.form.сitizenship_house = response.data.form.сitizenship_house;
              this.form.сitizenship_building = response.data.form.сitizenship_building;
              this.form.сitizenship_flat = response.data.form.сitizenship_flat;
              this.form.postalcode = response.data.form.postalcode;
              this.form.сitizenship_region_actual = response.data.form.сitizenship_region_actual;
              this.form.сitizenship_city_actual = response.data.form.сitizenship_city_actual;
              this.form.сitizenship_street_actual = response.data.form.сitizenship_street_actual;
              this.form.сitizenship_house_actual = response.data.form.сitizenship_house_actual;
              this.form.сitizenship_building_actual = response.data.form.сitizenship_building_actual;
              this.form.postalcode_actual = response.data.form.postalcode_actual;
              this.form.liable_for_military_service = response.data.form.liable_for_military_service;
              this.form.comment = response.data.form.comment;
              this.form.dormitories = response.data.form.dormitories;
              this.form.benefits = response.data.form.benefits;
              this.form.individual_achievements = response.data.form.individual_achievements;

              this.status = response.data.application.status;
              this.created_at = response.data.application.created_at;
              this.updated_at = response.data.application.updated_at;
            }) 
          .catch(error => {
              console.log(error);
              this.error = true;
          })
          .finally(() => (this.loading = false));

        } else {
          this.$router.push({ name: 'admin.applications.list' })
        }
    },
    application_form_statement_download() {
      let statement_download = this.$router.resolve({path: '/api/admin/applications.file_statement_document.get', query: {id: this.application.id}});
      window.open(statement_download.href, '_blank');
    },
    document_identification_download() {
      let identification_download = this.$router.resolve({path: '/api/admin/applications.file_identification_document.get', query: {id: this.application.id}});
      window.open(identification_download.href, '_blank');
    },
    document_education_download(){
      let education_download = this.$router.resolve({path: '/api/admin/applications.get_file_document_on_education.get', query: {id: this.application.id}});
      window.open(education_download.href, '_blank');
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
