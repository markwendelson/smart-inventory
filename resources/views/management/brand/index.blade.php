@extends('layouts.authenticated')

@section('content')
<div id="brand" class="row justify-content-center content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header form-title">Manage Brand</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="search_brand">Search</label>
                            <input type="text" class="form-control-input ml-2" id="search_brand" name="search_brand" placeholder="Search Brand" v-model="searchValue">
                        </div>
                        <div class="col-md-9">
                            <div class="pull-right">
                                <a href="{{ route('management.brand.create') }}" class="btn btn-primary btn-sm rounded-0">Add</a>
                                <button class="btn btn-danger btn-sm rounded-0" v-on:click="removeSelected">Delete</button>
                                <a href="" class="btn btn-success btn-sm rounded-0">Print</a>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check_all" v-on:click="selectAll" v-model="allSelected"></th>
                                <th style="width:200px">Brand Code</th>
                                <th>Brand Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="brand.length">
                                <tr v-for="(brand, index) in brand" :key="index">
                                    <td><input type="checkbox" v-model="brandIds" :value="brand.id" v-on:click="selectedChanged"/></td>
                                    <td>@{{ brand.brand_code }}</td>
                                    <td>@{{ brand.brand_name }}</td>
                                    <td>
                                        <a :href="'brand/edit/' +brand.id" class="btn btn-primary btn-sm rounded-0">Edit</a>
                                    <button class="btn btn-danger btn-sm rounded-0" v-on:click="remove(index, brand.id)">Delete</button>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td class="text-center" colspan="4">No Brand</td>
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
        el: '#brand',
            data () {
                return {
                    brand: [],
                    isLoading: false,
                    brandIds: [],
                    selected: [],
                    allSelected: false,
                    searchValue: '',
                }
            },
            mounted () {
                this.fetchBrand();
            },
            created() {
                this.callPusher();
            },
            methods: {
                fetchBrand() {
                    var app = this
                    app.isLoading = true
                    return axios.get(`/api/v1/brand`)
                    .then((response) => {
                        app.brand = response.data
                    })
                    .catch(() => {})
                    .then(() => {
                        app.isLoading = false
                    })
                },
                selectAll() {
                    this.brandIds = [];

                    if (!this.allSelected) {
                        for (brand in this.brand) {
                            this.brandIds.push(this.brand[brand].id);
                        }
                    }
                },
                edit() {

                },
                remove(index, brand) {
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
                            // this.brand.splice(index, 1);

                            // remove record to database
                            axios.delete('/api/v1/brand/'+brand).then(() => {
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

                    // return if no selected brand
                    if(!this.brandIds.length)
                    return

                    // will continue if has selected brand
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
                            // app.brandIds.forEach((id) => {
                            //     for(var i = 0; i < app.brand.length; i++) {
                            //         if(app.brand[i].id === id) {
                            //             app.brand.splice(i, 1);
                            //         }
                            //     }
                            // });

                            var strIds = app.brandIds.join(",");

                            axios.delete('{{ route('api.brand.destroyMultiple') }}',{
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
                brandSearch() {
                    var app = this
                    app.isLoading = true
                    return axios.get(`/api/v1/brand?q=`+app.searchValue)
                    .then((response) => {
                        app.brand = response.data
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

                    Echo.channel('brandCreated').listen('.brand-created', (e) => {
                        if(!this.searchValue)
                        this.brand.push(e.brand);
                    });

                    Echo.channel('brandUpdated').listen('.brand-updated', (e) => {
                        if(!this.searchValue)
                        this.fetchBrand();
                    });

                    Echo.channel('brandRemoved').listen('.brand-removed', (e) => {
                        if(!this.searchValue)
                        this.fetchBrand();
                    });
                },
                selectedChanged() {
                    this.allSelected = false

                    if(this.brandIds.length == this.brand.length-1)
                        this.allSelected = true
                        return

                }
            },
            watch: {
                searchValue() {
                    this.brandSearch()
                }
            }
    })
</script>
@endsection
