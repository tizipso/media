<style>
    .files > li {
        float: left;
        width: 150px;
        border: 1px solid #eee;
        margin-bottom: 10px;
        margin-right: 10px;
        position: relative;
    }
    .file-icon {
        text-align: left;
        font-size: 25px;
        color: #666;
        display: block;
        float: left;
    }
    .action-row {
        text-align: center;
    }
    .file-name {
        font-weight: bold;
        color: #666;
        display: block;
        overflow: hidden !important;
        white-space: nowrap !important;
        text-overflow: ellipsis !important;
        float: left;
        margin: 7px 0px 0px 10px;
    }
    .file-icon.has-img>img {
         max-width: 100%;
         height: auto;
         max-height: 30px;
     }
     .empty {
         text-align: center;
     }
     .empty i {
         font-size: 36px;
     }
</style>

<script>
Dcat.ready(function () {
    $('.file-delete').click(function () {
        var path = $(this).data('path');
        Dcat.confirm("{{ trans('admin.delete_confirm') }}", null, function() {
            $.ajax({
                method: 'delete',
                url: "{{ $url['delete'] }}",
                data: {
                    'files[]':[path],
                },
                success: function (data) {
                    Dcat.reload();
                    
                    if (typeof data === 'object') {
                        if (data.status) {
                            Dcat.success(data.message);
                        } else {
                            Dcat.error(data.message);
                        }
                    }
                }
            });
        });
    });
    $('#moveModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var name = button.data('name');
        var modal = $(this);
        modal.find('[name=path]').val(name)
        modal.find('[name=new]').val(name)
    });
    $('#urlModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var url = button.data('url');
        $(this).find('input').val(url)
    });
    $('#file-move').on('submit', function (event) {
        event.preventDefault();
        var form = $(this);
        var path = form.find('[name=path]').val();
        var name = form.find('[name=new]').val();
        $.ajax({
            method: 'put',
            url: "{{ $url['move'] }}",
            data: {
                path: path,
                'new': name,
                // _token:LA.token,
            },
            success: function (data) {
                Dcat.reload();

                if (typeof data === 'object') {
                    if (data.status) {
                        Dcat.success(data.message);
                    } else {
                        Dcat.error(data.message);
                    }
                }
            }
        });
        closeModal();
    });
    $('.file-upload').on('change', function () {
        $('.file-upload-form').submit();
    });
    $('#new-folder').on('submit', function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            method: 'POST',
            url: '{{ $url["new-folder"] }}',
            data: formData,
            async: false,
            success: function (data) {
                Dcat.reload();

                if (typeof data === 'object') {
                    if (data.status) {
                        Dcat.success(data.message);
                    } else {
                        Dcat.error(data.message);
                    }
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        closeModal();
    });
    function closeModal() {
        $("#moveModal").modal('toggle');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    }
    $('.media-reload').click(function () {
        Dcat.reload();
    });
    $('.goto-url button').click(function () {
        var path = $('.goto-url input').val();
        Dcat.reload("{{ $url['index'] }}?path=" + path);
    });
    $('.files-select-all').on('ifChanged', function(event) {
        console.log(this);
        if (this.checked) {
            // $('.grid-row-checkbox').check('check');
        } else {
            // $('.grid-row-checkbox').check('uncheck');
        }
    });
    // $('.file-select input').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChanged', function () {
    //     if (this.checked) {
    //         $(this).closest('tr').css('background-color', '#ffffd5');
    //     } else {
    //         $(this).closest('tr').css('background-color', '');
    //     }
    // });
    // $('.file-select-all input').iCheck({checkboxClass:'icheckbox_minimal-blue'}).on('ifChanged', function () {
    //     console.log(this)
    //     if (this.checked) {
    //         $('.file-select input').iCheck('check');
    //     } else {
    //         $('.file-select input').iCheck('uncheck');
    //     }
    // });
    $('.file-select-all input').click(function () {
        // console.log('test');
        if (this.checked) {
            $(".file-select input").prop("checked",true)
            // console.log('checked');
        } else {
            $(".file-select input").prop("checked",false)
            // console.log('unchecked');
        }
    });
    $('.file-delete-multiple').click(function () {
        var files = $(".file-select input:checked").map(function(){
            return $(this).val();
        }).toArray();
        if (!files.length) {
            return;
        }
        Dcat.confirm("{{ trans('admin.delete_confirm') }}", null, function() {
            $.ajax({
                method: 'delete',
                url: "{{ $url['delete'] }}",
                data: {
                    'files[]':files,
                },
                success: function (data) {
                    Dcat.reload();
                    
                    if (typeof data === 'object') {
                        if (data.status) {
                            Dcat.success(data.message);
                        } else {
                            Dcat.error(data.message);
                        }
                    }
                },
            });
        });
    });
    $('table>tbody>tr').mouseover(function () {
        $(this).find('.operate').removeClass('d-none');
    }).mouseout(function () {
        $(this).find('.operate').addClass('d-none');
    });
});
</script>

