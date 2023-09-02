@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>Create new group</h2>

                        <form action="{{route("group.store")}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label for="inputTitleEn" class="form-label">Group Name</label>
                                    <input type="text" class="form-control" id="GroupName" name="name" value="">
                                </div>
                                <div class="mb-3">
                                    <label for="inputTitleEn" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="Description" name="description" value="">
                                </div>
                                <div class="">
                                    <a href="https://culture.oia.nsysu.edu.tw/manage/form" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
