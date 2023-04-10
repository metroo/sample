import React, { Component } from "react";
import PropTypes from "prop-types";
import ReactDOM, { render } from "react-dom";
import HiddenWidget from "@rjsf/core/lib/components/widgets/HiddenWidget";
import CustomCategorySelectorField from "./CustomCategorySelectorField";
import CustomImageField from "./CustomImageField";
import Form from "@rjsf/core";
import CustomLocationField from "./CustomLocationField";

// Define a custom component for handling the root position object
class ProductCreate extends React.Component {
    componentWillMount() {

    }

    componentDidMount() {

    }

    constructor(props) {
        super(props);
        this.state = {
            formData : {}
        }

    }

    transformErrors(errors) {
        console.log("errorsss");
        console.log(errors);
        //console.log("errorsss");
        return errors.map(error => {
            if (error.name === "pattern") {
                error.message = "لطفا در وارد کردن نوع داده دقت فرمایید"
            }if (error.name === "required") {
                error.message = "وارد کردن فیلد اجباری می باشد"
            }if (error.name === "maximum") {
                error.message = "از حد مجاز بیشتر است"
            }if (error.name === "minimum") {
                error.message = "از حد مجاز کمتر است"
            }if (error.name === "type") {
                error.message = "لطفا در وارد کردن نوع داده دقت فرمایید"
            }
            return error;
        });
    }


    render() {

        console.log('i have run')
        var me = this;
        me.scforms = {};
        me.buyButtonReact = function (scform , formData){

            me.setState({formData : formData});
            //console.log('newform',formData)
            //console.log("buy bit" , me.state);
            $("#productCreate2").show();
            render((
            <Form   formData={this.state.formData} onError={me.onError} onSubmit={me.onSubmit} fields={me.fields}

                   schema={scform.sc_json.json_schema} uiSchema={scform.sc_json.ui_schema} showErrorList={false}
                   noHtml5Validate transformErrors={me.transformErrors}
            >


                <div className="form-row">
                    <div className="form-group col-md-12">
                        <button type="submit" className=" btn btn-block btn-primary">
                            ارسال آگهی
                        </button>
                    </div>

                </div>

            </Form>
            ), document.getElementById("productCreate2"));
            /* <div className="dropzone"></div>
            var myDropzone = new Dropzone(".dropzone", {
                url: "upload",

                paramName: "file",
                maxFilesize: 2,
                maxFiles: 2,
                autoProcessQueue: true,
                acceptedFiles: "image/*,application/pdf"
            });*/
            /*var myDropzone = new Dropzone('div#fallback', {
                url: 'http://httpbin.org/post',
                uploadMultiple: true,
                parallelUploads: 10
            });*/
        }
        //CustomLocationField.state.formDate = this.state.formData;
        me.fields = {
            CustomLocationField : CustomLocationField ,
            CustomCategorySelectorField: CustomCategorySelectorField ,
            CustomImageField:CustomImageField
        };
        me.onError = (errors) => console.log("I have", errors.length, "errors to fix");
        me.onSubmit = ({formData}, e) => {

            ProductImages.forEach(item => {
                var poi = Object.entries(item);
                var value =  poi[0][1];
                imagesUploads.push(value);
            });

            pImages.forEach(item => {
                imagesUploads.push(item.id)
            });

            $('.productCreateOverly').show();
            $("#root_categorys").val(ProductCategories);
            console.log("Data submitted: ",  formData)
            var p = {};
            p.sc_data = formData;
            p.sc_id = ProductSlug;
            p.categories = ProductCategories;
            p.images = imagesUploads;
            axios({
                method: formMethod,
                url: formUrl,
                data: p,
                config: { headers: {'Content-Type': 'multipart/form-data' }}
            })
                .then(function (response) {
                    //handle success
                    console.log(response)
                    if(response.data.success) {
                        $('.productCreateOverly').hide();
                        window.location = mainUrl+'/dashboard/product';
                    }else {
                        $('.productCreateOverly').hide();
                        Toast.fire({
                            icon: 'error',
                            title: ' خطا در بروزرسانی اطلاعات '
                        })
                    }
                })
                .catch(function (response) {
                    //handle error
                    $('.productCreateOverly').hide();
                    console.log(response)
                });
        };

        $('#btnCatDone').on('click', function () {
            console.log("itemClick");
            //console.log(BsDrillDown.getCurrentNode());
             if(!Object.keys(BsDrillDown.getCurrentNodeFull().Child).length) {
                 //if($("#categoryContainer").is(":hidden"))
                 if(categoryShow){
                     categoryShow = false;
                     BsDrillDown.doneSelection(function (currentNode, confirmFn) {
                         if (currentNode.ID != -1) {

                             var selection = confirmFn();
                             ProductCategories = selection.slice(0);
                             ProductCategories.shift();
                             console.log("itemClickBTN");
                             console.log("ProductCategories" , ProductCategories);
                             /*me.buyButtonReact(JSON.parse(scforms), {
                                 title: "ProductCategories",
                                 category: ProductCategories.toString()
                             });*/
                             if(!ProductCategories.length)
                                 ProductCategories = currentNodes;
                             $.get( mainUrl+"/dashboard/schema/"+currentNode.ID, function( data ) {
                                  //me.buyButtonReact(data , { "category" : ProductCategories});
                                 FormDatas.category = ProductCategories.toString();
                                  me.buyButtonReact(data , FormDatas);
                              });
                            // console.log("selection", selection);
                           //  console.log("currentNode.ID", currentNode.ID);
                             ProductSlug = currentNode.ID;

                         }

                         $("#categoryContainer").hide();
                         $("#categoryInfo").show();
                         $("#categoryInfoTitle").html(currentNode.Name);
                     });
                 }
             }else {
                 Toast.fire({
                     icon: 'error',
                     title: ' لطفا زیرگروه مربوطه را انتخاب نمایید '
                 })
             }

        });
        return (
           <div id="productCreate2"></div>
        );
    }

}

export default ProductCreate;

if (document.getElementById('productCreate')) {
    ReactDOM.render(<ProductCreate />, document.getElementById('productCreate'));
}


