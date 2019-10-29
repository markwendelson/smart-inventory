@extends('layouts.authenticated')

@section('content')
<div class="row justify-content-center content">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header form-title">Manage Category</div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width:200px">Category Code</th>
                                <th>Category Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CAT-001</td>
                                <td>Category 1</td>
                            </tr>
                            <tr>
                                <td>CAT-002</td>
                                <td>Category 2</td>
                            </tr>
                            <tr>
                                <td>CAT-003</td>
                                <td>Category 3</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
