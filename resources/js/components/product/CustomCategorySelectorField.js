import React, { Component } from "react";
import PropTypes from "prop-types";
import { render } from "react-dom";
import HiddenWidget from "@rjsf/core/lib/components/widgets/HiddenWidget";

// Define a custom component for handling the root position object
class CustomCategorySelectorField extends React.Component {
    constructor(props) {
        super(props);
       // console.log("props",props);
        this.state = {...props.formData};
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

    render() {
        this.state = this.props.formData;
        const value = JSON.stringify(this.state);
        const id = this.props.idSchema.$id;
       // console.log("thisId" , id)
       // console.log("thisProps",this.props)
       // console.log("thisState",this.state);
        return (
            <div>
                <input type="hidden" id ={id} value={value} onChange={this.onChange(this.props.name)} />
            </div>
        );
    }
}
export default CustomCategorySelectorField;
