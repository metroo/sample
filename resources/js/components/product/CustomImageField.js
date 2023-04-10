import React, { Component } from "react";
import PropTypes from "prop-types";
import { render } from "react-dom";
import HiddenWidget from "@rjsf/core/lib/components/widgets/HiddenWidget";
import DropzoneComponent from 'react-dropzone-component';
/*
composer require intervention/image 2.4
*/

// Define a custom component for handling the root position object
class CustomImageField extends React.Component {
    constructor(props) {
        super(props);
        //console.log("props",props);
        this.state = {...props.formData};
        this.djsConfig = {
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            uploadMultiple: false,
            maxFilesize: 1,
            maxFiles: 7,
            dictRemoveFile: "حذف فایل",
            dictCancelUpload: "لغو",
            dictDefaultMessage: "<span><span>عکس آگهی را اینجا بکشید و رها کنید یا</span><mark> اینجا را کلیک کنید</mark></span>",
            dictMaxFilesExceeded: "شما نمی توانید فایل دیگری اضافه کنید",
            dictUploadCanceled: "لغو انتقال",
            dictFileTooBig : "سایز فایل {{filesize}} می باشد و باید بیشتر از {{maxFilesize}} نباشد",
            acceptedFiles: "image/jpeg,image/png,image/gif"
        };
        this.componentConfig = {
            iconFiletypes: ['.jpg', '.png', '.gif'],
            showFiletypeIcon: true,
            postUrl: 'upload',
            renameFile: function (file) {
                let newName = new Date().getTime() + '_' + file.name;
                return newName;
            }
        };
    }

    componentDidMount() {
        var me = this;
        $.each(pImages, function(i, item) {
            $('#imagesUpload').append($('<div class="image-area col-md-2 col-sm-3 mb-3 "><img class=" rounded img-thumbnail " src="'+public_path+pImages[i].resized_name+'" ' +
                'height=\'120px\'   />  <a class="remove-image" href="#" style="display: inline;" rel="'+pImages[i].id+'"><i class="fas fa-trash-alt"/></a></div>'));
            //alert(pImages[i].resized_name);

        });

        $(".remove-image").click(function(){
            //alert($(this).attr('rel'))
            console.log($(this));
            if($(this).attr('rel')) me.removeFile($(this).attr('rel') , -1)
            $(this).parent().remove()
        });
    }

    onChange(name) {
        return (event) => {
            //console.log("name" , name)
           // console.log("event" , event.target)
            this.setState({
                [name]: (event.target.value)
            }, () => this.props.onChange(this.state));
        };
    }

    handleFileSuccess(file) {
        console.log ("success" , file);
        var p = JSON.parse(file.xhr.response);
        var uuidw = file.upload.uuid;
        const ids = p.ids;
        /*for (const [key, value] of Object.entries(ids)) {
            console.log(key, value);
            if(!ProductImages.includes(value))
                ProductImages.push(value)
        }*/
        p.ids.forEach(item => {

            var poi = Object.entries(item);
            var key =  poi[0][0];
            var value =  poi[0][1];
            var i = 0;
            ProductImages.forEach(item1 => {
                if(item1[key] == value )
                    i++;
            })
            if(i == 0 ) {
                var io = {}
                io[uuidw] = value;
                console.log(io)
                ProductImages.push(io)
            }
        });
    }
    handleFileAdded(file) {

    }
    handleFileremoved(file) {
        console.log ("remove", file);
        var imageId = -1;
        var i = 0;var indexId = -1;
        ProductImages.forEach(item => {
            if(item[file.upload.uuid] !== undefined ){
                imageId = item[file.upload.uuid];
                indexId = i;
            }
            i++;
        });
        if(imageId != -1 ) this.removeFile(imageId , indexId)
    }

    removeFile(imageId , indexId){
        $.post({
            url: 'fileDelete',
            data: {id: imageId, _token: $('[name="_token"]').val()},
            dataType: 'json',
            success: function (data) {
                //total_photos_counter--;
                // $("#counter").text("# " + total_photos_counter);
                //var ii = ProductImages.indexOf(id);
                if(indexId == -1){
                    $.post({
                        url: 'fileDeleteUpdate',
                        data: {id: imageId, _token: $('[name="_token"]').val()},
                        dataType: 'json',
                        success: function (data) {

                        }
                    });
                }else {
                    ProductImages.splice(indexId,1);
                }

            }
        });
    }

    render() {
        const djsConfig =  this.djsConfig;
        const componentConfig = this.componentConfig;
        this.state = this.props.formData;
        const value = JSON.stringify(this.state);
        const id = this.props.idSchema.$id;
        const eventHandlers = {
            addedfile: this.handleFileAdded.bind(this),
            success: this.handleFileSuccess.bind(this),
            removedfile: this.handleFileremoved.bind(this)
        }
        return (
            <div className="col-12">
                <b className="mb-1" >  عکس آگهی
                </b>
            <DropzoneComponent config={this.componentConfig} eventHandlers={eventHandlers}
                               djsConfig={this.djsConfig}   ></DropzoneComponent>
            <div id="imagesUpload" className="row text-center text-lg-left"></div>
            </div>
        );
        this.handleFileAdded.call(thisDropzone, mockFile);
    }
}
export default CustomImageField;
