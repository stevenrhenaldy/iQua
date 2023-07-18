@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>Settings</h2>

                        <form action="{{ route('group.update', $group->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-3">
                                    <label for="inputTitleEn" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="GroupName" name="name"
                                        value="{{ $group->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="inputTitleEn" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="Description" name="description"
                                        value="{{ $group->description }}">
                                </div>
                                <div class="">
                                    <a href="https://culture.oia.nsysu.edu.tw/manage/form"
                                        class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                        <div class="row my-2">
                            <div class="col-12">
                                <ul class="list-group">
                                    <li class="list-group-item active">Members</li>
                                    @foreach ($group->groupUsers as $user)
                                        <a class="text-decoration-none" href="">
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-9">
                                                        <b>
                                                            {{ $user->name }}
                                                        </b>
                                                    </div>
                                                    <div class="col-3 text-end">
                                                        {{ $user->role }}
                                                    </div>
                                                </div>
                                            </li>
                                        </a>
                                    @endforeach
                                </ul>
                                <div class="my-2 d-grid">
                                    <button data-bs-toggle="modal" data-bs-target="#sendEmailModal" class="btn btn-success">
                                        Add Member
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('group.update', $group->id) }}">
                @csrf
                @method('PUT')
                <input type="text" name="action" value="add-member" hidden>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="inputEmail" name="email"
                                value="" placeholder="someone@email.com">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
