<template>
   <div class="module mb-4">
        <div class="module-wrap bg-white rounded">
			<div>
				<template v-if="!isNull(articles_text) && islength(articles_text) && !error && !loading">
				<div class="row">
					<div class="col-12">
						<div class="p-4 blog-main">
							<h4 class="blog-post-title">{{articles_name}}</h4>
							<p class="blog-post-meta">{{articles_date}}</p>
						    <div class="blog-post" v-html="item()"></div>
						</div>
				    </div>
				</div>
			    </template>

			    <template v-if="((isNull(articles_text) || !islength(articles_text)) && (isNull(articles_text) || !islength(articles_text))) || error || loading">
			        <div class="p-3 text-center" v-if="isNull(articles_text) || !islength(articles_text) && !error && !loading">{{ $t('not_info') }}</div>
			        <div class="p-3 text-center text-danger" v-if="error ">{{ $t('error_info') }}</div>
			        <div class="spinner-grow text-primary p-3" role="status" v-if="loading">
			          <span class="sr-only">{{ $t('loading_info') }}</span>
			        </div>  
                </template>
			</div>
        </div>
   </div>
</template>

<script>
import axios from 'axios';
import Form from 'vform';
import { mapGetters } from 'vuex';
import i18n from '~/plugins/i18n';
import Swal from 'sweetalert2';
import { htmljson } from '~/mixins/htmljson';
import { variables } from '~/mixins/variables'

export default {

    mixins: [htmljson, variables],

    scrollToTop: false,

    metaInfo () {
        return { title: this.articles_name ? this.articles_name : this.$t('admin_articles')}
    },

    data() {
	    return {
            loading: false,
	    	error: null,
	    	articles_name: null,
	    	articles_text: null,
	    	articles_date: null,
	    	articles_category: null,
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
            this.error = this.articles_text = this.articles_state = this.articles_date = this.articles_category = this.articles_name = null;
            this.loading = true;

            if(this.isInt(this.$route.params.id)){
				axios.get('/api/admin/articles.get.id', {
				    params: {
				        id: this.$route.params.id
				    }
				})
		       .then(response => {
		            this.articles_name = response.data.name;
		            this.articles_text = JSON.parse(response.data.text);
		            this.articles_category = response.data.category;
		            this.articles_date = response.data.created_at;
		            this.articles_state = response.data.status;
		            
		       }) 
		       .catch(error => {
		          console.log(error);
		          this.error = true;
		       })
		       .finally(() => (this.loading = false));

            } else {
	          this.$router.push({ name: 'admin.articles.home' })
	        }
		},
		item() {
			return this.convertDataToHtml(this.articles_text);
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
