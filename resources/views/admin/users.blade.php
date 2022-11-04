<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TollLater (Admin) - User Management</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">
</head>

<body>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">TollLater <span class="badge bg-danger">Admin</span></a>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="/admin/logout">Sign out</a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">
                                <span data-feather="home"></span>
                                User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file"></span>
                                Card Management
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">User Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-primary">
                            Create User
                        </button>
                    </div>
                </div>

                <form>
                    <div class="input-group mb-3">
                        <select class="form-select" style="max-width:200px" name="type">
                            <option value="fullname" @if($type=='fullname') selected @endif>Full Name</option>
                            <option value="ic_no" @if($type=='ic_no') selected @endif>IC No.</option>
                            <option value="email" @if($type=='email') selected @endif>E-mail</option>
                            <option value="mobile_no" @if($type=='mobile_no') selected @endif>Phone Number</option>
                        </select>
                        <input type="text" class="form-control" name="keyword" value="{{$keyword}}" aria-label="Text input with dropdown button">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Full Name</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Created On</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 0;
                            ?>
                            @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->fullname}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{date('d M Y, h:iA', strtotime($user->created_at));}}</td>
                                <td><a href="/admin/user/{{$user->id}}">Edit</a> | <a href="/admin/delete/{{$user->id}}" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a></td>
                            </tr>
                            <?php $count++; ?>
                            @endforeach

                            @if($count == 0)
                            <tr>
                                <td colspan="4" class="text-center">
                                    No result found.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </main>
        </div>
    </div>
    <script src="/js/bootstrap.bundle.min.js"></script>
</body>

</html>