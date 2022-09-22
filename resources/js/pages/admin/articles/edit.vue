<template>
   <div class="module mb-4">
        <div class="module-wrap bg-white rounded">
			<div>
				<div class="row">
					<div class="col-12">
					</div>
					<div class="col-12 col-lg-9">
						<div class="p-4 border-bottom mb-5">
			    		<div class="form-group">
			    		  <label for="articles_title">{{ $t('admin_articles_title') }}</label>
		            	<input id="articles_title" class="form-control mr-4 rounded-pill" type="text" :placeholder="$t('admin_articles_title')" v-model="articles_name">
		            </div>
				    </div>
		            <div id="editor-js" class="p-3">
		        </div>
					</div>
					<div class="col-12 col-lg-3">
						<div class="wrap-100">
							<form class="p-3">
								<div class="form-group">
								  <button type="button" class="nav-link btn btn-primary w-100 rounded-pill" @click="edit_articles()">{{ $t('admin_save') }}</button>
							    </div>
							    <div class="form-group">
								  <button type="button" class="nav-link btn btn-danger w-100 rounded-pill" @click="delete_articles()">{{ $t('delete') }}</button>
							    </div>
			    		        <div class="form-group">
								    <label for="articles_add_state">{{ $t('admin_articles_state') }}</label>
								    <select class="form-control rounded-pill" id="articles_add_state" v-model="articles_state">
							        <option value="0" selected>{{ $t('admin_articles_state_0') }}</option>
							        <option value="1">{{ $t('admin_articles_state_1') }}</option>
							        <option value="2">{{ $t('admin_articles_state_2') }}</option>
							        <option value="3">{{ $t('admin_articles_state_3') }}</option>
								    </select>
								</div>
								<div class="form-group">
								    <label for="articles_add_category">{{ $t('admin_articles_category') }}</label>
								    <select class="form-control rounded-pill" id="articles_add_category" v-model="articles_category">
							        <option v-for="(category, index) in categories" :key="category.id" :value="category.id">{{category.name}}</option>
								    </select>
								</div>
		                    </form>
						</div>
					</div>
				</div>
			</div>
        </div>
        <notifications group="articles"
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
import localdata from '~/plugins/localdata';
import Swal from 'sweetalert2';

import EditorJS from '@editorjs/editorjs';

import Header from '@editorjs/header';
import Paragraph from '@editorjs/paragraph';
import Embed from '@editorjs/embed';
import Table from '@editorjs/table';
import Checklist from '@editorjs/checklist';
import Marker from '@editorjs/marker';
import Quote from '@editorjs/quote';
import ImageTool from '@editorjs/image';
import Delimiter from '@editorjs/delimiter';
import AttachesTool from '@editorjs/attaches';
import NestedList from '@editorjs/nested-list';

import { variables } from '~/mixins/variables';

