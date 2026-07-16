@extends('layouts.admin')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <x-table-crud
          :headers="$tableCrud['headers']"
          :list="$tableCrud['list']"
          :actions="$tableCrud['actions']"
          :routes="$tableCrud['routes']"
        />
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
    <!-- /.container-fluid -->
</section>

<script>
    $.ajax({
    url: 'admin/categories/delete',
    type: 'POST',
    data: {
        id: categoryId,
        _token: '{{ csrf_token() }}',
    },
    success: function(response) {
        if (response.status === 'success') {
            alert(response.message);
            location.reload();
        } else {
            alert(response.message);
        }
    },
    error: function(error) {
        alert('Có lỗi xảy ra, vui lòng thử lại.');
    }
});

</script>
@endsection
