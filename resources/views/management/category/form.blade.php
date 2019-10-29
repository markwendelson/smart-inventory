@extends('layouts.authenticated')

@section('content')
<div id="category" class="row justify-content-center content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header form-title">@{{ formState }} Category</div>
                <div class="card-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category_code">Category Code</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid': errors.has('category_code')}"
                                id="category_code"
                                name="category_code"
                                placeholder="Category Code"
                                v-model="category_code"
                                v-validate="'required'"
                            >
                            <small class="text-danger" v-cloak>
                                @{{ errors.first('category_code') }}
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="category_name">Category Name</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid': errors.has('category_name')}"
                                id="category_name"
                                name="category_name"
                                placeholder="Category Name"
                                v-model="category_name"
                                v-validate="'required'"
                            >
                            <small class="text-danger" v-cloak>
                                @{{ errors.first('category_name') }}
                            </small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary rounded-0" v-on:click="submit">Save</button>
                            <a href="{{ route('management.category.index') }}" class="btn btn-danger rounded-0">Cancel</a>
                        </div>
                    </div>
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
                    category_id: '{{ $category->id }}',
                    category_code: '{{ $category->category_code }}',
                    category_name: '{{ $category->category_name }}',
                    isLoading: false,
                }
            },
            methods: {
                submit() {
                    this.$validator.validate().then((valid) => {
                        if (!valid)
                        return

                        // save category if valid
                        let route = '{{ route('api.category.store') }}'
                        let params = {
                            category_id: this.category_id,
                            category_code: this.category_code,
                            category_name: this.category_name
                        }

                        axios.post(route, params)
                        .then((response) => {
                            Swal.fire(
                                'Success',
                                'New category added',
                                response.data.message,
                            ).then(()=>{
                                window.location = '{{ route('management.category.index') }}'
                            })
                        })
                        .catch((err) => {
                            _each(err.response.data.errors, (key, field) => {
                                this.$validator.errors.add({
                                    field: field,
                                    msg: key[0]
                                })
                            })
                        })
                    })

                }

            }
    })
</script>
@endsection
