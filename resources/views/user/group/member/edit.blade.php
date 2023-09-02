@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>Member</h2>
                        @if ($groupUser->accepted_at)

                        <form action="{{ route('group.member.update', [$group->uuid, $groupUser->id]) }}" method="post">
                            @csrf
                            @method("PUT")
                            <input type="text" name="action" value="edit" hidden>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="GroupName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="GroupName" name="name"
                                        value="{{ $groupUser->name_alias }}" placeholder="{{ $groupUser->user?->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="Account" class="form-label">Account</label>
                                    <input type="text" class="form-control" id="Account"
                                        value="@ {{ $groupUser->user?->username }}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="inputRole" class="form-label">Role</label>
                                    @if($groupUser->role == "owner")
                                    <input type="text" class="form-control" id="Account"
                                        value="Owner" disabled>
                                    @else
                                    <select name="role" class="form-select" id="inputTimezone">
                                        <option value="member" {{$groupUser->role == 'member' ? 'selected' :''}}>Member</option>
                                        <option value="administrator" {{$groupUser->role == 'administrator' ? 'selected' :''}}>Administrator</option>
                                    </select>
                                    @endif
                                </div>

                                <div class="">
                                    <a href="{{route("group.edit", $group->uuid)}}"
                                        class="btn btn-secondary">Back</a>
                                    @if ($groupUser->role != 'owner')
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#removeMemberModal"  class="btn btn-danger">Remove</button>
                                    @endif
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                        @else
                        <div class="alert alert-warning" role="alert">
                            Waiting to join...
                            {{-- <hr>
                            You may also send this invitation link to them
                            <div class="input-group">
                                <input type="url" id="url" class="form-control" value="https://culture.oia.nsysu.edu.tw/page/performance" readonly="" wfd-id="id1">
                                <button class="btn btn-primary" type="button" id="copy-url">Copy</button>
                            </div> --}}
                        </div>
                        <div class="">
                            <a href="{{route("group.edit", $group->uuid)}}"
                                class="btn btn-secondary">Back</a>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#removeMemberModal"  class="btn btn-danger">Cancel invitation</button>

                        </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="removeMemberModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('group.member.destroy', [$group->uuid, $groupUser->id]) }}">
                @csrf
                @method('DELETE')
                <input type="text" name="action" value="add-member" hidden>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Remove Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p>
                                Are you sure you want to {{$groupUser->accepted_at?"remove ".$groupUser->name:"cancel the invitation"}}?
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">{{$groupUser->accepted_at?"Remove":"Cancel"}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="module">
        $('#copy-url').click((event) => {
            // Get the text field
            var copyText = document.getElementById("url");

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);

            // Alert the copied text
            alert("Copied the URL: " + copyText.value);
        });
    </script>
@endsection
