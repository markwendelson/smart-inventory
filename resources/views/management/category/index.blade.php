@extends('layouts.authenticated')

@section('content')
<div id="category" class="row justify-content-center content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header form-title">Manage Category</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="search_category">Search</label>
                            <input type="text" class="form-control-input ml-2" id="search_category" name="search_category" placeholder="Search Category" v-model="searchValue">
                        </div>
                        <div class="col-md-9">
                            <div class="pull-right">
                                <a href="{{ route('management.category.create') }}" class="btn btn-primary btn-sm rounded-0">Add</a>
                                <button class="btn btn-danger btn-sm rounded-0" v-on:click="removeSelected">Delete</button>
                                <a href="" class="btn btn-success btn-sm rounded-0">Print</a>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check_all" v-on:click="selectAll" v-model="allSelected"></th>
                                <th style="width:200px">Category Code</th>
                                <th>Category Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="categories.length">
                                <tr v-for="(category, index) in categories" :key="index">
                                    <td><input type="checkbox" v-model="categoryIds" :value="category.id" v-on:click="selectedChanged"/></td>
                                    <td>@{{ category.category_code }}</td>
                                    <td>@{{ category.category_name }}</td>
                                    <td>
                                        <a :href="'category/edit/' +category.id" class="btn btn-primary btn-sm rounded-0">Edit</a>
                                    <button class="btn btn-danger btn-sm rounded-0" v-on:click="remove(index, category.id)">Delete</button>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td class="text-center" colspan="4">No category</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra_scripts')
<script type="text/javascript">
    new Vue({
        el: '#category',
            data () {
                return {
                    categories: [],
                    isLoading: false,
                    categoryIds: [],
                    selected: [],
                    allSelected: false,
                    searchValue: '',
                }
            },
            mounted () {
                this.fetchCategory();
            },
            created() {
                this.callPusher();
            },
            methods: {
                fetchCategory() {
                    var app = this
                    app.isLoading = true
                    return axios.get(`/api/v1/category`)
                    .then((response) => {
                        app.categories = response.data
                    })
                    .catch(() => {})
                    .then(() => {
                        app.isLoading = false
                    })
                },
                selectAll() {
                    this.categoryIds = [];

                    if (!this.allSelected) {
                        for (category in this.categories) {
                            this.categoryIds.push(this.categories[category].id);
                        }
                    }
                },
                edit() {

                },
                remove(index, category) {
                    this.isLoading = true
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            // remove record from table
                            // this.categories.splice(index, 1);

                            // remove record to database
                            axios.delete('/api/v1/category/'+category).then(() => {
                                Swal.fire(
                                'Deleted!',
                                'Record has been deleted.',
                                'success'
                                )
                            })
                        }
                    })

                    this.isLoading = false
                },
                removeSelected() {

                    // return if no selected category
                    if(!this.categoryIds.length)
                    return

                    // will continue if has selected category
                    var app = this

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {

                            // iterate selected ids then remove in collection
                            // app.categoryIds.forEach((id) => {
                            //     for(var i = 0; i < app.categories.length; i++) {
                            //         if(app.categories[i].id === id) {
                            //             app.categories.splice(i, 1);
                            //         }
                            //     }
                            // });

                            var strIds = app.categoryIds.join(",");

                            axios.delete('{{ route('api.category.destroyMultiple') }}',{
                                params: {
                                    ids: strIds
                                }
                            }).then(() => {
                                Swal.fire(
                                    'Deleted!',
                                    'Record has been deleted.',
                                    'success'
                                )
                            })

                        }
                    })
                },
                categorySearch() {
                    var app = this
                    app.isLoading = true
                    return axios.get(`/api/v1/category?q=`+app.searchValue)
                    .then((response) => {
                        app.categories = response.data
                    })
                    .catch(() => {})
                    .then(() => {
                        app.isLoading = false
                    })
                },
                callPusher() {
                    Pusher.logToConsole = true;

                    var Echo = new LaravelEcho({
                        broadcaster: 'pusher',
                        key: '{{ env("PUSHER_APP_KEY") }}',
                        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                        encrypted: true
                    });

                    Echo.channel('categoryCreated').listen('.category-created', (e) => {
                        if(!this.searchValue)
                        this.categories.push(e.category);
                    });

                    Echo.channel('categoryUpdated').listen('.category-updated', (e) => {
                        if(!this.searchValue)
                        this.fetchCategory();
                    });

                    Echo.channel('categoryRemoved').listen('.category-removed', (e) => {
                        if(!this.searchValue)
                        this.fetchCategory();
                    });
                },
                selectedChanged() {
                    this.allSelected = false

                    if(this.categoryIds.length == this.categories.length-1)
                        this.allSelected = true
                        return

                }
            },
            watch: {
                searchValue() {
                    this.categorySearch()
                }
            }
    })
</script>
@endsection
