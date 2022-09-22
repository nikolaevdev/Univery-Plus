<template>
  <div class="module">
    <div class="mb-3 d-flex text-center p-3 d-flex justify-content-center">
      <h2 class="text-dark align-middle mb-0">{{$t('statement_my_from')}}</h2>
    </div>

    <div class="row">
      <div class="col-12 col-md-8 m-auto">
        <div class="module-wrap p-5  bg-white rounded rounded-lg">
          <form class="form-validate statement-form" @keydown="form.onKeydown($event)">

            <div class="section mb-5">
              <div class="form-group">
                <label for="form_name" class="form-label">{{ $t('statement_form_name') }}</label>
                <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control rounded-lg" type="text" name="name" id="form_name" required="">
                <has-error :form="form" field="name" />
              </div>
              <div class="form-group">
                <label for="form_surname" class="form-label">{{ $t('statement_form_surname') }}</label>
                <input v-model="form.surname" :class="{ 'is-invalid': form.errors.has('surname') }" class="form-control rounded-lg" type="text" name="surname" id="form_surname" required="">
                <has-error :form="form" field="surname" />
              </div>
              <div class="form-group">
                <label for="form_patronymic" class="form-label">{{ $t('statement_form_patronymic') }}</label>
                <input v-model="form.patronymic" :class="{ 'is-invalid': form.errors.has('patronymic') }" class="form-control rounded-lg" type="text" name="patronymic" id="form_patronymic" required="">
                <has-error :form="form" field="patronymic" />
              </div>
              <div class="form-group">
                <label for="form_sex" class="form-label">{{ $t('statement_form_sex') }}</label>
                <select v-model="form.sex" :class="{ 'is-invalid': form.errors.has('sex') }" class="form-control rounded-lg" name="sex" id="form_sex" required="">
                  <option>{{ $t('statement_form_sex_0') }}</option>
                  <option value="1">{{ $t('statement_form_sex_1') }}</option>
                  <option value="2">{{ $t('statement_form_sex_2') }}</option>
                </select>
                <has-error :form="form" field="sex" />
              </div>
              <div class="form-group">
                <label for="form_checkword" class="form-label">{{ $t('statement_form_checkword') }}</label>
                <input v-model="form.checkword" :class="{ 'is-invalid': form.errors.has('checkword') }" class="form-control rounded-lg" type="text" name="checkword" id="form_checkword" required="">
                <has-error :form="form" field="checkword" />
              </div>
              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="form_birthday" class="form-label">{{ $t('statement_form_birthday') }}</label>
                    <input v-model="form.birthday" :class="{ 'is-invalid': form.errors.has('birthday') }" class="form-control rounded-lg" type="number" name="birthday" id="form_birthday" required="">
                    <has-error :form="form" field="birthday" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_birthmonth" class="form-label">{{ $t('statement_form_birthmonth') }}</label>
                    <input v-model="form.birthmonth" :class="{ 'is-invalid': form.errors.has('birthmonth') }" class="form-control rounded-lg" type="number" name="birthmonth" id="form_birthmonth" required="">
                    <has-error :form="form" field="birthmonth" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_birthyear" class="form-label">{{ $t('statement_form_birthyear') }}</label>
                    <input v-model="form.birthyear" :class="{ 'is-invalid': form.errors.has('birthyear') }" class="form-control rounded-lg" type="number" name="birthyear" id="form_birthmonth" required="">
                    <has-error :form="form" field="birthyear" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="form_telephone" class="form-label">{{ $t('statement_form_telephone') }}</label>
                <input v-model="form.telephone" :class="{ 'is-invalid': form.errors.has('telephone') }" class="form-control rounded-lg" type="text" name="telephone" id="form_telephone" required="">
                <has-error :form="form" field="telephone" />
              </div>
              <div class="form-group">
                <label for="form_telephone2" class="form-label">{{ $t('statement_form_telephone2') }}</label>
                <input v-model="form.telephone2" :class="{ 'is-invalid': form.errors.has('telephone2') }" class="form-control rounded-lg" type="text" name="telephone2" id="form_telephone2">
                <has-error :form="form" field="telephone2" />
              </div>
            </div>

            <div class="section mb-5">
              <div class="form-group">
                <label for="form_identification_document_id" class="form-label">{{ $t('statement_form_identification_document_id') }}</label>
                <select v-model="form.identification_document_id" :class="{ 'is-invalid': form.errors.has('identification_document_id') }" class="form-control rounded-lg" name="identification_document_id" id="form_identification_document_id" required="">
                  <option v-for="document in identity_documents" :key="document.id" id="admin_specialties_add_faculty" :value="document.id">{{document.name}}</option>
                </select>
                <has-error :form="form" field="identification_document_id" />
              </div>
              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="form_identification_document_series" class="form-label">{{ $t('statement_form_identification_document_series') }}</label>
                    <input v-model="form.identification_document_series" :class="{ 'is-invalid': form.errors.has('identification_document_series') }" class="form-control rounded-lg" type="number" name="identification_document_series" id="form_identification_document_series" required="">
                    <has-error :form="form" field="identification_document_series" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_identification_document_number" class="form-label">{{ $t('statement_form_identification_document_number') }}</label>
                    <input v-model="form.identification_document_number" :class="{ 'is-invalid': form.errors.has('identification_document_number') }" class="form-control rounded-lg" type="number" name="identification_document_number" id="form_identification_document_number" required="">
                    <has-error :form="form" field="identification_document_number" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="form_identification_division_code" class="form-label">{{ $t('statement_form_identification_division_code') }}</label>
                <input v-model="form.identification_division_code" :class="{ 'is-invalid': form.errors.has('identification_division_code') }" class="form-control rounded-lg" type="text" name="identification_division_code" id="form_identification_division_code">
                <has-error :form="form" field="identification_division_code" />
              </div>
              <div class="form-group">
                <label for="form_identification_issued_by" class="form-label">{{ $t('statement_form_identification_issued_by') }}</label>
                <input v-model="form.identification_issued_by" :class="{ 'is-invalid': form.errors.has('identification_issued_by') }" class="form-control rounded-lg" type="text" name="identification_issued_by" id="form_identification_issued_by">
                <has-error :form="form" field="identification_issued_by" />
              </div>
              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="form_identification_day" class="form-label">{{ $t('statement_form_identification_day') }}</label>
                    <input v-model="form.identification_day" :class="{ 'is-invalid': form.errors.has('identification_day') }" class="form-control rounded-lg" type="number" name="identification_day" id="form_identification_day" required="">
                    <has-error :form="form" field="identification_day" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_identification_month" class="form-label">{{ $t('statement_form_identification_month') }}</label>
                    <input v-model="form.identification_month" :class="{ 'is-invalid': form.errors.has('identification_month') }" class="form-control rounded-lg" type="number" name="identification_month" id="form_identification_month" required="">
                    <has-error :form="form" field="identification_month" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_identification_year" class="form-label">{{ $t('statement_form_identification_year') }}</label>
                    <input v-model="form.identification_year" :class="{ 'is-invalid': form.errors.has('identification_year') }" class="form-control rounded-lg" type="number" name="identification_year" id="form_identification_year" required="">
                    <has-error :form="form" field="identification_year" />
                  </div>
                </div>
              </div>
            </div>

            <div class="section mb-5">
              <div class="form-group">
                <label for="form_document_on_education_id" class="form-label">{{ $t('statement_form_document_on_education_id') }}</label>
                <select v-model="form.document_on_education_id" :class="{ 'is-invalid': form.errors.has('document_on_education_id') }" class="form-control rounded-lg" name="document_on_education_id" id="form_document_on_education_id" required="">
                  <option v-for="document in documents_on_education" :key="document.id" id="admin_specialties_add_faculty" :value="document.id">{{document.name}}</option>
                </select>
                <has-error :form="form" field="document_on_education_id" />
              </div>
              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="form_document_on_education_series" class="form-label">{{ $t('statement_form_document_on_education_series') }}</label>
                    <input v-model="form.document_on_education_series" :class="{ 'is-invalid': form.errors.has('document_on_education_series') }" class="form-control rounded-lg" type="number" name="document_on_education_series" id="form_document_on_education_series" required="">
                    <has-error :form="form" field="document_on_education_series" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_document_on_education_number" class="form-label">{{ $t('statement_form_document_on_education_number') }}</label>
                    <input v-model="form.document_on_education_number" :class="{ 'is-invalid': form.errors.has('document_on_education_number') }" class="form-control rounded-lg" type="number" name="document_on_education_number" id="form_document_on_education_number" required="">
                    <has-error :form="form" field="document_on_education_number" />
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="form_document_on_education_day_of_issue" class="form-label">{{ $t('statement_form_document_on_education_day_of_issue') }}</label>
                    <input v-model="form.document_on_education_day_of_issue" :class="{ 'is-invalid': form.errors.has('document_on_education_day_of_issue') }" class="form-control rounded-lg" type="number" name="document_on_education_day_of_issue" id="form_document_on_education_day_of_issue" required="">
                    <has-error :form="form" field="document_on_education_day_of_issue" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_document_on_education_month" class="form-label">{{ $t('statement_form_document_on_education_month') }}</label>
                    <input v-model="form.document_on_education_month" :class="{ 'is-invalid': form.errors.has('document_on_education_month') }" class="form-control rounded-lg" type="number" name="document_on_education_month" id="form_document_on_education_month" required="">
                    <has-error :form="form" field="document_on_education_month" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_document_on_education_year" class="form-label">{{ $t('statement_form_document_on_education_year') }}</label>
                    <input v-model="form.document_on_education_year" :class="{ 'is-invalid': form.errors.has('document_on_education_year') }" class="form-control rounded-lg" type="number" name="document_on_education_year" id="form_document_on_education_year" required="">
                    <has-error :form="form" field="document_on_education_year" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="form_document_on_education_name_of_organization" class="form-label">{{ $t('statement_form_document_on_education_name_of_organization') }}</label>
                <input v-model="form.document_on_education_name_of_organization" :class="{ 'is-invalid': form.errors.has('document_on_education_name_of_organization') }" class="form-control rounded-lg" type="text" name="document_on_education_name_of_organization" id="form_document_on_education_name_of_organization">
                <has-error :form="form" field="document_on_education_name_of_organization" />
              </div>
              <div class="form-group">
                <label for="form_document_on_education_organization_region" class="form-label">{{ $t('statement_form_document_on_education_organization_region') }}</label>
                <input v-model="form.document_on_education_organization_region" :class="{ 'is-invalid': form.errors.has('document_on_education_organization_region') }" class="form-control rounded-lg" type="text" name="document_on_education_organization_region" id="form_document_on_education_organization_region">
                <has-error :form="form" field="document_on_education_organization_region" />
              </div>
              <div class="form-group">
                <label for="form_document_on_education_organization_city" class="form-label">{{ $t('statement_form_document_on_education_organization_city') }}</label>
                <input v-model="form.document_on_education_organization_city" :class="{ 'is-invalid': form.errors.has('document_on_education_organization_city') }" class="form-control rounded-lg" type="text" name="document_on_education_organization_city" id="form_document_on_education_organization_city">
                <has-error :form="form" field="document_on_education_organization_city" />
              </div>
              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="form_document_on_education_year_of_receipt" class="form-label">{{ $t('statement_form_document_on_education_year_of_receipt') }}</label>
                    <input v-model="form.document_on_education_year_of_receipt" :class="{ 'is-invalid': form.errors.has('document_on_education_year_of_receipt') }" class="form-control rounded-lg" type="number" name="document_on_education_year_of_receipt" id="form_document_on_education_year_of_receipt" required="">
                    <has-error :form="form" field="document_on_education_year_of_receipt" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="document_on_education_year_of_completion" class="form-label">{{ $t('statement_form_document_on_education_year_of_completion') }}</label>
                    <input v-model="form.document_on_education_year_of_completion" :class="{ 'is-invalid': form.errors.has('document_on_education_year_of_completion') }" class="form-control rounded-lg" type="number" name="document_on_education_year_of_completion" id="document_on_education_year_of_completion" required="">
                    <has-error :form="form" field="document_on_education_year_of_completion" />
                  </div>
                </div>
              </div>
            </div>

            <div class="section mb-5">
              <div class="form-group">
                <label for="form_сitizenship" class="form-label">{{ $t('statement_form_сitizenship') }}</label>
                <input v-model="form.сitizenship" :class="{ 'is-invalid': form.errors.has('сitizenship') }" class="form-control rounded-lg" type="text" name="сitizenship" id="form_сitizenship">
                <has-error :form="form" field="сitizenship" />
              </div>
              <div class="form-group">
                <label for="form_document_on_education_organization_region" class="form-label">{{ $t('statement_form_сitizenship_region') }}</label>
                <input v-model="form.сitizenship_region" :class="{ 'is-invalid': form.errors.has('сitizenship_region') }" class="form-control rounded-lg" type="text" name="сitizenship_region" id="form_сitizenship_region">
                <has-error :form="form" field="сitizenship_region" />
              </div>
              <div class="form-group">
                <label for="form_сitizenship_city" class="form-label">{{ $t('statement_form_сitizenship_city') }}</label>
                <input v-model="form.сitizenship_city" :class="{ 'is-invalid': form.errors.has('сitizenship_city') }" class="form-control rounded-lg" type="text" name="сitizenship_city" id="form_сitizenship_city">
                <has-error :form="form" field="сitizenship_city" />
              </div>
              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="form_сitizenship_street" class="form-label">{{ $t('statement_form_сitizenship_street') }}</label>
                    <input v-model="form.сitizenship_street" :class="{ 'is-invalid': form.errors.has('сitizenship_street') }" class="form-control rounded-lg" type="text" name="сitizenship_street" id="form_сitizenship_street" required="">
                    <has-error :form="form" field="сitizenship_street" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_сitizenship_house" class="form-label">{{ $t('statement_form_сitizenship_house') }}</label>
                    <input v-model="form.сitizenship_house" :class="{ 'is-invalid': form.errors.has('сitizenship_house') }" class="form-control rounded-lg" type="text" name="сitizenship_house" id="form_сitizenship_house" required="">
                    <has-error :form="form" field="сitizenship_house" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_сitizenship_building" class="form-label">{{ $t('statement_form_сitizenship_building') }}</label>
                    <input v-model="form.сitizenship_building" :class="{ 'is-invalid': form.errors.has('сitizenship_building') }" class="form-control rounded-lg" type="text" name="сitizenship_building" id="form_сitizenship_building" required="">
                    <has-error :form="form" field="сitizenship_building" />
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="col">
                  <div class="form-group">
                    <label for="form_сitizenship_flat" class="form-label">{{ $t('statement_form_сitizenship_flat') }}</label>
                    <input v-model="form.сitizenship_flat" :class="{ 'is-invalid': form.errors.has('сitizenship_flat') }" class="form-control rounded-lg" type="text" name="сitizenship_flat" id="form_сitizenship_flat" required="">
                    <has-error :form="form" field="сitizenship_flat" />
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="form_postalcode" class="form-label">{{ $t('statement_form_postalcode') }}</label>
                    <input v-model="form.postalcode" :class="{ 'is-invalid': form.errors.has('postalcode') }" class="form-control rounded-lg" type="text" name="postalcode" id="form_postalcode">
                    <has-error :form="form" field="postalcode" />
                  </div>
                </div>
              </div>
            </div>

            <div class="section mb-5">
              <div class="custom-control custom-checkbox form-group">
                <input type="checkbox" class="custom-control-input" id="form_liable_for_military_service" v-model="form.liable_for_military_service" :class="{ 'is-invalid': form.errors.has('liable_for_military_service') }">
                <label class="custom-control-label" for="form_liable_for_military_service">{{ $t('statement_form_liable_for_military_service') }}</label>
                <has-error :form="form" field="liable_for_military_service" />
              </div>
              <div class="form-group">
                <label for="form_comment" class="form-label">{{ $t('statement_form_comment') }}</label>
                <input v-model="form.comment" :class="{ 'is-invalid': form.errors.has('comment') }" class="form-control rounded-lg" type="text" name="comment" id="form_comment">
                <has-error :form="form" field="comment" />
              </div>
              <div class="custom-control custom-checkbox form-group">
                <input type="checkbox" class="custom-control-input" id="form_dormitories" v-model="form.dormitories" :class="{ 'is-invalid': form.errors.has('dormitories') }">
                <label class="custom-control-label" for="form_dormitories">{{ $t('statement_form_dormitories') }}</label>
                <has-error :form="form" field="dormitories" />
              </div>
              <div class="custom-control custom-checkbox form-group">
                <input type="checkbox" class="custom-control-input" id="form_benefits" v-model="form.benefits" :class="{ 'is-invalid': form.errors.has('benefits') }">
                <label class="custom-control-label" for="form_benefits">{{ $t('statement_form_benefits') }}</label>
                <has-error :form="form" field="benefits" />
              </div>
              <div class="custom-control custom-checkbox form-group">
                <input type="checkbox" class="custom-control-input" id="form_individual_achievements" v-model="form.individual_achievements" :class="{ 'is-invalid': form.errors.has('individual_achievements') }">
                <label class="custom-control-label" for="form_individual_achievements">{{ $t('statement_form_individual_achievements') }}</label>
                <has-error :form="form" field="individual_achievements" />
              </div>
            </div>
          </form> 
        </div>
        <div class="statement-form-footer p-3 bg-white rounded mb-3 rounded-lg">
          <v-button :loading="form.busy" class="btn-primary btn-lg btn-block"  v-on:click.native="register_step2()">
            {{ $t('register') }}
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
    return { title: this.$t('statement') }
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
      documents_on_education: null,
      identity_documents: null,
      document_on_education_level: null, 
      error: null
    }
  },

  created() {
    this.fetchData()
  },

  methods: {
    async register_step2(){

      // Register the user.
      const { data } = await this.form.post('/api/statement.step1.sending')

      // Redirect statements.
      this.$router.push({ name: 'statement.my_form.files' })
    },
    fetchData() {
      this.error = this.documents_on_education = this.identity_documents = null
      this.loading = true

      axios
        .get(`/api/statement.infodata_form.get`)
        .then(response => {
          this.documents_on_education = response.data.documents_on_education;
          this.identity_documents = response.data.identity_documents;
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
