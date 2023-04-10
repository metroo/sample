import React, { Component } from "react";
import { render } from "react-dom";
import CustomCategorySelectorField from "./CustomCategorySelectorField";

import Form from "@rjsf/core";

function transformErrors(errors) {
    console.log("errorsss");
    console.log(errors);
    console.log("errorsss");
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


// Define the custom field components to register; here our "geo"
// custom field component
const fields = {CustomCategorySelectorField: CustomCategorySelectorField};


const onError = (errors) => console.log("I have", errors.length, "errors to fix");
const onSubmit = ({formData}, e) => {
    $('.productCreateOverly').show();
    $("#root_categorys").val(ProductCategories);
    console.log("Data submitted: ",  formData)
    var p = {};
    p.sc_data = formData;
    p.sc_id = ProductSlug;
    p.title = formData.title;
    p.categories = ProductCategories;
    axios({
        method: 'post',
        url: mainUrl+'/dashboard/product',
        data: p,
        config: { headers: {'Content-Type': 'multipart/form-data' }}
    })
    .then(function (response) {
        //handle success
        console.log(response)
        if(response.data.success) {
            $('.productCreateOverly').hide();
            //window.location = mainUrl+'/dashboard/product';
        }
    })
    .catch(function (response) {
        //handle error
        $('.productCreateOverly').hide();
        console.log(response)
    });
};


if (document.getElementById('productCreate')) {
    $('#btnCatDone').on('click', function () {
        BsDrillDown.doneSelection(function (currentNode, confirmFn) {
            if(currentNode.ID != -1) {
                const scforms = {
                    result: 'success',
                    json_schema: {
                        id: 'root',
                        type: 'object',
                        title: 'املاک',
                        properties: {
                            title: {
                                id: 'title',
                                type: 'string',
                                title: '1عنوان آگهی'
                            },

                            categorys :{
                                 type :"string",
                                 title :"دسته‌بندی"
                            },

                        },
                        definitions: {},
                        additionalProperties: false
                    },
                    ui_schema: {
                        title: {
                            'ui:help': 'در عنوان آگهی به موارد مهم و چشمگیر اشاره کنید.'
                        },
                        categorys : {
                            'ui:field' : 'CustomCategorySelectorField'
                        }
                    },
                    meta_version: 1125
                };
                var selection = confirmFn();
                ProductCategories = selection.slice(0);
                ProductCategories.shift();
                buyButtonReact(scforms , {title: "ProductCategories", categorys: ProductCategories});
                /*$.get( mainUrl+"/dashboard/schema/"+currentNode.ID, function( data ) {
                    buyButtonReact(data , { "category" : ProductCategories});
                });*/
                console.log(selection);
                console.log(currentNode.ID);
                ProductSlug = currentNode.ID;
                $("#categoryContainer").hide();
                $("#categoryInfo").show();
                $("#categoryInfoTitle").html(currentNode.Name);
            }
        });
    });
    function buyButtonReact(scform , formDate){
        console.log("this form data",formDate);
        $("#productCreate").show();
        render((
            <Form  onError={onError} onSubmit={onSubmit} fields={fields}
                  schema={scform.json_schema} uiSchema={scform.ui_schema} showErrorList={false}
                  noHtml5Validate transformErrors={transformErrors}
            >
                <div className="form-row">
                    <div className="form-group col-md-12">
                        <button type="submit" className=" btn btn-block btn-danger">
                            ارسال آگهی
                        </button>
                    </div>

                </div>

            </Form>

        ), document.getElementById("productCreate"));
    }
}

