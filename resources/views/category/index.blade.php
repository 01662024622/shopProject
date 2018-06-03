@extends('layouts.adminheader')
@section('css')
<style type="text/css" media="screen">
.paginate_button{
  color: #fff;
  background-color: #e68f35;
  border-color: #d43f3a;
  padding: 6px;
  border-radius: 5px;
  margin: 2px;
}
</style>

<script src="https://cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>
@endsection
@section('content')

<div class="container" style="width:100%">
  <h2>Categories</h2>
  <br />

  <a href="#"  class="btn btn-info" data-toggle="modal" data-target="#create">+ Thêm mới </a>
  <br><br>
  <table id="users-table" class="table table-striped">
    <thead class="flg">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Parent_Category</th>
        <th>Sort_Order</th>
        <th>Action</th>
      </tr>
    </thead>
  </table>
</div>
<div class="modal fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Cập Nhật Thông Tin Danh Mục</h5>
      </div>
      <div class="modal-body">
        <form action="" method="user" role="form" id="editUser" style=" width:100%" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group">
            <label class="control-label col-sm-2" for="name">Name:</label>
            <input type="text" class="form-control" id="ename" placeholder="Enter name" name="name">
          </div>
          <select name="ecategory_id" id="eparent_id" style="width: 100%" class="form-control">
            <option  value="0" style="font-weight: 900;color:red"><b> <strong>Main Category </strong></b> </option>
            @foreach($categories as $category)
            <option value="{{$category['id']}}">{{$category['name']}}</option>}
            option
            @endforeach
          </select>
          <div class="form-group">
            @if ($errors->has('phone'))
            <span class="errors">{{$errors->first('phone')}}</span>
            @endif
            <label class="control-label" for="phone">Sort_Order:</label>        
            <input type="tel" name="esort_order"  class="form-control" id="esort_order" value="" placeholder="">
          </div>
          <input type="hidden" name="eid" id="eid">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="UpdateBtn" class="btn btn-primary">Save changes</button>
          </div> 
        </form>
      </div>

    </div>
  </div>
</div>
<!-- Modal add -->
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edituser">Thêm Danh Mục</h5>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="addUser" style=" width:100%" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group">
            <label class="control-label col-sm-2" for="name">Name:</label>
            <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2 " for="description">Category:</label>
            <select name="category_id" id="parent_id" style="width: 100%" class="form-control">
              <option  value="0" style="font-weight: 900;color:red"><b> <strong>Main Category </strong></b> </option>}
              @foreach($categories as $category)
              <option value="{{$category['id']}}">{{$category['name']}}</option>}
              option
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="control-label" for="phone">Sort_Order:</label>        
            <input type="tel" name="sort_order"  class="form-control" id="sort_order" value="" placeholder="">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="StoreBtn" class="btn btn-primary">Save changes</button>
          </div> 
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')

{{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> --}}
<script type="text/javascript" charset="utf-8">
  $(function() {
    $('#users-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{!! route('categories.data') !!}',
      columns: [
      { data: 'id', name: 'id' },
      { data: 'name', name: 'name' },
      { data: 'sort_order', name: 'sort_order' },
      { data: 'parent_id', name: 'parent_id' },
      { data: 'action', name: 'action' },
      ]
    });
  });
  $(function () {
    $.ajaxSetup({

      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $('#StoreBtn').on('click',function(e){
      e.preventDefault();
      console.log($('#name').val());
      $.ajax({
        type:'post',
        url:"{{asset('admin/categories/store')}}",
        data:{
          name:$('#name').val(),
          parent_id:$('#parent_id').val(),
          sort_order:$('#sort_order').val(),
        },
        success:function(response){
         console.log(response);
         setTimeout(function () {
           toastr.success('has been added');

                  // 
                },1000);
                // var data = JSON.parse(response).data;
                var html=
                '<tr id="category-'+response.id+'">'+
                '<td>'+response.id+'</td>'+
                '<td>'+response.name+'</td>'+
                '<td>'+response.parent_id+'</td>'+
                '<td>'+response.sort_order+'</td>'+
                '<td>'+
                '<button type="button" class="btn btn-xs btn-info" data-toggle="modal" href="#showProduct"><i class="fa fa-eye" aria-hidden="true"></i></button> '+
                ' <button type="button" class="btn btn-xs btn-warning"data-toggle="modal" onclick="getProduct('+response.id+')" href="#editProduct"><i class="fa fa-pencil" aria-hidden="true"></i></button> '+
                ' <button type="button" class="btn btn-xs btn-danger" onclick="alDelete('+response.id+')"><i class="fa fa-trash" aria-hidden="true"></i></button>'+
                '</td>'+
                '</tr>';
                $('tbody').prepend(html);
                $('#create').modal('hide');

              }, error: function (xhr, ajaxOptions, thrownError) {
                if (!checkNull(xhr.responseJSON.errors)) {
                  console.log(xhr.responseJSON.errors);
                  $('p#sperrors').remove();
                  if(!checkNull(xhr.responseJSON.errors.name))
                  {
                    for (var i = 0; i < xhr.responseJSON.errors.name.length; i++) {
                      var html='<p id="sperrors" style="color:red">'+xhr.responseJSON.errors.name[i]+'</p>';

                      $(html).insertAfter('#name');
                    }
                  };
                  if(!checkNull(xhr.responseJSON.errors.sort_order))
                  {
                    for (var i = 0; i < xhr.responseJSON.errors.sort_order.length; i++) {
                      var html='<p id="sperrors" style="color:red">'+xhr.responseJSON.errors.sort_order[i]+'</p>';
                      console.log(html);
                      $(html).insertAfter('#sort_order');
                    }
                  };
                }
                toastr.error(xhr.responseJSON.message);

              },

            })
    });


  })


  // delete post

  $(document).on('click','.btn-danger',function(){
    var id=$(this).data('id');
    var btn=$(this);
    $.ajax({
      type:'delete',
      url:'categories/'+id,
      success:function(response){
        btn.parent().parent().remove();
        toastr.success(response.message);
      }
    });
  });

  // get data for form update

      // Update function
      $('#UpdateBtn').on('click',function(e){
        e.preventDefault();
        var id=$('#eid').val();
        console.log(id);
        $.ajax({
          type:'post',
          url: "{{ asset('admin/categories/update') }}",
          data:{
            name:$('#ename').val(),
            parent_id:$('#eparent_id').val(),
            sort_order:$('#esort_order').val(),
            id:$('#eid').val(),
          },
          success: function(response){
            console.log(response);
            setTimeout(function () {
              toastr.success(response.name+'has been added');

            },1000);

            $('#editProduct').modal('hide');
            var html=
            '<td>'+response.id+'</td>'+
            '<td>'+response.name+'</td>'+
            '<td>'+response.parent_id+'</td>'+
            '<td>'+response.sort_order+'</td>'+
            '<td>'+
            '<button type="button" class="btn btn-xs btn-info" data-toggle="modal" href="#showProduct"><i class="fa fa-eye" aria-hidden="true"></i></button> '+
            ' <button type="button" class="btn btn-xs btn-warning"data-toggle="modal" onclick="getProduct('+response.id+')" href="#editProduct"><i class="fa fa-pencil" aria-hidden="true"></i></button> '+
            ' <button type="button" class="btn btn-xs btn-danger" onclick="alDelete('+response.id+')"><i class="fa fa-trash" aria-hidden="true"></i></button>'+
            '</td>';
            $('#category-'+response.id).html(html);
          }, error: function (xhr, ajaxOptions, thrownError) {
            if (!checkNull(xhr.responseJSON.errors)) {
              console.log(xhr.responseJSON.errors);
              $('p#sperrors').remove();
              if(!checkNull(xhr.responseJSON.errors.name))
              {
                for (var i = 0; i < xhr.responseJSON.errors.name.length; i++) {
                  var html='<p id="sperrors" style="color:red">'+xhr.responseJSON.errors.name[i]+'</p>';

                  $(html).insertAfter('#name');
                }
              };
              if(!checkNull(xhr.responseJSON.errors.sort_order))
              {
                for (var i = 0; i < xhr.responseJSON.errors.sort_order.length; i++) {
                  var html='<p id="sperrors" style="color:red">'+xhr.responseJSON.errors.sort_order[i]+'</p>';
                  console.log(html);
                  $(html).insertAfter('#sort_order');
                }
              };
            }
            toastr.error(xhr.responseJSON.message);

          },

        })
      });
      function getProduct(id) {
        console.log(id);
        // $('#editPost').modal('show');

        $.ajax({
          type: "GET",
          url: "categories/edit/" + id,

          success: function(response)
          { console.log(response);
            $('#ename').val(response.name);
            $('#eparent_id').val(response.parent_id);
            $('#esort_order').val(response.sort_order);
            $('#eid').val(response.id);
          },
          error: function (xhr, ajaxOptions, thrownError) {
            toastr.error(thrownError);
          }
        });

      }

      // Delete function
      function alDelete(id){
        console.log(id);
        var path = "categories/" + id;
        swal({
          title: "Bạn có chắc muốn xóa?",
        // text: "Bạn sẽ không thể khôi phục lại bản ghi này!!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        cancelButtonText: "Không",
        confirmButtonText: "Có",
        // closeOnConfirm: false,
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            type: "delete",
            url: path,
            success: function(res)
            {

              if(!res.error) {
                toastr.success('Xóa thành công!');
                $('#category-'+id).remove();
                  //setTimeout(function () {
                    //location.reload();
                  //}, 1000)
                }
              },
              error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError);
              }
            });
        } else {
          toastr.error("Thao tác xóa đã bị huỷ bỏ!");
        }
      });
      };
      function checkNull(value){
        return (value == null || value === '');
      }
    </script>
    @endsection
