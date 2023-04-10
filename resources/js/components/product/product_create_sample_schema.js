import React, { Component } from "react";
import { render } from "react-dom";

import Form from "@rjsf/core";

//const Form = JSONSchemaForm.default;
function transformErrors(errors) {
    console.log(errors);
    return errors.map(error => {
         if (error.name === "pattern") {
            error.message = "Only digits are allowed"
        }
        return error;
    });
}
const uiSchema1 = {
    title: {
        'ui:help': 'در عنوان آگهی به موارد مهم و چشمگیر اشاره کنید.'
    },
    images: {
        'ui:help': 'عکس\u200cهایی از فضای داخل و بیرون ملک اضافه کنید. آگهی\u200cهای دارای عکس تا «۳ برابر» بیشتر توسط کاربران دیده می\u200cشوند.',
        'ui:field': 'CustomImageField',
        'ui:options': {
            min_size: 600,
            manage_url: 'https://divarcdn.com/static',
            upload_url: 'https://divar.ir/upload_s3',
            internal_crop: true,
            manage_bucket: 'manage_pictures',
            upload_bucket: 'temp',
            placeholder_count: 5,
            search_with_media_store: true
        }
    },
    contact: {
        phone: {
            'ui:help': 'توجه: لطفاً پس از ثبت آگهی، از طریق هیچ پیامکی برای پرداخت وجه جهت انتشار آگهی اقدام نکنید.\nکد تأیید به شمارهٔ موبایل شما ارسال خواهد شد. تماس و چت نیز با این شماره انجام می\u200cشود.',
            'ui:options': {
                inputType: 'tel'
            },
            'ui:placeholder': 'شماره موبایل شما (**** *** 0911)'
        },
        'ui:field': 'CustomContactField',
        'ui:order': [
            'phone',
            'chat_enabled'
        ]
    },
    category: {
        'ui:field': 'CustomCategorySelectorField',
        'ui:widget': 'hidden'
    },
    location: {
        'ui:field': 'CustomLocationField'
    },
    'ui:order': [
        'category',
        'location',
        'images',
        'size',
        'post_type',
        'user_type',
        'new_credit',
        'new_rent',
        'rent_to_single',
        'rooms',
        'year',
        'floor',
        'elevator',
        'parking',
        'warehouse',
        'contact',
        'title',
        'description'
    ],
    description: {
        'ui:help': 'در توضیحات آگهی به مواردی مانند شرایط اجاره، جزئیات و ویژگی\u200cهای قابل توجه، دسترسی\u200cهای محلی و موقعیت قرارگیری ملک اشاره کنید.',
        'ui:widget': 'textarea',
        'ui:placeholder': 'توضیحات را بنویسید.'
    },
    user_type: {
        'ui:widget': 'radio'
    },
    size: {
        'ui:valueholder': '%s مترمربع',
        'ui:options': {
            'ui:comma_separated': {
                enabled: true,
                pattern: '###,###'
            }
        }
    },
    new_rent: {
        'ui:placeholder': 'اجاره به تومان',
        'ui:valueholder': '%s تومان',
        'ui:options': {
            'ui:comma_separated': {
                enabled: true,
                pattern: '###,###'
            }
        }
    },
    post_type: {
        'ui:widget': 'radio'
    },
    new_credit: {
        'ui:placeholder': 'ودیعه به تومان',
        'ui:valueholder': '%s تومان',
        'ui:options': {
            'ui:comma_separated': {
                enabled: true,
                pattern: '###,###'
            }
        }
    },
    floor: {
        'ui:widget': 'select'
    },
    rooms: {
        'ui:widget': 'select'
    },
    parking: {
        'ui:widget': 'select'
    },
    elevator: {
        'ui:widget': 'select'
    },
    year: {
        'ui:widget': 'select'
    },
    warehouse: {
        'ui:widget': 'select',
        'ui:secondary_title': 'انباری',
        'ui:placeholder': 'انتخاب'
    },
    rent_to_single: {
        'ui:widget': 'select',
        'ui:secondary_title': 'مناسب برای',
        'ui:placeholder': 'انتخاب'
    }
};
const schema = {
    "result": "success",
    "json_schema": {
        "id": "root",
        "type": "object",
        "title": "آپارتمان",
        "required": [
            "contact",
            "post_type",
            "rooms",
            "category",
            "size",
            "description",
            "location",
            "user_type",
            "title",
            "warehouse",
            "rent_to_single",
            "year",
            "floor",
            "elevator",
            "parking"
        ],
        "properties": {
            "title": {
                "id": "title",
                "type": "string",
                "title": "عنوان آگهی",
                "maxLength": 50,
                "minLength": 3
            },
            "images": {
                "type": "array",
                "items": {
                    "type": "string",
                    "pattern": "^/([a-zA-Z0-9_\\-]*)/([a-zA-Z0-9_\\-\\.]*.jpg)$"
                },
                "title": "عکس آگهی",
                "errors": {
                    "maxItems": "در این دسته‌بندی مجاز به درج ${schema} عکس هستید."
                },
                "maxItems": 20,
                "minItems": 0
            },
            "contact": {
                "type": "object",
                "title": "اطلاعات تماس",
                "required": [
                    "phone"
                ],
                "properties": {
                    "phone": {
                        "id": "phone",
                        "type": "string",
                        "title": "شمارهٔ موبایل",
                        "errors": {
                            "pattern": "شماره تماس به درستی وارد نشده است، لطفا شماره تماس  معتبر ی وارد کنید."
                        },
                        "pattern": "^(09|۰۹|٠٩)[0-9۰-۹٠-٩]{9}$"
                    },
                    "chat_enabled": {
                        "id": "chat_enabled",
                        "type": "boolean",
                        "title": "چت دیوار فعال شود",
                        "default": true
                    }
                }
            },
            "category": {
                "type": "string",
                "title": "دسته‌بندی"
            },
            "location": {
                "type": "object",
                "title": "محدودهٔ آگهی",
                "required": [
                    "city"
                ],
                "properties": {
                    "city": {
                        "id": "place2",
                        "type": "integer",
                        "title": "شهر"
                    },
                    "latitude": {
                        "id": "latitude",
                        "type": "number"
                    },
                    "longitude": {
                        "id": "longitude",
                        "type": "number"
                    },
                    "neighborhood": {
                        "id": "place4",
                        "type": "integer",
                        "title": "محدودهٔ آگهی"
                    },
                    "destination_latitude": {
                        "id": "destination_latitude",
                        "type": "number"
                    },
                    "destination_longitude": {
                        "id": "destination_longitude",
                        "type": "number"
                    }
                }
            },
            "description": {
                "id": "desc",
                "type": "string",
                "title": "توضیحات آگهی",
                "errors": {
                    "maxLength": "طول متن بیشتر از  ${schema} حرف است.",
                    "minLength": "طول متن باید بیشتر از  ${schema} حرف باشد."
                },
                "maxLength": 1000,
                "minLength": 10
            },
            "user_type": {
                "id": "v05",
                "enum": [
                    "شخصی",
                    "مشاور املاک"
                ],
                "type": "string",
                "title": "آگهی‌دهنده"
            },
            "size": {
                "id": "v01",
                "type": "integer",
                "title": "متراژ",
                "maximum": 32767,
                "minimum": 1
            },
            "new_rent": {
                "id": "v10",
                "type": "integer",
                "title": "اجاره",
                "errors": {
                    "minimum": "مقدار ${data} برای قیمت کمتر از حد مجاز است. لطفاً مقدار ${schema} به بالا وارد کنید."
                },
                "maximum": 50000000000,
                "minimum": 0
            },
            "post_type": {
                "id": "v02",
                "enum": [
                    "ارائه",
                    "درخواستی"
                ],
                "type": "string",
                "title": "نوع آگهی"
            },
            "new_credit": {
                "id": "v09",
                "type": "integer",
                "title": "ودیعه",
                "errors": {
                    "minimum": "مقدار ${data} برای قیمت کمتر از حد مجاز است. لطفاً مقدار ${schema} به بالا وارد کنید."
                },
                "maximum": 50000000000,
                "minimum": 0
            },
            "floor": {
                "enum": [
                    "-1",
                    "0",
                    "1",
                    "2",
                    "3",
                    "4",
                    "5",
                    "6",
                    "7",
                    "8",
                    "9",
                    "10",
                    "11",
                    "12",
                    "13",
                    "14",
                    "15",
                    "16",
                    "17",
                    "18",
                    "19",
                    "20",
                    "21",
                    "22",
                    "23",
                    "24",
                    "25",
                    "26",
                    "27",
                    "28",
                    "29",
                    "30",
                    "30+"
                ],
                "type": "string",
                "title": "طبقه",
                "enumNames": [
                    "زیرهمکف",
                    "همکف",
                    "۱",
                    "۲",
                    "۳",
                    "۴",
                    "۵",
                    "۶",
                    "۷",
                    "۸",
                    "۹",
                    "۱۰",
                    "۱۱",
                    "۱۲",
                    "۱۳",
                    "۱۴",
                    "۱۵",
                    "۱۶",
                    "۱۷",
                    "۱۸",
                    "۱۹",
                    "۲۰",
                    "۲۱",
                    "۲۲",
                    "۲۳",
                    "۲۴",
                    "۲۵",
                    "۲۶",
                    "۲۷",
                    "۲۸",
                    "۲۹",
                    "۳۰",
                    "بالاتر از ۳۰"
                ]
            },
            "rooms": {
                "id": "v03",
                "enum": [
                    "بدون اتاق",
                    "یک",
                    "دو",
                    "سه",
                    "چهار",
                    "پنج یا بیشتر"
                ],
                "type": "string",
                "title": "تعداد اتاق"
            },
            "parking": {
                "enum": [
                    "true",
                    "false"
                ],
                "type": "string",
                "title": "پارکینگ",
                "enumNames": [
                    "دارد",
                    "ندارد"
                ]
            },
            "elevator": {
                "enum": [
                    "true",
                    "false"
                ],
                "type": "string",
                "title": "آسانسور",
                "enumNames": [
                    "دارد",
                    "ندارد"
                ]
            },
            "year": {
                "type": "string",
                "enum": [
                    "۱۳۹۹",
                    "۱۳۹۸",
                    "۱۳۹۷",
                    "۱۳۹۶",
                    "۱۳۹۵",
                    "۱۳۹۴",
                    "۱۳۹۳",
                    "۱۳۹۲",
                    "۱۳۹۱",
                    "۱۳۹۰",
                    "۱۳۸۹",
                    "۱۳۸۸",
                    "۱۳۸۷",
                    "۱۳۸۶",
                    "۱۳۸۵",
                    "۱۳۸۴",
                    "۱۳۸۳",
                    "۱۳۸۲",
                    "۱۳۸۱",
                    "۱۳۸۰",
                    "۱۳۷۹",
                    "۱۳۷۸",
                    "۱۳۷۷",
                    "۱۳۷۶",
                    "۱۳۷۵",
                    "۱۳۷۴",
                    "۱۳۷۳",
                    "۱۳۷۲",
                    "۱۳۷۱",
                    "قبل از ۱۳۷۰"
                ],
                "title": "سال ساخت",
                "id": "v12"
            },
            "warehouse": {
                "enum": [
                    "true",
                    "false"
                ],
                "enumNames": [
                    "دارد",
                    "ندارد"
                ],
                "type": "string",
                "title": "انباری"
            },
            "rent_to_single": {
                "enum": [
                    "true",
                    "false"
                ],
                "enumNames": [
                    "خانواده و مجرد",
                    "خانواده"
                ],
                "type": "string",
                "title": "مناسب برای"
            }
        },
        "definitions": {},
        "additionalProperties": false
    },
    "ui_schema": {
        "title": {
            "ui:help": "در عنوان آگهی به موارد مهم و چشمگیر اشاره کنید."
        },
        "images": {
            "ui:help": "عکس‌هایی از فضای داخل و بیرون ملک اضافه کنید. آگهی‌های دارای عکس تا «۳ برابر» بیشتر توسط کاربران دیده می‌شوند.",
            "ui:field": "CustomImageField",
            "ui:options": {
                "min_size": 600,
                "manage_url": "https://divarcdn.com/static",
                "upload_url": "https://divar.ir/upload_s3",
                "internal_crop": true,
                "manage_bucket": "manage_pictures",
                "upload_bucket": "temp",
                "placeholder_count": 5,
                "search_with_media_store": true
            }
        },
        "contact": {
            "phone": {
                "ui:help": "توجه: لطفاً پس از ثبت آگهی، از طریق هیچ پیامکی برای پرداخت وجه جهت انتشار آگهی اقدام نکنید.\nکد تأیید به شمارهٔ موبایل شما ارسال خواهد شد. تماس و چت نیز با این شماره انجام می‌شود.",
                "ui:options": {
                    "inputType": "tel"
                },
                "ui:placeholder": "شماره موبایل شما (**** *** 0911)"
            },
            "ui:field": "CustomContactField",
            "ui:order": [
                "phone",
                "chat_enabled"
            ]
        },
        "category": {
            "ui:field": "CustomCategorySelectorField",
            "ui:widget": "hidden"
        },
        "location": {
            "ui:field": "CustomLocationField"
        },
        "ui:order": [
            "category",
            "location",
            "images",
            "size",
            "post_type",
            "user_type",
            "new_credit",
            "new_rent",
            "rent_to_single",
            "rooms",
            "year",
            "floor",
            "elevator",
            "parking",
            "warehouse",
            "contact",
            "title",
            "description"
        ],
        "description": {
            "ui:help": "در توضیحات آگهی به مواردی مانند شرایط اجاره، جزئیات و ویژگی‌های قابل توجه، دسترسی‌های محلی و موقعیت قرارگیری ملک اشاره کنید.",
            "ui:widget": "textarea",
            "ui:placeholder": "توضیحات را بنویسید."
        },
        "user_type": {
            "ui:widget": "radio"
        },
        "size": {
            "ui:valueholder": "%s مترمربع",
            "ui:options": {
                "ui:comma_separated": {
                    "enabled": true,
                    "pattern": "###,###"
                }
            }
        },
        "new_rent": {
            "ui:placeholder": "اجاره به تومان",
            "ui:valueholder": "%s تومان",
            "ui:options": {
                "ui:comma_separated": {
                    "enabled": true,
                    "pattern": "###,###"
                }
            }
        },
        "post_type": {
            "ui:widget": "radio"
        },
        "new_credit": {
            "ui:placeholder": "ودیعه به تومان",
            "ui:valueholder": "%s تومان",
            "ui:options": {
                "ui:comma_separated": {
                    "enabled": true,
                    "pattern": "###,###"
                }
            }
        },
        "floor": {
            "ui:widget": "select"
        },
        "rooms": {
            "ui:widget": "select"
        },
        "parking": {
            "ui:widget": "select"
        },
        "elevator": {
            "ui:widget": "select"
        },
        "year": {
            "ui:widget": "select"
        },
        "warehouse": {
            "ui:widget": "select",
            "ui:secondary_title": "انباری",
            "ui:placeholder": "انتخاب"
        },
        "rent_to_single": {
            "ui:widget": "select",
            "ui:secondary_title": "مناسب برای",
            "ui:placeholder": "انتخاب"
        }
    },
    "meta_version": 1125
}
const schema1 = {
    type: "object",
    properties: {
        title: {
            title: "عنوان",
            type: "string"
        },
        name: {
            title: "نام",
            type: "string"
        },
        family: {
            title: "فامیل",
            type: "string"
        }
    },
    required: ["name","family"]

};
const schema2 = {
    id: 'root',
    type: 'object',
    title: 'آپارتمان',
    required: [
        'contact',
        'post_type',
        'rooms',
        'category',
        'size',
        'description',
        'location',
        'user_type',
        'title',
        'warehouse',
        'rent_to_single',
        'year',
        'floor',
        'elevator',
        'parking'
    ],
    properties: {
        title: {
            id: 'title',
            type: 'string',
            title: 'عنوان آگهی',
            maxLength: 50,
            minLength: 3
        },
        images: {
            type: 'array',
            items: {
                type: 'string',
                pattern: '^/([a-zA-Z0-9_\\-]*)/([a-zA-Z0-9_\\-\\.]*.jpg)$'
            },
            title: 'عکس آگهی',
            errors: {
                maxItems: 'در این دسته\u200cبندی مجاز به درج ${schema} عکس هستید.'
            },
            maxItems: 20,
            minItems: 0
        },
        contact: {
            type: 'object',
            title: 'اطلاعات تماس',
            required: [
                'phone'
            ],
            properties: {
                phone: {
                    id: 'phone',
                    type: 'string',
                    title: 'شمارهٔ موبایل',
                    errors: {
                        pattern: 'شماره تماس به درستی وارد نشده است، لطفا شماره تماس  معتبر ی وارد کنید.'
                    },
                    pattern: '^(09|۰۹|٠٩)[0-9۰-۹٠-٩]{9}$'
                },
                chat_enabled: {
                    id: 'chat_enabled',
                    type: 'boolean',
                    title: 'چت دیوار فعال شود',
                    'default': true
                }
            }
        },
        category: {
            type: 'string',
            title: 'دسته بندی'
        },
        location: {
            type: 'object',
            title: 'محدودهٔ آگهی',
            required: [
                'city'
            ],
            properties: {
                city: {
                    id: 'place2',
                    type: 'integer',
                    title: 'شهر'
                },
                latitude: {
                    id: 'latitude',
                    type: 'number'
                },
                longitude: {
                    id: 'longitude',
                    type: 'number'
                },
                neighborhood: {
                    id: 'place4',
                    type: 'integer',
                    title: 'محدودهٔ آگهی'
                },
                destination_latitude: {
                    id: 'destination_latitude',
                    type: 'number'
                },
                destination_longitude: {
                    id: 'destination_longitude',
                    type: 'number'
                }
            }
        },
        description: {
            id: 'desc',
            type: 'string',
            title: 'توضیحات آگهی',
            errors: {
                maxLength: 'طول متن بیشتر از  ${schema} حرف است.',
                minLength: 'طول متن باید بیشتر از  ${schema} حرف باشد.'
            },
            maxLength: 1000,
            minLength: 10
        },
        user_type: {
            id: 'v05',
            'enum': [
                'شخصی',
                'مشاور املاک'
            ],
            type: 'string',
            title: 'آگهی\u200cدهنده'
        },
        size: {
            id: 'v01',
            type: 'integer',
            title: 'متراژ',
            maximum: 32767,
            minimum: 1
        },
        new_rent: {
            id: 'v10',
            type: 'integer',
            title: 'اجاره',
            errors: {
                minimum: 'مقدار ${data} برای قیمت کمتر از حد مجاز است. لطفاً مقدار ${schema} به بالا وارد کنید.'
            },
            maximum: 50000000000,
            minimum: 0
        },
        post_type: {
            id: 'v02',
            'enum': [
                'ارائه',
                'درخواستی'
            ],
            type: 'string',
            title: 'نوع آگهی'
        },
        new_credit: {
            id: 'v09',
            type: 'integer',
            title: 'ودیعه',
            errors: {
                minimum: 'مقدار ${data} برای قیمت کمتر از حد مجاز است. لطفاً مقدار ${schema} به بالا وارد کنید.'
            },
            maximum: 50000000000,
            minimum: 0
        },
        floor: {
            'enum': [
                '-1',
                '0',
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
                '10',
                '11',
                '12',
                '13',
                '14',
                '15',
                '16',
                '17',
                '18',
                '19',
                '20',
                '21',
                '22',
                '23',
                '24',
                '25',
                '26',
                '27',
                '28',
                '29',
                '30',
                '30+'
            ],
            type: 'string',
            title: 'طبقه',
            enumNames: [
                'زیرهمکف',
                'همکف',
                '۱',
                '۲',
                '۳',
                '۴',
                '۵',
                '۶',
                '۷',
                '۸',
                '۹',
                '۱۰',
                '۱۱',
                '۱۲',
                '۱۳',
                '۱۴',
                '۱۵',
                '۱۶',
                '۱۷',
                '۱۸',
                '۱۹',
                '۲۰',
                '۲۱',
                '۲۲',
                '۲۳',
                '۲۴',
                '۲۵',
                '۲۶',
                '۲۷',
                '۲۸',
                '۲۹',
                '۳۰',
                'بالاتر از ۳۰'
            ]
        },
        rooms: {
            id: 'v03',
            'enum': [
                'بدون اتاق',
                'یک',
                'دو',
                'سه',
                'چهار',
                'پنج یا بیشتر'
            ],
            type: 'string',
            title: 'تعداد اتاق'
        },
        parking: {
            'enum': [
                'true',
                'false'
            ],
            type: 'string',
            title: 'پارکینگ',
            enumNames: [
                'دارد',
                'ندارد'
            ]
        },
        elevator: {
            'enum': [
                'true',
                'false'
            ],
            type: 'string',
            title: 'آسانسور',
            enumNames: [
                'دارد',
                'ندارد'
            ]
        },
        year: {
            type: 'string',
            'enum': [
                '۱۳۹۹',
                '۱۳۹۸',
                '۱۳۹۷',
                '۱۳۹۶',
                '۱۳۹۵',
                '۱۳۹۴',
                '۱۳۹۳',
                '۱۳۹۲',
                '۱۳۹۱',
                '۱۳۹۰',
                '۱۳۸۹',
                '۱۳۸۸',
                '۱۳۸۷',
                '۱۳۸۶',
                '۱۳۸۵',
                '۱۳۸۴',
                '۱۳۸۳',
                '۱۳۸۲',
                '۱۳۸۱',
                '۱۳۸۰',
                '۱۳۷۹',
                '۱۳۷۸',
                '۱۳۷۷',
                '۱۳۷۶',
                '۱۳۷۵',
                '۱۳۷۴',
                '۱۳۷۳',
                '۱۳۷۲',
                '۱۳۷۱',
                'قبل از ۱۳۷۰'
            ],
            title: 'سال ساخت',
            id: 'v12'
        },
        warehouse: {
            'enum': [
                'true',
                'false'
            ],
            enumNames: [
                'دارد',
                'ندارد'
            ],
            type: 'string',
            title: 'انباری'
        },
        rent_to_single: {
            'enum': [
                'true',
                'false'
            ],
            enumNames: [
                'خانواده و مجرد',
                'خانواده'
            ],
            type: 'string',
            title: 'مناسب برای'
        }
    },
    definitions: {},
    additionalProperties: false
};
const uiSchema = {
    title: {
        'ui:help': 'در عنوان آگهی به موارد مهم و چشمگیر اشاره کنید.'
    },
    images: {
        'ui:help': 'عکس\u200cهایی از فضای داخل و بیرون ملک اضافه کنید. آگهی\u200cهای دارای عکس تا «۳ برابر» بیشتر توسط کاربران دیده می\u200cشوند.',
        'ui:field': 'CustomImageField',
        'ui:options': {
            min_size: 600,
            manage_url: 'https://divarcdn.com/static',
            upload_url: 'https://divar.ir/upload_s3',
            internal_crop: true,
            manage_bucket: 'manage_pictures',
            upload_bucket: 'temp',
            placeholder_count: 5,
            search_with_media_store: true
        }
    },
    contact: {
        phone: {
            'ui:help': 'توجه: لطفاً پس از ثبت آگهی، از طریق هیچ پیامکی برای پرداخت وجه جهت انتشار آگهی اقدام نکنید.\nکد تأیید به شمارهٔ موبایل شما ارسال خواهد شد. تماس و چت نیز با این شماره انجام می\u200cشود.',
            'ui:options': {
                inputType: 'tel'
            },
            'ui:placeholder': 'شماره موبایل شما (**** *** 0911)'
        },
        'ui:field': 'CustomContactField',
        'ui:order': [
            'phone',
            'chat_enabled'
        ]
    },
    category: {
        'ui:field': 'CustomCategorySelectorField',
        'ui:widget': 'hidden'
    },
    location: {
        'ui:field': 'CustomLocationField'
    },
    'ui:order': [
        'category',
        'location',
        'images',
        'size',
        'post_type',
        'user_type',
        'new_credit',
        'new_rent',
        'rent_to_single',
        'rooms',
        'year',
        'floor',
        'elevator',
        'parking',
        'warehouse',
        'contact',
        'title',
        'description'
    ],
    description: {
        'ui:help': 'در توضیحات آگهی به مواردی مانند شرایط اجاره، جزئیات و ویژگی\u200cهای قابل توجه، دسترسی\u200cهای محلی و موقعیت قرارگیری ملک اشاره کنید.',
        'ui:widget': 'textarea',
        'ui:placeholder': 'توضیحات را بنویسید.'
    },
    user_type: {
        'ui:widget': 'radio'
    },
    size: {
        'ui:valueholder': '%s مترمربع',
        'ui:options': {
            'ui:comma_separated': {
                enabled: true,
                pattern: '###,###'
            }
        }
    },
    new_rent: {
        'ui:placeholder': 'اجاره به تومان',
        'ui:valueholder': '%s تومان',
        'ui:options': {
            'ui:comma_separated': {
                enabled: true,
                pattern: '###,###'
            }
        }
    },
    post_type: {
        'ui:widget': 'radio'
    },
    new_credit: {
        'ui:placeholder': 'ودیعه به تومان',
        'ui:valueholder': '%s تومان',
        'ui:options': {
            'ui:comma_separated': {
                enabled: true,
                pattern: '###,###'
            }
        }
    },
    floor: {
        'ui:widget': 'select'
    },
    rooms: {
        'ui:widget': 'select'
    },
    parking: {
        'ui:widget': 'select'
    },
    elevator: {
        'ui:widget': 'select'
    },
    year: {
        'ui:widget': 'select'
    },
    warehouse: {
        'ui:widget': 'select',
        'ui:secondary_title': 'انباری',
        'ui:placeholder': 'انتخاب'
    },
    rent_to_single: {
        'ui:widget': 'select',
        'ui:secondary_title': 'مناسب برای',
        'ui:placeholder': 'انتخاب'
    }
};

const onError = (errors) => console.log("I have", errors.length, "errors to fix");

if (document.getElementById('productCreate')) {
    render((
        <Form schema={schema} uiSchema={uiSchema} showErrorList={false} transformErrors={transformErrors}
        >
            <div className="form-row">
                <div className="form-group col-md-11"><button type="submit" className=" btn btn-block btn-danger">ارسال آگهی</button></div>
                <div className="form-group col-md-1"><button type="button" className=" btn btn-block btn-warning">لغو</button></div>
            </div>
        </Form>

    ), document.getElementById("productCreate"));
}
