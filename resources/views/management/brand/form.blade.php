@extends('layouts.authenticated')

@section('content')
<div id="brand" class="row justify-content-center content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header form-title">@{{ formState }} Brand</div>
                <div class="card-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="brand_code">Brand Code</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid': errors.has('brand_code')}"
                                id="brand_code"
                                name="brand_code"
                                placeholder="Brand Code"
                                v-model="brand_code"
                                v-validate="'required'"
                            >
                            <small class="text-danger" v-cloak>
                                @{{ errors.first('brand_code') }}
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="brand_name">Brand Name</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid': errors.has('brand_name')}"
                                id="brand_name"
                                name="brand_name"
                                placeholder="Brand Name"
                                v-model="brand_name"
                                v-validate="'required'"
                            >
                            <small class="text-danger" v-cloak>
                                @{{ errors.first('brand_name') }}
                            </small>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary rounded-0" v-on:click="submit">Save</button>
                            <a href="{{ route('management.brand.index') }}" class="btn btn-danger rounded-0">Cancel</a>
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
        el: '#brand',
            data () {
                return {
                    brand_id: '{{ $brand->id }}',
                    brand_code: '{{ $brand->brand_code }}',
                    brand_name: '{{ $brand->brand_name }}',
                    isLoading: false,
                }
            },
            methods: {
                submit() {
                    this.$validator.validate().then((valid) => {
                        if (!valid)
                        return

                        // save category if valid
                        let route = '{{ route('api.brand.store') }}'
                        let params = {
                            brand_id: this.brand_id,
                            brand_code: this.brand_code,
                            brand_name: this.brand_name
                        }
                        
                        axios.post(route, params)
                        .then((response) => {
                            Swal.fire(
                                'Success',
                                'New brand added',
                                response.data.message,
                            ).then(()=>{
                                window.location = '{{ route('management.brand.index') }}'
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
