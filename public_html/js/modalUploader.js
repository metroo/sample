var showModalUploader = (function () {

    var opts = {
        maxFilesize : 2,
        csrf : '',
        filesize: 2,
        maxFiles: 1,
        acceptedFileType: ['image' , 'sound'  , 'video'],
        acceptedFiles: 'image',
        acceptedFilesList : {"image":".jpeg,.jpg,.png,.gif" , "sound" : ".mp3", "video" : ".mp4,.mkv"},
        publicUrl : '',
        modaluploadurl : '',
        modaluploadid : '',
        formFieldId : '',
        palceImageId : '',
        formFieldUrl : '',
        arrFileUploadModal : [],
        modaluploaded : false,
        uploader : '',
        modalfunc : function (){},
        item : [],
        listMultiFile : [],
        newDropzone : null
    };
    function  init(options){
        opts = $.extend(opts, options);

        var LI_POSITION = 'li_position';
        $('ul#sortable').sortable({
            //observe the update event...
            update: function(event, ui) {
                /*console.log('update sortable')
                //create the array that hold the positions...
                var order = [];
                //loop trought each li...
                $('#sortable li').each( function(e) {

                    //add each li position to the array...
                    // the +1 is for make it start from 1 instead of 0
                    order.push( $(this).attr('id')  + '=' + ( $(this).index() + 1 ) );
                });
                // join the array as single variable...
                var positions = order.join(';')
                //use the variable as you need!
                // alert( positions );
                // $.cookie( LI_POSITION , positions , { expires: 10 });*/
                $(opts.formFieldId).val(getMultiFileId());
            }
        });

        jsGrid.locale("fr");
        if($("#gridwrapper")) {
            $("#gridwrapper").jsGrid({
                pagerContainer: "#externalPager",
                pagerFormat: "صفحات : {first} {prev} {pages} {next} {last} &nbsp;&nbsp; {pageIndex} از {pageCount}",
                height: "400",
                width: "100%",
                sorting: true,
                paging: true,
                pageLoading: true,
                autoload: true,
                pageSize: 5,
                pageButtonCount: 5,
                editing: true,
                filtering: true,
                clearFilterButton: true,                        // show clear filter button
                modeSwitchButton: true,
                heading: true,

                controller: {
                    loadData: function (filter) {
                        var d = $.Deferred();
                        filter['file_type'] = opts.acceptedFileType.indexOf(opts.acceptedFiles) + 1;
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: opts.publicUrl + "/admin/upload",
                            data: filter,
                            dataType: "json"
                        }).done(function (response) {
                            d.resolve({
                                data: response.results,
                                itemsCount: response.count
                            });
                        });

                        return d.promise();

                    },
                    insertItem: function (item) {
                        //POST /photos	store
                        return $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: opts.publicUrl + "/admin/upload",
                            data: item
                        });
                    },
                    updateItem: function (item) {
                        console.log(item);
                        //PUT/PATCH  /photos/{photo} update
                        return $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "PUT",
                            url: opts.publicUrl + "/admin/upload/" + item.id,
                            data: item
                        });
                    },
                    deleteItem: function (item) {
                        console.log(item.id);
                        //DELETE	/photos/{photo}
                        return $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "DELETE",
                            url: opts.publicUrl + "/admin/upload/" + item.id,
                            data: item
                        });
                    }
                },
                fields: [
                    {width: 40, type: "control"},
                    {
                        width: 40, editButton: false, deleteButton: false,
                        itemTemplate: function (value, item) {
                            var $result = jsGrid.fields.control.prototype.itemTemplate.apply(this, arguments);

                            var $customEditButton = $("<button>انتخاب</button>").attr({class: "btn btn-block btn-primary select-file-from-listgrid"})
                                .click(function (e) {
                                    console.log(item);
                                    opts.item = item;
                                    opts.listMultiFile.push(item)
                                    opts.modalfunc();
                                    //alert("ID: " + item.id);
                                    e.stopPropagation();
                                });
                            return $("<div>").append($customEditButton);
                            //return $result.add($customButton);
                        }
                    },
                    {
                        name: 'filename',width: 100,  sorting: false, filtering: false, title: "پیش نمایش",
                        itemTemplate: function (value , r) {
                            switch (r.file_type){
                                case 0 :
                                    return $(" <span/>");
                                    break;
                                case 1 :
                                    return $(" <img height='40px' src='"+opts.publicUrl + value + "'>");
                                    break;
                                case 2 :
                                    return $('<div class="media-wrapper">'+
                                        '<audio  preload="none" controls style="max-width:100%;">'+
                                        '<source src="'+opts.publicUrl + value +'" type="audio/mp3">'+
                                        '</audio>'+
                                        '</div>');
                                    break;
                                case 3 :
                                    break;
                            }

                        }
                    },
                    {
                        name: 'id',
                        width: 50,
                        title: "شماره",
                        align: "center",
                        type: "number",
                        editing: false,
                        sorting: true,
                        filtering: true
                    },
                    {name: 'subject', title: "عنوان", type: "text",width: 100},
                    {name: 'original_name', title: " نام فایل ", type: "text" ,width: 100},
                ]
            });
            //$("#gridwrapper").jsGrid("option", "filtering", false);
        }

        dropzoneOptions = {
            dictDefaultMessage: 'جهت بارگذاری ، فایل را اینجا رها کنید یا اینجا کلیک کنید',
            dictCancelUpload: 'لغو بارگذاری',
            dictCancelUploadConfirmation: 'آیا از لغو بارگذاری اطمینان دارید ؟',
            dictRemoveFile: 'حذف فایل',
            dictFileTooBig: 'حداقل اندازه فایل برای بارگذاری {{maxFilesize}} مگ می باشد، اندازه فایل {{filesize}} ',
            dictInvalidFileType: 'نوع فایل صحیح نمی باشد',
            dictRemoveFileConfirmation: 'آیا برای حذف اطمینان دارید',
            dictMaxFilesExceeded: ' فقط امکان بارگذاری {{maxFiles}} فایل فراهم می باشد ',
            paramName: "file",
            maxFilesize: opts.maxFilesize, // MB
            addRemoveLinks: true,
            init: function () {
                this.on("success", function (file, e) {
                    console.log('success upload ',file,e);
                    $('#selectfilemodal').show();
                    opts.modaluploaded = true;
                    opts.modaluploadurl = e.path;
                    opts.modaluploadid = e.ids[0].id;
                    //$('#category_imagelogo_id')
                    console.log("success > ", e);
                });
                this.on("error", function (e) {
                    console.log('error', e);
                    opts.modaluploaded = false;
                });
                this.on("addedfiles", function (e) {
                    if (e[0].accepted) {
                        opts.arrFileUploadModal.push(e[0].lastModified);
                    }
                    console.log('addedfiles', e[0]);
                });
                this.on("complete", function (file) {
                    //this.removeAllFiles(true);
                });
            },
            maxFiles: 2,
            acceptedFiles: opts.acceptedFilesList[opts.acceptedFiles],
            removedfile: function (file) {
                console.log('delete file', file);
                var fileName = file.name;

                for (var i = 0; i < opts.arrFileUploadModal.length; i++) {
                    if (opts.arrFileUploadModal[i] === file.lastModified) {
                        opts.arrFileUploadModal.splice(i, 1);
                    }
                }

                $.ajax({
                    type: 'POST',
                    url: opts.publicUrl + '/admin/upload',
                    data: {name: fileName, request: 'delete'},
                    sucess: function (data) {
                        console.log('success: ' + data);
                    }
                });

                var _ref;
                return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
            }
        };



        $('#uploader').remove();
        //if(typeof opts.newDropzone === 'undefined' || opts.newDropzone == null)
        opts.uploader =  $('#dropzone');
        //opts.newDropzone = null;
        opts.uploader.append('<form action="'+opts.publicUrl+'/admin/upload" class="dropzone" id="uploader"\n' +
            '                                                              enctype="multipart/form-data">\n' +
            opts.csrf +
            '                                                            <div class="form-group row">\n' +
            '                                                                <label for="inputTitle" class="col-sm-2 col-form-label">عنوان</label>\n' +
            '\n' +
            '                                                                <div class="col-sm-10">\n' +
            '                                                                    <input type="text" class="form-control" id="inputTitle"  name="inputTitle" placeholder="عنوان">\n' +
            '                                                                </div>\n' +
            '                                                            </div> <div class="alert alert-danger" role="alert">\n' +
            '                                                                جهت انتصاب عنوان به فایل ها ، قبل از انتخاب فایل ، کادر عنوان را تکمیل یا تغییر دهید\n' +
            '                                                            </div>\n' +
            '                                                            <div class="dz-message" data-dz-message><span>جهت بارگذاری فایل را اینجا رها کنید یا اینجا کلیک کنید</span></div>\n' +
            '\n' +
            '\n' +
            '                                                        </form>');
        $('#uploader').dropzone(dropzoneOptions)
        //opts.newDropzone = new Dropzone(jj, dropzoneOptions);
        /*else {
            //Dropzone.options.myDropzone
            opts.newDropzone.options.acceptedFiles = opts.acceptedFilesList[opts.acceptedFiles];
        }*/
    }

    function showModal() {
        opts.modalfunc = function () {
            var purl = opts.publicUrl;
            switch (opts.item.file_type) {
                case 0 : break;
                case 1 :
                    $(opts.palceImageId ).html('<img src="' + purl + opts.item.filename + '" height="120px">');
                    break;
                case 2 :
                    $(opts.palceImageId ).html('<div class="media-wrapper">'+
                        '<audio  preload="none" controls style="max-width:100%;">'+
                        '<source src="'+ purl +  opts.item.filename  +'" type="audio/mp3">'+
                        '</audio>'+
                        '</div>');
                    break;
                case 3 :  $(opts.palceImageId ).html('<div class="media-wrapper">'+
                    '<video id="player1"  style="max-width:100%;" ' +
                    'poster="" preload="none" controls playsinline webkit-playsinline data-mejsoptions="{stretching:fill}">' +
                    '<source src="'+ purl +  opts.item.filename  +'" type="video/mp4">' +
                    '<track srclang="en" kind="subtitles" src="mediaelement.vtt">' +
                    '<track srclang="en" kind="chapters" src="chapters.vtt">' +
                    '</video></div>'
                );
                break;
            }
            $(opts.formFieldUrl ).val( purl + opts.item.filename);
            $(opts.formFieldId ).val(opts.item.id);
            $('#theModal').modal('hide');
        }
        console.log('show start');
        $(document).ready(function () {
            //opts.newDropzone.removeAllFiles(true);
            $('#uploader')[0].reset();
            $('#theModal').modal('show');
            $('#selectfilemodal').hide();

            $('#selectfilemodal').on('click', function () {
                if (opts.arrFileUploadModal.length) {
                    $(opts.palceImageId).html('<img src="' + opts.publicUrl + opts.modaluploadurl + '" height="120px">')
                    $(opts.formFieldUrl).val(opts.modaluploadurl);
                    $(opts.formFieldId).val(opts.modaluploadid);
                    $('#theModal').modal('hide');
                }
            });

        });
    }

    function showMultiModal() {
        opts.modalfunc = function () {
            var len = $('#sortable li').length;++len;
            $(opts.palceImageId).append(
                '<li id="image-sort-'+opts.item.id+'">'+
                '<span class="page-layout  " id="layouts-pages-gallery-slider"> ' +
                '<img class="page-layout-image" src="'+opts.publicUrl + opts.item.filename +' ">' +
                ' <span class="delete btn btn-danger btn-block mt-1 ">' +
                '<i class=" fas fa-trash "></i>' +
                '</span>' +
                '</span>'+
                ' </li>');
            $('#theModal').modal('hide');
            $(opts.formFieldId).val(getMultiFileId());

        }
        $(document).on('click', '.delete', function() {
            $(this).parent().parent().remove();
            $(opts.formFieldId).val(getMultiFileId());
        });



        console.log('show start');
        $(document).ready(function () {
            //opts.newDropzone.removeAllFiles(true);
            $('#uploader')[0].reset();
            $('#theModal').modal('show');
            $('#selectfilemodal').hide();

            /*$('#selectfilemodal').on('click', function () {
                if (opts.arrFileUploadModal.length) {
                    $(opts.logoimg_add_id).html('<img src="' + opts.publicUrl + opts.modaluploadurl + '" height="120px">')
                    $(opts.logourl_add_id).val(opts.modaluploadurl);
                    $(opts.logo_imagelogo_id).val(opts.modaluploadid);
                    $('#theModal').modal('hide');
                }
            });*/

        });
    }

    function hideModal(){
        $('#theModal').modal('hide');
    }

    function getMultiFileId() {
        var order = [];
        $('#sortable li').each( function(e) {
            //add each li position to the array...
            // the +1 is for make it start from 1 instead of 0
            order.push( $(this).attr('id') );
        });
        return order;
    }

    return {
        initialize: function (settings) {
            init(settings);
        },
        show:  function () {
            showModal();
        },
        showMulti:  function () {
            showMultiModal();
        },
        hide:  function () {
            hideModal();
        },
        arraySize : function (){
          return opts.arrFileUploadModal;
        },
        opts : function (){
            return opts;
        },
        getMultiFileId : function () {
           return getMultiFileId();
        }
    }
}());