export default {

    mixins: [variables],

    scrollToTop: false,

    metaInfo () {
        return { title: this.$t('admin_articles_edit') }
    },

    data() {
	    return {
	    	categories: null,
	    	error: null,
	    	editor: null,
	    	articles_name: localdata.get('admin.edit.articles.name')[1],
	    	articles_text:  localdata.get('admin.edit.articles.text')[1],
	    	articles_state: localdata.get('admin.edit.articles.state')[1],
	    	articles_category: localdata.get('admin.edit.articles.category_select')[1],
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

            if(this.isInt(this.$route.params.id)){
				axios.get('/api/admin/articles.get.id', {
				    params: {
				        id: this.$route.params.id
				    }
				})
		       .then(response => {
		            this.articles_name = response.data.name;
		            this.articles_text = response.data.text;
		            this.articles_category = response.data.category;
		            this.articles_state = response.data.status;

		            this.editor = new EditorJS({
					    holder: 'editor-js',

					    tools:{
					    	  header: {
					          class: Header,
					        },
			                list: {
							      class: NestedList,
							      inlineToolbar: true,
							    },
					        paragraph: {
					            class: Paragraph,
					        },
					        embed: {
					            class: Embed,
					            config: {
					                services: {
					                   youtube: true,
					                }
					            }
					        },
					        table: {
					            class: Table,
					            inlineToolbar: true,
					            config: {
					                rows: 2,
					                cols: 3,
					            },
					        },
			                checklist: {
			                    class: Checklist,
			                },
			                Marker: {
			                    class: Marker,
			                    shortcut: 'CMD+SHIFT+M',
			                },
			                quote: {
			                    class: Quote,
			                    inlineToolbar: true,
			                    shortcut: 'CMD+SHIFT+O'
			                },
			                image: {
			                	class: ImageTool,
					            config: {
			                        endpoints: {
					                    byFile: '/api/admin/articles.editor.upload.images',
					                },
					            }
					        },
			                delimiter: Delimiter,
				            attaches: {
						        class:AttachesTool,
						        config: {
						            endpoint: '/api/admin/articles.editor.upload.file'
						        }
						    }
					    },
			            i18n: {
					        messages: {   
					    
						        ui: { 
							        "blockTunes": {
							            "toggler": {
							                "Click to tune": this.$t('admin_editor_click_to_tune'),
							                "or drag to move": this.$t('admin_editor_or_drag_to_move'),
							            },
							        },
							        "inlineToolbar": {
							          "converter": {
							            "Convert to": this.$t('admin_editor_сonvert_to'),
							          }
							        },
							        "toolbar": {
							          "toolbox": {
							            "Add": this.$t('admin_editor_add'),  
							          }
							        }
						        },
					  
						      
						        toolNames: {
							        "Text": this.$t('admin_editor_text'),
							        "Heading": this.$t('admin_editor_heading'),
							        "List": this.$t('admin_editor_list'),
							        "Warning": this.$t('admin_editor_warning'),
							        "Checklist": this.$t('admin_editor_checklist'),
							        "Quote": this.$t('admin_editor_quote'),
							        "Code": this.$t('admin_editor_code'),
							        "Delimiter": this.$t('admin_editor_delimiter'),
							        "Raw HTML": this.$t('admin_editor_raw_html'),
							        "Table": this.$t('admin_editor_table'),
							        "Link": this.$t('admin_editor_link'),
							        "Marker": this.$t('admin_editor_marker'),
							        "Bold": this.$t('admin_editor_bold'),
							        "Italic": this.$t('admin_editor_italic'),
							        "InlineCode": this.$t('admin_editor_inlineCode'),
						        },
						        tools: {
							        "warning": { 
							            "Title": this.$t('admin_editor_title'),
							            "Message": this.$t('admin_editor_message'),
							        },
							        "link": {
							            "Add a link": this.$t('admin_editor_add_a_link')
							        },
							        "stub": {
							            "The block can not be displayed correctly.": this.$t('admin_editor_stub_not_be_displayed_correctly')
							        }
						        },
						  
						        blockTunes: {
							        "delete": {
							            "Delete": this.$t('admin_editor_delete')
							        },
							        "moveUp": {
							            "Move up": this.$t('admin_editor_move_up')
							        },
							        "moveDown": {
							            "Move down": this.$t('admin_editor_move_down')
							        }
						        }
					        }
					    },
					    data: JSON.parse(response.data.text),
					    onChange: (data) => {

					    	this.editor.saver.save()
							.then((savedData) => {
							    localdata.put('admin.edit.articles.name', this.name);
							    localdata.put('admin.edit.articles.state', this.state);
							    localdata.put('admin.edit.articles.category_select', this.category_select);
							    localdata.put('admin.edit.articles.text', savedData);
							}).catch((error) => {
								this.$notify({
					              group: 'articles',
					              type: 'error',
					              title: i18n.t('error_title'),
					              text:  error
					            });
							});
					    }
					});
		       }) 
		       .catch(error => {
		          console.log(error);
		          this.error = true;
		       })
		       .finally(() => (this.loading = false));

		       axios
		        .get('/api/admin/articles.categories.get')
		        .then(response => {
		            this.categories = response.data;
		        })
		        .catch(error => {
		            console.log(error);
		            this.error = true;
		        })
		        .finally(() => (this.loading = false));

            } else {
	          this.$router.push({ name: 'articles.articles.home' })
	        }
		},
        edit_articles() {

        	this.editor.saver.save()
				.then((savedData) => {
				    localdata.put('admin.edit.articles.text', savedData);
				    localdata.put('admin.edit.articles.name', this.articles_name);
				    localdata.put('admin.edit.articles.state', this.articles_state);
				    localdata.put('admin.edit.articles.category_select', this.category_select);

				    if(!savedData)
                        return false;

                    self = this;

	                axios.post('/api/admin/articles.edit', {
	                	id: this.$route.params.id,
	                    name: this.articles_name,
	                    category: this.articles_category,
	                    status: this.articles_state,
	                    text: savedData,
	                    headers: {
	                        'Content-Type': 'application/json'
	                    }
	                }).then(response => {

                    	localdata.delete('admin.edit.articles.text');
			            localdata.delete('admin.edit.articles.name');

			            localdata.delete('admin.edit.articles.state');
			            localdata.delete('admin.edit.articles.category_select');

                    	self.$notify({
			              group: 'articles',
			              type: 'success',
			              title: i18n.t('admin_save_message'),
			              text:  i18n.t('admin_user_add_message')
			            });

			        })
			        .catch(error => {
			          Swal.showValidationMessage(
			            i18n.t('swal_error_message')
			          )
			        })
				}).catch((error) => {
					this.$notify({
		              group: 'articles',
		              type: 'error',
		              title: i18n.t('error_title'),
		              text:  error
		            });
				})
        },
        delete_articles (){
	        Swal.fire({
	          title: this.$t('admin_warning2'),
	          showCancelButton: true,
	          confirmButtonColor: '#3085d6',
	          cancelButtonColor: '#d33',
	          confirmButtonText: this.$t('swal_delete_btn'),
	          cancelButtonText: this.$t('swal_cancel_btn')
	        }).then((result) => {
	          if (result.value) {
	            axios.post('/api/admin/articles.delete', {
	              id: this.$route.params.id,
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
	              this.$router.push({ name: 'admin.articles.home' })
	            })
	            .catch(function (error) {
	              console.log(error);
	            }); 
	          }
	        });
	    },
    }
}
</script>
