import React, { Component } from "react";
import TextWidget from "@rjsf/core/lib/components/widgets/TextWidget";
import {asNumber, guessType} from "@rjsf/core/lib/utils";


function getValue(event, multiple) {
    if (multiple) {
        return [].slice.call(event.target.options).filter(function (o) {
            return o.selected;
        }).map(function (o) {
            return o.value;
        });
    } else {
        return event.target.value;
    }
}


function processValue(schema, value) {
    // "enum" is a reserved word, so only "type" and "items" can be destructured
    var type = schema.type,
        items = schema.items;

    if (value === "") {
        return undefined;
    } else if (type === "array" && items && nums.has(items.type)) {
        return value.map(asNumber);
    } else if (type === "boolean") {
        return value === "true";
    } else if (type === "number") {
        return asNumber(value);
    } // If type is undefined, but an enum is present, try and infer the type from
    // the enum values


    if (schema["enum"]) {
        if (schema["enum"].every(function (x) {
            return guessType(x) === "number";
        })) {
            return asNumber(value);
        } else if (schema["enum"].every(function (x) {
            return guessType(x) === "boolean";
        })) {
            return value === "true";
        }
    }

    return value;
}

class CustomLocationField extends React.Component {
        constructor(props) {
        super(props);
         console.log("props up",props);
        //this.state = {...props.formData };
        this.state = { ...props.formData  , teams: [] ,  value: '?', selectedTeam: "" , locationComboLoad : false, cityId:props.idSchema.city.$id}

        //  this.citis = null;
    }
    maploader(){
        var me = this;
        console.log('map loader');
        var index = cities.map(function (img) { return img.id; }).indexOf(me.props.formData.city);
        if(index >-1)
            mymap = L.map('mapid').setView([cities[index].latitude, cities[index].longitude], 13);
        else
            mymap = L.map('mapid').setView([36.304897, 59.577873], 13);
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png' ).addTo(mymap);

        if("location" in FormDatas){
            if("latitude" in FormDatas.location){
                if(marker!= null)
                    marker.remove();
                marker = L.marker({lat : FormDatas.location.latitude , lng : FormDatas.location.longitude}).addTo(mymap);
            }
        }
        var popup = L.popup();
        function onMapClick(e) {
            if(marker!= null)
                marker.remove();
            marker = L.marker(e.latlng).addTo(mymap);
           // console.log(e.latlng.lat)
            me.setState(state => ({formData: {location :{latitude : e.latlng.lat,longitude:e.latlng.lng}}}));
            //console.log(me.props.formData)
            var p = me.props.formData;
            p.latitude = e.latlng.lat;
            p.longitude= e.latlng.lng;
            me.props.onChange(p)
            //popup.setLatLng(e.latlng).setContent("You clicked the map at " + e.latlng.toString()).openOn(mymap);
        }
        mymap.on('click', onMapClick);
    }

    onChange(name) {
        //var me = this;
        return (event) => {
            console.log("name" , name)
            console.log("event" , event.target)
            this.setState({
                [name]: (event.target.value)
            }, () => this.props.onChange(this.state.formData));
        };

    }

    onChange2(name) {
        var me = this;
        console.log("what");
        console.log(me.props);
        console.log(me.state);
        console.log(name);

        if(me.state.locationComboLoad) {
            //alert("asdf");
            return (e) => {
                console.log("event", e.target);
                this.setState({selectedTeam: e.target.value}, () => this.props.onChange(this.state));
            }
        }else {
            return (e) => {
                console.log("event", e.target);
                this.setState({selectedTeam: e.target.value}, () => this.props.onChange(this.state));
            }
        }
    }
    componentDidMount() {
        var me = this;
        console.log('map componentDidMount');
        //var $this = $(ReactDOM.findDOMNode(this));
        me.maploader();
        $(function () {
            $('.select2city').select2();
            $('.select2city').on('select2:select', function (e) {
                 var index = cities.map(function (img) { return img.id; }).indexOf(parseInt(e.params.data.id));
                 if(index >-1) {
                     $('.select2city').val(cities[index].id); // Select the option with a value of '1'
                     $('.select2city').trigger('change');
                     //$('#'+me.state.cityId).value(e.params.data.id);
                     if (mymap != null) {
                         me.setState(state => ({formData: {location :{city : cities[index].id}}}));
                         var p = {city : cities[index].id};
                         me.props.onChange(p)
                         console.log('state on change',me.state)
                         mymap.setView([cities[index].latitude, cities[index].longitude], 13);
                     }
                 }
            });
        });
    }

