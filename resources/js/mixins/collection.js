export default {
  data() {
      return {
          dataSet: false,   
          items: [],
          in_edit_modal: null,
          id_delete: null,
          index_delete: null, 
          search_input:'', 
          in_delete_modal: null,
          group_id: 0,
          category_id: 0,
          // for order
          asc_id: true, 
          asc_name: true, 
          asc_fn: true,    //  family_name            
          asc_active: true, 
          order_column: null,
          order: null,
      };
  },
  created() {
      this.fetch();
  }, 
  methods: {
      fetch(page) {
          axios.get(this.url(page)).then(({data})=> {
              this.dataSet = data;
              this.items = data.data;  // pagination 
          });
      }, 
      url(page) {            
          if (! page) {
              let query = location.search.match(/page=(\d+)/);
              page = query ? query[1] : 1;
          }           
          var url = '/api' + this.path + '?page=' + page;
          if( this.search_input.length > 0 ) url += `&search=${this.search_input}`;
          if( this.group_id > 0) url += `&group_id=${this.group_id}`;         
          if( this.id>0 ) url += `&id=${this.id}`; 
          if( this.order_column )  url += `&order_column=${this.order_column}&order=${this.order}`;  
          return url; 
      },
      show_add_modal() {
          this.$modal.show('add_modal');
      },
      add(item){
          this.items.unshift(item);
          this.$modal.hide('add_modal');
      },        
      show_edit_modal(item) {
          this.$modal.show('edit_modal', {'item':item});
      },
      update(){
          this.$modal.hide('edit_modal');
      },
      show_delete_modal(item, index){
          this.$modal.show('delete_modal');
          this.id_delete = item.id; 
          this.index_delete = index; 
          this.in_delete_modal = item; 
      },   
      delete_item(){
          if(this.id_delete){
              axios.delete(this.path + '/' +  this.id_delete).then(({data}) => {
                  if( data.status == 'success' ){
                      this.items.splice(this.index_delete, 1); 
                      this.id_delete=null; 
                      this.index_delete=null; 
                      this.$modal.hide('delete_modal'); 
                  }
                  else{
                      flash( data.message, 'danger'); 
                  }
              });
          }
      },
      delete_item_path(){
          if(this.in_delete_modal !== null){
              axios.delete(this.in_delete_modal.path).then(() => {
                  this.items.splice(this.index_delete, 1); 
                  this.in_delete_modal=null; 
                  this.index_delete=null; 
                  this.$modal.hide('delete_modal'); 
              });
          }
      },
      // order
      set_order(column, order){
          this.order_column = column; 
          this.order = order; 
          this.fetch();
      },
  }
}