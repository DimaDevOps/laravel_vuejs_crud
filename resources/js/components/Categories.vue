<template>
	<div class="container">
			<div class="row">
				<div class="col-md-12">
					<form @submit.prevent="createCategory()">
						<div class="form-group">
							<input type="text" class="form-control"  placeholder="Category Name" v-model="category.name">
						</div>

						<div class="form-group">
							<input type="text" class="form-control"  placeholder="Descripion" v-model="category.description">
						</div>


						<button type="submit"  @click="showModal = true" class="btn btn-success">Save</button>


					</form>
					<nav aria-label="Page navigation example">
						<ul class="pagination">
							<li v:bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="get_categories(pagination.prev_page_url)">Previous</a></li>
							<li class="page-item disabled"><a class="page-link" href="#">Page {{pagination.current_page}} of {{pagination.last_page}}</a></li>
							<li v:bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#"  @click="get_categories(pagination.next_page_url)">Next</a></li>
						</ul>
					</nav>
					<div class="table-responsive">      
						<table id="mytable" class="table table-bordred table-striped">
							<thead>
								
								<th>Name</th>
								<th>Descripion</th>
								<th>Edit</th>
								<th>Delete</th>
							</thead>
							<tbody>
								<tr v-for="category in categories">
									
									<td>{{category.name}}</td>
									<td>{{category.description}}</td>
									<td>
										<p data-placement="top" data-toggle="tooltip" title="Edit">
											<button  @click="editCategory(category)" class="btn btn-primary btn-xs" data-title="Edit">
												<span class="glyphicon glyphicon-pencil"></span>
											</button>
										</p>
									</td>
									<td>
										<p data-placement="top" data-toggle="tooltip" title="Delete">
											<button @click="deleteCategory(category.id)" class="btn btn-danger btn-xs" data-title="Delete">
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
			categories:[],
			category:{
				id:'',
				name:'',
				description:'',
			},
			category_id:'',
			pagination:{},
			edit:false
	  }
	},
	created(){
		this.get_categories();
	},
	methods:{
		get_categories(page_url){
			let vm=this;
			page_url=page_url || 'api/categories'
			axios.get(page_url)
			.then(response=>{
				this.categories=response.data.data;
				vm.makePagination(response.data.meta,response.data.links);
			})
			.then(data=>{
				this.category.name='';
				this.category.description='';
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
			console.log(pagination);
			this.pagination=pagination;
		},
		deleteCategory(id){
			console.log(id);
			if(confirm("Are you sure?")){
				axios.delete(`/api/categories/${id}`)
				.then(response=>{
					alert('Category REMOVED');
					this.get_categories();
				})
				.catch(err=>console.log(err));
			}
		},
		createCategory(){
			if(this.edit===false){
				let currentObj=this;
				axios.post('api/categories', {
					name: this.category.name,
					description: this.category.description,
				})
				.then(response=>{
					alert('Category Added');
					this.get_categories();
				})
				.catch(function (error) {
					currentObj.output = error;
				});
			}
			else
			{
				axios.put('/api/categories', {
					name: this.category.name,
					category_id:this.category.category_id,
					description: this.category.description,

				})
				.then(response=>{
					alert('Category Updated');
					this.get_categories();
				})
				.catch(function (error) {
					currentObj.output = error;
				});
			}
		},

		editCategory(category){
			this.edit=true;
			this.category.id=category.id;
			this.category.category_id=category.id;
			this.category.name=category.name;
			this.category.description=category.description;

		}
	}
}
</script>

