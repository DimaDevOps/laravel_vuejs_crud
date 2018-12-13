<template>
	<div class="container">
			<div class="row">
				<div class="col-md-12">
					<form @submit.prevent="createProduct()">
						<div class="form-group">
							<input type="text" class="form-control"  placeholder="Product Name" v-model="product.name">
						</div>

						<div class="form-group">
							<input type="text" class="form-control"  placeholder="Descripion" v-model="product.description">
						</div>

						<div class="form-group">
							<select class="form-control" name="category_id" id="category_id" v-model="category_id">
							<option v-for="each_category in all_categories" v-bind:value="each_category">{{each_category.name}}</option>
						</select>
						</div>
						

						<button type="submit"  @click="showModal = true" class="btn btn-success">Save</button>


					</form>
					<nav aria-label="Page navigation example">
						<ul class="pagination">
							<li v:bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="get_products(pagination.prev_page_url)"  >Previous</a></li>
							<li class="page-item disabled"><a class="page-link" href="#">Page {{pagination.current_page}} of {{pagination.last_page}}</a></li>
							<li v:bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#"  @click="get_products(pagination.next_page_url)">Next</a></li>
						</ul>
					</nav>
					<div class="table-responsive">      
						<table id="mytable" class="table table-bordred table-striped">
							<thead>
								
								<th>Name</th>
								<th>Descripion</th>
								<th>Category</th>
								<th>Edit</th>
								<th>Delete</th>
							</thead>
							<tbody>
								<tr v-for="product in products">
									
									<td>{{product.name}}</td>
									<td>{{product.description}}</td>
									<td>{{product.category_id.name}}</td>

									<td>
										<p data-placement="top" data-toggle="tooltip" title="Edit">
											<button  @click="editProduct(product)" class="btn btn-primary btn-xs" data-title="Edit">
												<span class="glyphicon glyphicon-pencil"></span>
											</button>
										</p>
									</td>
									<td>
										<p data-placement="top" data-toggle="tooltip" title="Delete">
											<button @click="deleteProduct(product.id)" class="btn btn-danger btn-xs" data-title="Delete">
												<span class="glyphicon glyphicon-trash"></span>
											</button>
										</p>
									</td>
								</tr>  
							</tbody>

						</table>

						<div class="clearfix"></div>
					</div>

				</div>
			</div>
	</div>
</template>

<script>

export default {
	data () {
		return {
			products:[],
			category_id:null,
			all_categories:[],
			product:{
				id:'',
				name:'',
				description:'',
				category_id:'',
			},
			product_id:'',
			pagination:{},
			edit:false
	  }

	},
	created(){
		this.get_products();
	},
	mounted(){
		var self=this;
			axios.get('api/categories')
			.then(response=>{
				this.all_categories=response.data.data;
			})

			.catch(err=>console.log(err));
	},
	methods:{
		get_products(page_url){
			let vm=this;
			page_url=page_url || 'api/products'
			axios.get(page_url)
			.then(response=>{
				this.products=response.data.data;
				vm.makePagination(response.data.meta,response.data.links);
			})
			.catch(err=>console.log(err));

		},

		makePagination(meta,links){
			let pagination={
				current_page:meta.current_page,
				last_page:meta.last_page,
				next_page_url:links.next,
				prev_page_url:links.prev

			}
			this.pagination=pagination;
		},
		deleteProduct(id){
			console.log(id);
			if(confirm("Are you sure?")){
				axios.delete(`/api/products/${id}`)
				.then(response=>{
					alert('Product REMOVED');
					this.get_products();
				})
				.catch(err=>console.log(err));
			}
		},
		
		createProduct(){
			if(this.edit===false){
				let currentObj=this;
				axios.post('api/products', {
					name: this.product.name,
					description: this.product.description,
					category_id: this._data.category_id.id
				})
				.then(response=>{
					this.product.name='';
					this.product.description='';
					this.category_id='';
					this.get_products();
				})
				.catch(function (error) {
					currentObj.output = error;
				});
			}
			else
			{
				axios.put('/api/products', {
					name: this.product.name,
					product_id:this.product.product_id,
					description: this.product.description,
					category_id: this._data.category_id.id
				})
				.then(response=>{
					alert('Product Updated');
					this.product.name='';
					this.product.description='';
					this.category_id='';
					this.get_products();
				})
				.catch(function (error) {
					currentObj.output = error;
				});
			}
		},

		editProduct(product){
			this.edit=true;
			this.product.id=product.id;
			this.product.product_id=product.id;
			this.product.name=product.name;
			this.product.description=product.description;
			this.category_id=product.category_id;

		}
	}
}
</script>

