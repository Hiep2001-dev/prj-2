@extends('layouts.admin-auth')
@section('content-auth')
<div class="login-box" style="width:80%; max-width:450px;">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="text-center card-header">
      <p class="h3"><b>Xác Thực Tài Khoản Thành Công</b></p>
    </div>
    <div class="mt-2 card-body">
      <p class="login-box-msg text-success">
          Chúc mừng bạn tài khoản của bạn đã được xác thực thành công
      </p>
      <div class="row">
          <!-- /.col -->
          <div class="text-center col-12">
            <a href="{{route('user.home')}}">Tiếp Tục</a>
          </div>
          <!-- /.col -->
      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
@endsection
