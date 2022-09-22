<template>
   <div class="module mb-4">
        <div class="module-wrap bg-white rounded">
			<div>
				<div class="row">
					<div class="col-12 col-lg-9">
						<div class="p-4 mb-5">
				    		<div class="form-group">
				    		  <label for="documents_title">{{ $t('admin_documents_title') }}</label>
			            	  <input id="documents_title" class="form-control mr-4 rounded-lg" type="text" :placeholder="$t('admin_documents_title')" v-model="name">
			                </div>
			                <div class="form-group">
				    		  <label for="documents_title">{{ $t('admin_documents_description') }}</label>		    		  
                              <textarea id="documents_description" class="form-control mr-4 rounded-lg" rows="3" v-model="description">></textarea>
			                </div>
			                <div class="form-group">
				    		   <label for="documents_file">{{ $t('admin_documents_file') }}</label>
				    		   <input class="form-control mr-4 rounded-lg" type="file" ref="file" id="documents_file" v-on:change="document_file">
			                </div>
						</div>
					</div>
					<div class="col-12 col-lg-3">
						<div class="wrap-100">
							<form class="p-3">
								<div class="form-group">
								  <button type="button" class="nav-link btn btn-primary w-100 rounded-lg" @click="add_()">{{ $t('admin_add') }}</button>
							    </div>
								<div class="form-group">
								    <label for="documents_add_category">{{ $t('admin_documents_category') }}</label>
								    <select class="form-control rounded-lg" id="documents_add_category" v-model="category_select">
								        <option v-for="(category, index) in categories" :key="category.id" :value="category.id">{{category.name}}</option>
								    </select>
								</div>
		                    </form>
						</div>
					</div>
				</div>
			</div>
        </div>
        <notifications group="documents"
        class="notifications"
        position="bottom right"
        :max="3"
        :width="400"/>
   </div>
</template>

<script>
import axios from 'axios';
import Form from 'vform';
import { mapGetters } from 'vuex';
import i18n from '~/plugins/i18n';
import Swal from 'sweetalert2';

export default {
    scrollToTop: false,

    metaInfo () {
        return { title: this.$t('admin_documents_add') }
    },

    data() {
	    return {
	    	categories: null,
	    	error: null,
	    	name: '',
	    	description: '',
	    	file: '',
	    	category_select: '',
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
            this.error = this.categories = null;
            this.loading = true;

		      axios
		        .get('/api/admin/documents.categories.get')
		        .then(response => {
		            this.categories = response.data;
		        })
		        .catch(error => {
		            console.log(error);
		            this.error = true;
		        })
		        .finally(() => (this.loading = false));
		},
		document_file(e){
            this.file = e.target.files[0];
        },
        add_(event) {

	      if(!this.file)
	        return false;

	      this.loading_send = true;

	        const config = { 'content-type': 'multipart/form-data' }
	        const formData = new FormData()
	        formData.append('file', this.file)
	        formData.append('name', this.name)
	        formData.append('category_select', this.category_select)
	        formData.append('description', this.description)

	        axios.post('/api/admin/documents.add', formData, config)
	        .then(response => {

	        this.loading_send = false;

	          if (!response.data.ok) {
	            throw new Error(response.statusText)
	          }
	  
	          if (response.data.ok) {
	             this.$router.push({ name: 'admin.documents.home' })
	          }
	        })
	        .catch(error => {
	          this.loading_send = false;
	          Swal.showValidationMessage(
	            i18n.t('swal_error_message')
	          )
	        })
	    }
    }
}
</script>
