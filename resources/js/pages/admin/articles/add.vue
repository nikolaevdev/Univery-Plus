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
				            	<input id="articles_title" class="form-control mr-4 rounded-pill" type="text" :placeholder="$t('admin_articles_title')" v-model="name">
				            </div>
						    </div>
				        <div id="editor-js" class="p-3">
				        </div>
							</div>
							<div class="col-12 col-lg-3">
								<div class="wrap-100">
									<form class="p-3">
										<div class="form-group">
										  <button type="button" class="nav-link btn btn-primary w-100 rounded-pill" @click="add_articles()">{{ $t('admin_save') }}</button>
									  </div>
					    		  <div class="form-group">
									    <label for="articles_add_state">{{ $t('admin_articles_state') }}</label>
									    <select class="form-control rounded-pill" id="articles_add_state" v-model="state">
								        <option value="0" selected>{{ $t('admin_articles_state_0') }}</option>
								        <option value="1">{{ $t('admin_articles_state_1') }}</option>
								        <option value="2">{{ $t('admin_articles_state_2') }}</option>
								        <option value="3">{{ $t('admin_articles_state_3') }}</option>
									    </select>
										</div>
										<div class="form-group">
									    <label for="articles_add_category">{{ $t('admin_articles_category') }}</label>
									    <select class="form-control rounded-pill" id="articles_add_category" v-model="category_select">
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

export default {
    scrollToTop: false,

    metaInfo () {
        return { title: this.$t('admin_articles_add') }
    },

    data() {
	    return {
	    	categories: null,
	    	error: null,
	    	editor: null,
	    	name: localdata.get('admin.new.articles.name')[1],
	    	dataarticles:  localdata.get('admin.new.articles.text')[1],
	    	state: localdata.get('admin.new.articles.state')[1],
	    	category_select: localdata.get('admin.new.articles.category_select')[1],
	    }
	},

    created() {
        this.fetchData()
    },

    watch: {
        $route: 'fetchData'
    },

    mounted(){

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
		    data: this.dataarticles,
		    onChange: (data) => {

		    	this.editor.saver.save()
				.then((savedData) => {
				    localdata.put('admin.new.articles.name', this.name);
				    localdata.put('admin.new.articles.state', this.state);
				    localdata.put('admin.new.articles.category_select', this.category_select);
				    localdata.put('admin.new.articles.text', savedData);
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
    },

    methods: {
    	fetchData() {
          this.error = this.categories = null;
          this.loading = true;

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
		    },
        add_articles() {

        	this.editor.saver.save()
					.then((savedData) => {
					    localdata.put('admin.new.articles.text', savedData);
					    localdata.put('admin.new.articles.name', this.name);
					    localdata.put('admin.new.articles.state', this.state);
					    localdata.put('admin.new.articles.category_select', this.category_select);

					    if(!savedData)
	              return false;

                axios.post('/api/admin/articles.add', {
                    name: this.name,
                    category_select: this.category_select,
                    status: this.state,
                    text: savedData,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(response => {

		            if (!response.data.ok) {
		                throw new Error(response.statusText)
		            }
                    if (response.data.ok) {
                    	localdata.delete('admin.new.articles.text');
					            localdata.delete('admin.new.articles.name');

					            localdata.delete('admin.new.articles.state');
					            localdata.delete('admin.new.articles.category_select');

                    	this.$router.push({ name: `admin.articles.get`, params: { id: response.data.new_id }})
                    }
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
    }
}
</script>
