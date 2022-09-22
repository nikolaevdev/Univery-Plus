import i18n from '~/plugins/i18n';
export const htmljson = { 
  methods: {
    convertDataToHtml(blocks) {

      let self = this;

      var convertedHtml = "";
      blocks.blocks.map(block => {
        
        switch (block.type) {
          case "header":
            convertedHtml += `<h${block.data.level}>${block.data.text}</h${block.data.level}>`;
            break;
          case "embded":
            convertedHtml += `<div><iframe width="560" height="315" src="${block.data.embed}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>`;
            break;
          case "paragraph":
            convertedHtml += `<p>${block.data.text}</p>`;
            break;
          case "delimiter":
            convertedHtml += "<hr />";
            break;
          case "image":
            convertedHtml += `<div class="image-wrap p-4"><img class="img-fluid" src="${block.data.file.url}" title="${block.data.caption}" /><br /><em>${block.data.caption}</em></div>`;
            break;
          case "list":
            convertedHtml += "<ul>";
            block.data.items.forEach(function(li) {
              convertedHtml += self.list_item(li);
            });
            convertedHtml += "</ul>";
            break;
          case "quote":
             convertedHtml += `<div class="quote border rounded-lg py-4 px-3 mb-2">${block.data.text}<br /> <em>${block.data.caption}</em></div>`;
             break;
          case "attaches":
             convertedHtml += `<div class="attaches border shadow-sm rounded-lg p-3 mb-2 d-flex"><div class="flex-grow-1 attaches-title">${block.data.title}</div><div class="attaches-url"><a href="${block.data.file.url}" target="_blabk"> ${this.$t('download')}</a></div></div>`;
             break;
          default:
            console.log("Unknown block type", block.type);
            break;
        }
      });
      return convertedHtml;
    },
    list_item(item) {

      let self = this;
      var convertedHtml = "";


      if(item.items.length> 0){

        convertedHtml += `<li>${item.content}</li>`;
        convertedHtml += "<ul>";

        item.items.forEach(function(li) {

          convertedHtml += `<li>${li.content}</li>`;
          
          self.list_item(li);

        });

        convertedHtml += "</ul>";

      } else convertedHtml += `<li>${item.content}</li>`;

      return convertedHtml;

    }
  }
}