    render() {
        var me = this;
        const {formData1 ,  teams , valueq , selectedTeam ,cityIds } = this.state;
        let props1 = me.props;
        // me.sestate({  selected  : props.formData  })
        var schema = props1.schema,
            id = props1.idSchema.$id,
            cityId = props1.idSchema.city.$id,
            latitudeId = props1.idSchema.latitude.$id,
            longitudeId = props1.idSchema.longitude.$id,
            options = props1.options,
            value = props1.value,
            required = props1.required,
            disabled = props1.disabled,
            readonly = props1.readonly,
            multiple = props1.multiple,
            autofocus = props1.autofocus,
            _onChange = props1.onChange,
            onBlur = props1.onBlur,
            onFocus = props1.onFocus,
            formData = props1.formData,
            placeholder = "لطفا نام شهر خود را انتخاب نمایید";

        //me.state = this.props.formData;
        //const value = me.state;
        //this.setState({cityId : cityId})
        console.log("thisProps location",this.props)
        return React.createElement("fieldset" , {
                id: id
            }
            ,React.createElement('div', {
                style: {
                    margin : "15px 0px ",
                    fontWeight: "bold"
                },
            }, `شهر`)
            /*,React.createElement('input', {id: latitudeId, placeholder: 'Enter a latitudeId', type: 'url'})
            ,React.createElement('input', {id: longitudeId, placeholder: 'Enter a longitudeId', type: 'url'})*/
            ,React.createElement("select", {
                id: cityId,
                multiple: multiple,
                className: "form-control select2city",
                value:  props1.formData.city,
                required: required,
                disabled: disabled || readonly,
                autoFocus: autofocus,
                onBlur: onBlur && function (event) {
                    var newValue = getValue(event, multiple);
                    onBlur(id, processValue(schema, newValue));
                },
                onFocus: onFocus && function (event) {
                    var newValue = getValue(event, multiple);
                    onFocus(id, processValue(schema, newValue));
                },
                onChange : function onChange(event) {
                    var val =  parseInt(getValue(event, multiple));
                    var newValue = { city : val };
                    //console.log("newValue" , newValue)
                    // console.log(schema)
                    props1.onChange(newValue);
                    var index = cities.map(function (img) { return img.id; }).indexOf(val);
                    if(index >-1) {
                        mymap.setView([cities[index].latitude, cities[index].longitude], 13);
                    }
                    //props[0].formData = {city :newValue};
                    //me.setState({selected : newValue })
                    _onChange(processValue(schema, newValue));
                }
            }, React.createElement("option", {
                value: ""
            }, placeholder), cities.map(function (_ref, i) {
                var value = _ref.id,
                    label = _ref.name;
                return React.createElement("option", {
                    key: i,
                    value: value.toString()
                }, label);
            })),
            React.createElement("div" , {
                className: "mt-2",
                id: "mapid"
            }));
    }
}

export default CustomLocationField;

/*

function getValue(event, multiple) {
    if (multiple) {
        return [].slice.call(event.target.options).filter(function (o) {
            return o.selected;
        }).map(function (o) {
            return o.value;
        });
    } else {
        return event.target.value;
    }
}


function processValue(schema, value) {
    // "enum" is a reserved word, so only "type" and "items" can be destructured
    var type = schema.type,
        items = schema.items;

    if (value === "") {
        return undefined;
    } else if (type === "array" && items && nums.has(items.type)) {
        return value.map(asNumber);
    } else if (type === "boolean") {
        return value === "true";
    } else if (type === "number") {
        return asNumber(value);
    } // If type is undefined, but an enum is present, try and infer the type from
    // the enum values


    if (schema["enum"]) {
        if (schema["enum"].every(function (x) {
            return guessType(x) === "number";
        })) {
            return asNumber(value);
        } else if (schema["enum"].every(function (x) {
            return guessType(x) === "boolean";
        })) {
            return value === "true";
        }
    }

    return value;
}


function CustomLocationField(...props){

    console.log("CustomLocationField Props" , props);
    var me =  this;
    let props1 = props[0]
   // me.sestate({  selected  : props.formData  })
    var schema = props1.schema,
        id = props1.idSchema.$id,
        cityId = props1.idSchema.city.$id,
        options = props1.options,
        value = props1.value,
        required = props1.required,
        disabled = props1.disabled,
        readonly = props1.readonly,
        multiple = props1.multiple,
        autofocus = props1.autofocus,
        _onChange = props1.onChange,
        onBlur = props1.onBlur,
        onFocus = props1.onFocus,
        formData = props1.formData,
        placeholder = "لطفا نام شهر خود را انتخاب نمایید";
    var enumOptions;
    var emptyValue = multiple ? [] : "";
    //console.log("locId" , id);
    return React.createElement("fieldset" , {
        id: id
    },  React.createElement("select", {
        id: cityId,
        multiple: multiple,
        className: "form-control",
        value:  props1.formData.city,
        required: required,
        disabled: disabled || readonly,
        autoFocus: autofocus,
        onBlur: onBlur && function (event) {
            var newValue = getValue(event, multiple);
            onBlur(id, processValue(schema, newValue));
        },
        onFocus: onFocus && function (event) {
            var newValue = getValue(event, multiple);
            onFocus(id, processValue(schema, newValue));
        },
        onChange : function onChange(event) {
            var val =  parseInt(getValue(event, multiple));
            var newValue = { city : val };
            //console.log("newValue" , newValue)
           // console.log(schema)
            props1.onChange(newValue);
            var index = cities.map(function (img) { return img.id; }).indexOf(val);
            if(index >-1) {
                mymap.setView([cities[index].latitude, cities[index].longitude], 13);
            }
            //props[0].formData = {city :newValue};
            //me.setState({selected : newValue })
            _onChange(processValue(schema, newValue));
        }
    }, React.createElement("option", {
        value: ""
    }, placeholder), cities.map(function (_ref, i) {
        var value = _ref.id,
            label = _ref.name;
         return React.createElement("option", {
            key: i,
            value: value.toString()
        }, label);
    })),
    React.createElement("div" , {
        id: "mapid"
    } ));
}

*/
