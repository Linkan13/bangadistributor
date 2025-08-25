@extends('backEnd.master')

@section('mainContent')
<div class="container">
    <div class="list-container">
        <h4 class="mb-4 text-left">Distributors List</h4>

        <table class="table table-bordered table-hover" id="distributorTable">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection