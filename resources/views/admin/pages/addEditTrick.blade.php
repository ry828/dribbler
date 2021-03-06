@extends("admin/admin_app")


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/form_select2.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/form_inputs.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/components_modals.js') }}"></script>


    <script>
        $(function () {
            var description_count = 0;
            var description_text = "{{isset($trick) ? $trick->trick_description : ''}}";
            description_text = description_text.replace(/&quot;/g, '\"');
            console.log(description_text);

            $("#btn_add_description").on("click", function () {
                add_description_box("", "", "");
                init_inputbox_style();
            });
            function add_description_box(title, thumbnail, description) {
               html = '';
               html += '<ul style="list-style-type: none">' +
                       '<li style="padding: 8px">' +
                       '<div class="form-group">\n' +
                       '    <label class="control-label col-lg-2"> Title</label>\n' +
                       '    <div class="col-lg-10">\n' +
                       '        <input type="text" class="form-control" placeholder="Title" name="title[]" value="'+title+'" required>\n' +
                       '    </div>\n' +
                       '</div>' +
                       '</li>' +
                       '<li style="padding: 8px">' +
                       '<div class="form-group">\n' +
                        '    <label class="control-label col-lg-2">Photo</label>\n' +
                        '    <div class="col-lg-10">\n' +
                        '        <input type="file" class="description-file-input form-control" name="picture[]" accept=".png, .jpg" ' +
                   'data-allowed-file-extensions=\'["png", "jpg"]\' data-show-caption="true" value="'+thumbnail+'" required>\n' +
                        '    </div>\n' +
                        '</div>' +
                        '</li>' +
                       '<li style="padding: 8px">' +
                       '<div class="form-group">\n' +
                        '    <label class="control-label col-lg-2"> Description</label>\n' +
                        '    <div class="col-lg-10">\n' +
                        '        <textarea class="form-control" placeholder="Description" name="description[]" value="'+description+'" required>' + description + '</textarea>\n' +
                        '    </div>\n' +
                        '</div>' +
                        '</li>' +
                        '<li style="padding: 12px">' +
                        '<a class="delete_description" style="float:right">Delete</a>' +
                        '</li>' +

                    '</ul>';
               $('#div_description').append(html);
               $("#div_description").css('display', '');
               description_count ++;

           }
           $("#div_description").on("click", ".delete_description", function () {
               if (description_count > 1) {
                   $(this).parent().parent().remove();
                   description_count --;
               }

           });
            function init_inputbox_style() {
                $('.description-file-input').fileinput({
                    browseLabel: 'Browse',
                    browseIcon: '<i class="icon-file-plus"></i>',
                    uploadIcon: '<i class="icon-file-upload2"></i>',
                    removeIcon: '<i class="icon-cross3"></i>',
                    showUpload: false,
                    layoutTemplates: {
                        icon: '<i class="icon-file-check"></i>'
                    },
                    initialCaption: "No file selected"
                });
            }
            if (description_text != '') {
                var arr_description = JSON.parse(description_text);
                for (var i in arr_description) {
                    add_description_box(arr_description[i].title,arr_description[i].thumbnail, arr_description[i].description);
                }

            } else {
                add_description_box("", "", "");
            }


        });
    </script>
    <script type="text/javascript">
        $(function(){
            $('.file-input, .description-file-input').fileinput({
                browseLabel: 'Browse',
                browseIcon: '<i class="icon-file-plus"></i>',
                uploadIcon: '<i class="icon-file-upload2"></i>',
                removeIcon: '<i class="icon-cross3"></i>',
                showUpload: false,
                layoutTemplates: {
                    icon: '<i class="icon-file-check"></i>'
                },
                initialCaption: "No file selected"
            });

        });
    </script>
@endsection


@section("content")
<!-- Main content -->
<div class="content-wrapper">

    <!-- Page header -->
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h3><span class="text-semibold">Trick Details</span></h3>
            </div>
        </div>

        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="{{ URL::to('/admin/tricks')}}"><i class="icon-home2 position-left"></i>Tricks</a></li>
                @if(isset($trick))
                <li class="active">{{ $trick->trick_id }}</li>
                @endif
            </ul>
        </div>
    </div>
    <!-- /page header -->

    <!-- Content area -->
    <div class="content">
        <!-- Error Message -->
        @if (count($errors) > 0)
            <div class="alert alert-danger no-border">
                <ul>
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                    @foreach ($errors->all() as $error)
                        <li>
                            <span class="text-semibold">{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if(Session::has('flash_message'))
            <div class="alert alert-success no-border">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">{{ Session::get('flash_message') }}</span>
            </div>
        @endif

        <div class="row">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <form class="form-horizontal" action="@if (isset($trick)) {{url('/admin/tricks/'.$trick->trick_id.'/update')}} @else {{url('/admin/tricks/create')}} @endif" enctype="multipart/form-data" method="post">
                        {{ csrf_field() }}
                        <div class="form-group" hidden="true">
                            <label class="control-label col-lg-2"> Trick ID</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="" name="trick_id" value="{{isset($trick) ? $trick->trick_id : '0'}}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2"> Trick title</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="Trick title" name="trick_title" value="{{isset($trick) ? $trick->trick_title : ''}}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2"> Description</label>
                            <div class="col-lg-10">

                                {{--<input type="text" placeholder="description" class="form-control" name="trick_description" value="{{isset($trick) ? $trick->trick_describtion : ''}}" required>--}}
                                <div class="panel" id="div_description">

                                </div>
                                <button type="button" class="btn btn-default" id="btn_add_description"><i class="icon-plus3 position-left"></i> Add</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2"> Category</label>
                            <div class="col-lg-10">
                                <select class="select" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{$category->category_id}}">{{$category->category_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Trick tags</label>
                            <div class="col-lg-10">
                                <select multiple="multiple" class="select" name="trick_tags[]" data-placeholder="Select tags" required>
                                    @if (isset($trick))
                                        @foreach($tags as $tag)
                                            <option value="{{$tag->tag_id}}"
                                                @foreach($trickTags as $trickTag)
                                                    @if ($trickTag->tag_id == $tag->tag_id)
                                                        selected
                                                    @endif
                                                @endforeach>{{$tag->tag_name}}</option>
                                        @endforeach
                                    @else
                                        @foreach($tags as $tag)
                                            <option value="{{$tag->tag_id}}">{{$tag->tag_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Thumbnail</label>
                            <div class="col-lg-10">
                                <input type="file" class="file-input" name="thumbnail" accept=".png, .jpg" data-allowed-file-extensions='["png", "jpg"]' data-show-caption="true" @if (!isset($trick)) required @endif>
                            </div>
                        </div>

<!--                         <div class="form-group">
                            <label class="control-label col-lg-2">LD Video</label>
                            <div class="col-lg-10">
                                <input type="file" class="file-input" name="ld_video" accept=".mp4" data-allowed-file-extensions='["mp4"]' data-show-caption="true">
                            </div>
                        </div>
 -->
                        <div class="form-group">
                            <label class="control-label col-lg-2">Video</label>
                            <div class="col-lg-10">
                                <input type="file" class="file-input" name="hd_video" accept=".mp4" data-allowed-file-extensions='["mp4"]' data-show-caption="true" @if (!isset($trick)) required @endif>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success pull-right col-lg-2">@if (isset($trick)) Update Trick @else Create Trick @endif<i class="icon-arrow-right14 position-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
</div>
<!-- /main content -->
@endsection