<div class="row">
    <!-- /.col -->
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body no-padding">
                <div class="mailbox-controls with-border">
                    <div class="btn-group">
                        <a href="" type="button" class="btn btn-default btn media-reload" title="Refresh">
                            <i class="fa fa-refresh"></i>
                        </a>
                        <a type="button" class="btn btn-default btn file-delete-multiple" title="Delete">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </div>
                    <!-- /.btn-group -->
                    <label class="btn btn-default btn"{{-- data-toggle="modal" data-target="#uploadModal"--}}>
                        <i class="fa fa-upload"></i>&nbsp;&nbsp;{{ trans('admin.upload') }}
                        <form action="{{ $url['upload'] }}" method="post" class="file-upload-form" enctype="multipart/form-data" pjax-container>
                            <input type="file" name="files[]" class="hidden file-upload" multiple>
                            <input type="hidden" name="dir" value="{{ $url['path'] }}" />
                            {{ csrf_field() }}
                        </form>
                    </label>

                    <!-- /.btn-group -->
                    <a class="btn btn-default btn" data-toggle="modal" data-target="#newFolderModal">
                        <i class="fa fa-folder"></i>&nbsp;&nbsp;{{ trans('admin.new_folder') }}
                    </a>

                    <div class="btn-group">
                        <a href="{{ route('media-index', ['path' => $url['path'], 'view' => 'table']) }}" class="btn btn-default active"><i class="fa fa-list"></i></a>
                        <a href="{{ route('media-index', ['path' => $url['path'], 'view' => 'index']) }}" class="btn btn-default"><i class="fa fa-th"></i></a>
                    </div>

                    {{--<form action="{{ $url['index'] }}" method="get" pjax-container>--}}
                    <div class="input-group pull-right goto-url" style="width: 250px;">
                        <input type="text" name="path" class="form-control" value="{{ '/'.trim($url['path'], '/') }}">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                    {{--</form>--}}

                </div>

                <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer d-block">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-10">
                        <li class="breadcrumb-item"><a href="{{ route('media-index', ['view' => 'table']) }}"><i class="fa fa-th-list"></i> </a></li>
                        @foreach($nav as $item)
                        <li class="breadcrumb-item"><a href="{{ $item['url'] }}"> {{ $item['name'] }}</a></li>
                        @endforeach
                    </ol>
                </nav>
                @if (!empty($list))
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th width="40px;">
                            <span class="file-select-all">
                            <input type="checkbox" value=""/>
                            </span>
                        </th>
                        <th>{{ trans('admin.name') }}</th>
                        <th></th>
                        <th width="200px;">{{ trans('admin.time') }}</th>
                        <th width="100px;">{{ trans('admin.size') }}</th>
                    </tr>
                    @foreach($list as $item)
                    <tr>
                        <td style="padding-top: 15px;">
                            <span class="file-select">
                            <input type="checkbox" value="{{ $item['name'] }}"/>
                            </span>
                        </td>
                        <td>
                            {!! $item['preview'] !!}
                            <a @if(!$item['isDir'])target="_blank"@endif href="{{ $item['link'] }}" class="file-name" title="{{ $item['name'] }}">
                            {{ $item['icon'] }} {{ basename($item['name']) }}
                            </a>
                        </td>

                        <td class="action-row">
                            <div class="operate d-none">
                                <button class="shadow-none btn btn-link text-primary btn-sm file-rename p-2" data-toggle="modal" data-target="#moveModal" data-name="{{ $item['name'] }}"><i class="fa fa-edit"></i></button>
                                <button class="shadow-none btn btn-link text-primary btn-sm file-delete p-2" data-path="{{ $item['name'] }}"><i class="fa fa-trash"></i></button>
                                @unless($item['isDir'])
                                <a target="_blank" href="{{ $item['download'] }}" class="shadow-none btn btn-link text-primary btn-sm p-2"><i class="fa fa-download"></i></a>
                                @endunless
                                <button class="shadow-none btn btn-link btn-sm text-primary p-2" data-toggle="modal" data-target="#urlModal" data-url="{{ $item['url'] }}"><i class="fa fa-internet-explorer"></i></button>
                            </div>

                        </td>
                        <td>{{ $item['time'] }}&nbsp;</td>
                        <td>{{ $item['size'] }}&nbsp;</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <div class="jumbotron bg-white empty">
                    <i class="feather icon-inbox text-primary"></i>
                    <p class="text-secondary">Empty</p>
                </div>
                @endif
            </div>
            <!-- /.box-footer -->
            <!-- /.box-footer -->
        </div>
        <!-- /. box -->
    </div>
    <!-- /.col -->
</div>

<div class="modal fade" id="moveModal" tabindex="-1" role="dialog" aria-labelledby="moveModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="moveModalLabel">Rename & Move</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="file-move">
            <div class="modal-body">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Path:</label>
                    <input type="text" class="form-control" name="new" />
                </div>
                <input type="hidden" name="path"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="urlModal" tabindex="-1" role="dialog" aria-labelledby="urlModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="urlModalLabel">Url</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newFolderModal" tabindex="-1" role="dialog" aria-labelledby="newFolderModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="newFolderModalLabel">New folder</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="new-folder">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" />
                    </div>
                    <input type="hidden" name="dir" value="{{ $url['path'] }}"/>
                    {{ csrf_field() }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>